<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    /**
     * Mostrar el panel de administración de disponibilidad
     */
    public function index()
    {
        // Obtener la configuración de disponibilidad actual o crear una por defecto
        $availability = Availability::firstOrCreate(
            ['id' => 1],
            [
                'weekly_hours' => [
                    '0' => ['10:00', '13:30'], // Lunes
                    '1' => [], // Martes
                    '2' => ['15:00', '15:15'], // Miércoles
                    '3' => ['05:30'], // Jueves
                    '4' => ['00:00', '01:00'], // Viernes
                    '5' => [], // Sábado
                    '6' => [], // Domingo
                ],
                'specific_availability' => [],
                'blocked_slots' => [],
            ]
        );

        return Inertia::render('Admin/Availability', [
            'availability' => $availability
        ]);
    }

    /**
     * Actualizar la configuración de disponibilidad
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'weekly_hours' => 'required|array',
            'specific_availability' => 'array',
            'blocked_slots' => 'array',
        ]);

        try {
            $availability = Availability::findOrFail(1);
            $availability->update($validatedData);

            return redirect()->back()->with('success', 'Configuración de disponibilidad actualizada correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar la disponibilidad: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar la disponibilidad');
        }
    }

    /**
     * Obtener la configuración de disponibilidad (para API)
     */
    public function getAvailability()
    {
        $availability = Availability::firstOrCreate(['id' => 1], [
            'weekly_hours' => [
                '0' => ['10:00', '13:30'], // Lunes
                '1' => [], // Martes
                '2' => ['15:00', '15:15'], // Miércoles
                '3' => ['05:30'], // Jueves
                '4' => ['00:00', '01:00'], // Viernes
                '5' => [], // Sábado
                '6' => [], // Domingo
            ],
            'specific_availability' => [],
            'blocked_slots' => [],
        ]);

        return response()->json($availability);
    }
}
