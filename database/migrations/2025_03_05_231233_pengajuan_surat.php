<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_warga')->constrained('warga')->onDelete('cascade');
            $table->foreignId('id_detailPemohon')->constrained('detail_pemohon_surat')->onDelete('cascade');
            $table->foreignId('id_rt')->constrained('RT')->onDelete('cascade');
            $table->foreignId('id_rw')->constrained('RW')->onDelete('cascade');
            $table->string('jenis_surat');
            $table->text('keterangan');
            $table->string('file_surat');
            $table->enum('status', ['Diajukan', 'Diproses_RT', 'Diproses_RW', 'Disetujui', 'Ditolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
