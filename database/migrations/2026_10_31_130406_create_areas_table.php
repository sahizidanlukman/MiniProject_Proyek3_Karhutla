<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Kawasan Perkebunan Gambut Blok A
            $table->string('district'); // Contoh: Kec. Rasau Jaya, Kab. Kubu Raya
            $table->string('risk_level')->default('sedang'); // rendah, sedang, tinggi
            $table->string('contact_agency'); // Contoh: BPBD Kubu Raya & Manggala Agni
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
