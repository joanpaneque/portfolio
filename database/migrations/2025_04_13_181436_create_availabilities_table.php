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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->json('weekly_hours')->comment('Horarios disponibles por defecto para cada día de la semana (0-6)');
            $table->json('specific_availability')->nullable()->comment('Excepciones de disponibilidad para fechas específicas');
            $table->json('blocked_slots')->nullable()->comment('Slots bloqueados para fechas específicas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
