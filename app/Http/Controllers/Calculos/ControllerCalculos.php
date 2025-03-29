<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Models\CrnubeSpreadsheetCCSS;
use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreadsheetTax;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrEmployee;
use http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use PHPUnit\Event\Telemetry\System;

class ControllerCalculos extends Controller
{


    public function generarCalculos()
    {
        $id = Cache::get('selected_employee');
        $employee = HrEmployee::find($id);

        if(!$employee) {
            echo "Empleado no encontrado";
            return;
        }

        // Obtener conceptos
        $conceptos = $employee->conceptos()->get();

        // Clasificar conceptos
        $ingresos = [];
        $deducciones = [];
        $totalIngresos = 0;
        $totalDeducciones = 0;

        foreach ($conceptos as $concepto) {
            $monto = $concepto->pivot->value * $concepto->value;

            if($concepto->type == 'ING') {
                $ingresos[] = ['nombre' => $concepto->name, 'monto' => $monto];
                $totalIngresos += $monto;
            } elseif($concepto->type == 'DED') {
                $deducciones[] = ['nombre' => $concepto->name, 'monto' => $monto];
                $totalDeducciones += $monto;
            }
        }

        $salarioBase = $totalIngresos - $totalDeducciones;

        // ======== CÁLCULO DETALLADO DE CCSS ========
        $detallesCCSS = DB::table('crnube_spreadsheet_c_c_s_s')->get();

        $ccssTotal = 0;
        $detallesCCCSText = [];
        foreach ($detallesCCSS as $ccss) {
            $monto = $salarioBase * $ccss->value / 100;
            $detallesCCCSSText[] = "- " . str_pad($ccss->name, 25) . number_format($monto, 2);

            // Solo sumar los que corresponden al empleado
            if(in_array($ccss->name, ['Pago CCSS Empleado', 'INS'])) {
                $ccssTotal += $monto;
            }
        }

        // ======== CÁLCULO PROGRESIVO DE IMPUESTOS ========
        $taxBrackets = DB::table('crnube_spreadsheet_taxes')
            ->orderBy('tax_range', 'asc')
            ->get();

        $remainingSalary = $salarioBase;
        $taxTotal = 0;
        $detallesTaxText = [];

        // Calcular impuesto progresivo
        for($i = 0; $i < count($taxBrackets); $i++) {
            $currentBracket = $taxBrackets[$i];
            $nextBracket = $taxBrackets[$i + 1] ?? null;

            $bracketLimit = $nextBracket ? $nextBracket->tax_range : PHP_INT_MAX;
            $taxableAmount = max(0, min($remainingSalary, $bracketLimit - $currentBracket->tax_range));

            if($taxableAmount > 0) {
                $taxForBracket = $taxableAmount * ($currentBracket->percentage / 100);
                $taxTotal += $taxForBracket;

                $detallesTaxText[] = "- " . str_pad("{$currentBracket->percentage}% sobre {$currentBracket->tax_range}", 25) . number_format($taxForBracket, 2);
            }

            $remainingSalary -= $taxableAmount;
            if($remainingSalary <= 0) break;
        }

        // ======== RESTANTE DE CÁLCULOS ========
        $aguinaldo = $salarioBase * 0.0833;
        $salarioNeto = ($salarioBase + $aguinaldo) - $taxTotal - $ccssTotal;

        // Mostrar resultados
        echo "<pre>";
        echo "==============================================\n";
        echo "               PLANILLA DE PAGO\n";
        echo "==============================================\n";
        echo "ID Empleado:      " . $employee->id . "\n";
        echo "Nombre:           " . $employee->name_related . "\n";
        echo "----------------------------------------------\n";

        // Ingresos
        echo "INGRESOS:\n";
        foreach ($ingresos as $ingreso) {
            echo "- " . str_pad($ingreso['nombre'], 25) . number_format($ingreso['monto'], 2) . "\n";
        }
        echo "Total Ingresos:   " . str_pad('', 15) . number_format($totalIngresos, 2) . "\n";

        echo "----------------------------------------------\n";

        // Deducciones
        echo "DEDUCCIONES:\n";
        foreach ($deducciones as $deduccion) {
            echo "- " . str_pad($deduccion['nombre'], 25) . number_format($deduccion['monto'], 2) . "\n";
        }
        echo "Total Deducciones: " . str_pad('', 14) . number_format($totalDeducciones, 2) . "\n";

        echo "----------------------------------------------\n";
        echo "SALARIO BASE:     " . str_pad('', 15) . number_format($salarioBase, 2) . "\n";

        echo "----------------------------------------------\n";
        echo "DESGLOSE CCSS:\n";
        echo implode("\n", $detallesCCCSSText);
        echo "\nTotal CCSS:       " . str_pad('', 15) . number_format($ccssTotal, 2) . "\n";

        echo "----------------------------------------------\n";
        echo "DESGLOSE IMPUESTOS:\n";
        echo implode("\n", $detallesTaxText);
        echo "\nTotal Impuestos:  " . str_pad('', 15) . number_format($taxTotal, 2) . "\n";

        echo "----------------------------------------------\n";
        echo "AGUINALDO:        " . str_pad('', 15) . number_format($aguinaldo, 2) . "\n";
        echo "SALARIO NETO:     " . str_pad('', 15) . number_format($salarioNeto, 2) . "\n";
        echo "==============================================\n";
        echo "</pre>";
    }
}
