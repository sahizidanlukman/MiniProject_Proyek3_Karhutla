<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fire_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->dateTime('incident_time');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('severity', ['rendah', 'sedang', 'tinggi']);
            $table->string('evidence_path');
            $table->enum('status', [
                'menunggu_tinjauan',
                'ditinjau',
                'diteruskan',
                'selesai',
                'ditolak',
            ])->default('menunggu_tinjauan');

            // Kolom hasil peninjauan Relawan Pemantau
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_notes')->nullable();
            $table->boolean('needs_forwarding')->default(false);

            // Kolom pencatatan penerusan oleh Koordinator Relawan
            $table->string('forwarded_to')->nullable();
            $table->dateTime('forwarded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fire_reports');
    }
};
