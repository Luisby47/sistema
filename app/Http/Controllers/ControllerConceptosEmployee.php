<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;

class ControllerConceptosEmployee extends Controller
{
    public function generate(MoonShineRequest $request): RedirectResponse
    {
        // Validar el tipo de plantilla
        $validated = $request->validate([
            'value_type' => 'required|in:SEMA,QUIN,MENS',
            'notes' => 'nullable|string|max:255',
        ]);

        // Obtener company_id desde cache
        $companyId = Cache::get('company');
        if (!$companyId) {
            MoonShineUI::toast('Error: No se encontró el ID de la compañía', 'error');
            return back();
        }

        // Determinar tipo de plantilla
        $type = match($validated['value_type']) {
            'SEMA' => 'SEMANAL',
            'QUIN' => 'QUINCENAL',
            'MENS' => 'MENSUAL'
        };

        // Calcular fechas según el tipo
        $now = Carbon::now();
        [$dateStart, $dateEnd] = $this->calculateDateRange($validated['value_type'], $now);

        // Generar número consecutivo
        $nextNumber = $this->generateNextNumber($companyId, $type, $now->year);

        // Variable para notas
        $notes = $validated['notes'] ?? null;

        // Crear registro en la base de datos
        DB::table('crnube_spreadsheet_headers')->insert([
            'company_id' => $companyId,
            'number' => $nextNumber,
            'type' => $type,
            'date_start' => $dateStart->format('Y-m-d'),
            'date_end' => $dateEnd->format('Y-m-d'),
            'status' => 'Pendiente',
            'notes' => $notes,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        MoonShineUI::toast("Planilla {$type} creada exitosamente!", 'success');
        return back();
    }

    private function calculateDateRange(string $type, Carbon $date): array
    {
        return match($type) {
            'MENS' => [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ],
            'QUIN' => $date->day <= 15
                ? [$date->copy()->startOfMonth(), $date->copy()->startOfMonth()->addDays(14)]
                : [$date->copy()->startOfMonth()->addDays(15), $date->copy()->endOfMonth()],
            'SEMA' => [
                $date->copy()->startOfWeek()->month === $date->month
                    ? $date->copy()->startOfWeek()
                    : $date->copy()->startOfMonth(),
                $date->copy()->endOfWeek()->month === $date->month
                    ? $date->copy()->endOfWeek()
                    : $date->copy()->endOfMonth()
            ]
        };
    }
    private function generateNextNumber(?string $lastNumber, string $type): string
    {
        if ($lastNumber) {
            // Extraer la parte numérica: "0001-MENS" => 1
            $numberPart = (int) explode('-', $lastNumber)[0];
            $nextSequence = $numberPart + 1;
        } else {
            $nextSequence = 1; // Iniciar desde 1 si no hay registros
        }

        // Formatear a 4 dígitos + tipo
        return str_pad($nextSequence, 4, '0', STR_PAD_LEFT) . '-' . $type;
    }

    private function generateConsecutiveNumber(int $companyId, string $type, int $year): string
    {
        $lastNumber = DB::table('crnube_spreadsheet_headers')
            ->where('company_id', $companyId)
            ->where('type', $type)
            ->whereYear('created_at', $year)
            ->max('number');

        $nextSequence = $lastNumber
            ? (int) substr($lastNumber, 0, 3) + 1
            : 1;

        return str_pad($nextSequence, 3, '0', STR_PAD_LEFT) . '-' . $year;
    }

}
