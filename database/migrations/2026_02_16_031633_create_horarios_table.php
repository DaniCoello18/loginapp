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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique();
            $table->string('dia_semana',50);
            $table->time('hora_inicio');
            $table->time('duracion');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('edificio_id')->constrained('edificios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
