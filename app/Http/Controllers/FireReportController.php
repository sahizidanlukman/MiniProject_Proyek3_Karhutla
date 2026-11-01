<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\FireReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FireReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = FireReport::with(['area', 'reporter'])->latest();

        // Pelapor hanya boleh melihat daftar laporan miliknya sendiri (AC-05)
        if ($user->role === 'pelapor') {
            $query->where('user_id', $user->id);
        }

        $reports = $query->paginate(5)->withQueryString();
        $areas = Area::orderBy('name')->get();

        return view('reports.index', compact('reports', 'areas'));
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get();
        return view('reports.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'area_id' => ['required', 'exists:areas,id'],
            'incident_time' => ['required', 'date', 'before_or_equal:now'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'severity' => ['required', 'in:rendah,sedang,tinggi'],
            'description' => ['required', 'string', 'min:15'],
            'evidence' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Menyimpan file bukti ke storage/app/public/evidences (AC-04)
        $evidencePath = $request->file('evidence')->store('evidences', 'public');

        $report = FireReport::create([
            'user_id' => Auth::id(),
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_time' => $validated['incident_time'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'severity' => $validated['severity'],
            'evidence_path' => $evidencePath,
            'status' => 'menunggu_tinjauan',
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan indikasi kebakaran berhasil dikirim dan menunggu tinjauan relawan.');
    }

    public function show(FireReport $report)
    {
        $user = Auth::user();

        //Pelapor tidak dapat melihat laporan milik orang lain
        if ($user->role === 'pelapor' && $report->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat laporan milik pengguna lain.');
        }

        $report->load(['area', 'reporter', 'reviewer', 'statusLogs.user']);

        // Mengambil laporan lain di area yang sama untuk membantu Relawan Pemantau
        $relatedReports = FireReport::where('area_id', $report->area_id)
            ->where('id', '!=', $report->id)
            ->latest()
            ->take(5)
            ->get();

        return view('reports.show', compact('report', 'relatedReports'));
    }

    public function edit(FireReport $report)
    {
        $user = Auth::user();

        // AC-05: Pastikan hanya pemilik laporan (pelapor) yang dapat mengakses halaman edit
        if ($user->role !== 'pelapor' || $report->user_id !== $user->id) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengubah laporan milik Anda sendiri.');
        }

        // BR-01: Laporan hanya dapat diperbaiki oleh pelapor jika status masih menunggu_tinjauan
        if ($report->status !== 'menunggu_tinjauan') {
            return redirect()->route('reports.show', $report)
                ->withErrors(['business_rule' => 'BR-01: Laporan yang sudah ditinjau atau diproses tidak dapat diubah lagi oleh pelapor.']);
        }

        $areas = Area::orderBy('name')->get();
        return view('reports.edit', compact('report', 'areas'));
    }

    public function update(Request $request, FireReport $report)
    {
        $user = Auth::user();

        // AC-05: Proteksi kepemilikan laporan di sisi server
        if ($user->role !== 'pelapor' || $report->user_id !== $user->id) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengubah laporan pengguna lain.');
        }

        // BR-01: Proteksi Business Rule 1 di sisi server saat request PUT dikirim langsung
        if ($report->status !== 'menunggu_tinjauan') {
            return redirect()->route('reports.show', $report)
                ->withErrors(['business_rule' => 'BR-01: Perubahan ditolak karena laporan sudah berada pada status ' . $report->status . '.']);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'area_id' => ['required', 'exists:areas,id'],
            'incident_time' => ['required', 'date', 'before_or_equal:now'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'severity' => ['required', 'in:rendah,sedang,tinggi'],
            'description' => ['required', 'string', 'min:15'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('evidence')) {
            if ($report->evidence_path && Storage::disk('public')->exists($report->evidence_path)) {
                Storage::disk('public')->delete($report->evidence_path);
            }
            $report->evidence_path = $request->file('evidence')->store('evidences', 'public');
        }

        $report->update([
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_time' => $validated['incident_time'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'severity' => $validated['severity'],
            'evidence_path' => $report->evidence_path,
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }
}