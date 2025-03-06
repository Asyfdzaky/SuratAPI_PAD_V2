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
        Schema::create('pejabat_rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rt')->constrained('RT')->onDelete('cascade');
            $table->foreignId('id_warga')->constrained('warga')->onDelete('cascade');
            $table->string('periode');
            $table->string('ttd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_r_t_s');
    }
};
