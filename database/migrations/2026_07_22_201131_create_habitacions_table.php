<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();

            $table->string('numero', 10)->unique();
            $table->integer('piso');

            $table->enum('estado', [
                'Disponible',
                'Ocupada',
                'Limpieza',
                'Mantenimiento'
            ])->default('Disponible');

            // Apunta a la tabla 'tipo_habitaciones' en español
            $table->foreignId('tipo_habitacion_id')
                ->constrained('tipo_habitaciones')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};