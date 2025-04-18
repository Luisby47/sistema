<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Mail\ContactMailable;
use App\Models\CrnubeSpreadsheetCCSS;
use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreadsheetTax;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrEmployee;
use App\Models\ResCompany;
use http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MoonShine\Enums\ToastType;
use MoonShine\Http\Responses\MoonShineJsonResponse;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use PHPUnit\Event\Telemetry\System;
use Barryvdh\DomPDF\Facade\Pdf;

class ControllerCalculos extends Controller
{

    public function generarComprobanteSalarial(){
        $id = Cache::get('selected_employee');
        $employee = HrEmployee::find($id);

        $company_id = Cache::get('company');
        $company = ResCompany::find($company_id);


        if(!$employee) {
            MoonShineUI::toast("No se ha seleccionado ningún empleado colaborador", 'error');
            return back();
        }

        $conceptoSalarioBase = DB::table('crnube_spreadsheet_conceptos')->where('name', 'Salario Base')->first();
        $conceptoSalarioBaseId = $conceptoSalarioBase->id;
        $salarioBase = $employee->conceptos()->where('id', $conceptoSalarioBaseId)->first()->pivot->value * $conceptoSalarioBase->value;
        $ccss_empleado = DB::table('crnube_spreadsheet_c_c_s_s')->where('name', 'Pago CCSS Empleado')->first();


        $xml = new \SimpleXMLElement('<comprobante/>');
        $xml->addChild('id', $employee->id);
        $xml->addChild('nombre', $employee->name_related);
        $xml->addChild('cedula', $employee->identification_id);
        $xml->addChild('puesto', $employee->job->name);
        $xml->addChild('dpto', $employee->department->name);
        $xml->addChild('empresa', $company->name);

        $name = 'employee_' . $employee->identification_id . '.xml';
        $xmlPath = storage_path('app/' . $name);

        $conceptos = $employee->conceptos()->get();
        $totalIngresos = 0;
        $totalDeducciones = 0;

        $ingresosXml = $xml->addChild('ingresos');
        $deduccionesXml = $xml->addChild('deducciones');

        $deduccionCCSS = $deduccionesXml->addChild('concepto');
        $monto = $salarioBase * $ccss_empleado->value / 100;
        $deduccionCCSS->addChild('nombre', htmlspecialchars($ccss_empleado->name, ENT_XML1, 'UTF-8'));
        $deduccionCCSS->addChild('monto', number_format($monto, 2, '.', ''));
        $totalDeducciones += $monto;

        foreach ($conceptos as $concepto) {
            $monto = $concepto->pivot->value * $concepto->value;

            if ($concepto->type == 'ING') {
                $conceptoXml = $ingresosXml->addChild('concepto');
                $conceptoXml->addChild('nombre', htmlspecialchars($concepto->name, ENT_XML1, 'UTF-8'));
                $conceptoXml->addChild('monto', number_format($monto, 2, '.', ''));
                $totalIngresos += $monto;
            } elseif ($concepto->type == 'DED') {
                $conceptoXml = $deduccionesXml->addChild('concepto');
                $conceptoXml->addChild('nombre', htmlspecialchars($concepto->name, ENT_XML1, 'UTF-8'));
                $conceptoXml->addChild('monto', number_format($monto, 2, '.', ''));
                $totalDeducciones += $monto;
            }
        }

        $salarioNeto = $totalIngresos - $totalDeducciones;

        $xml->addChild('total_ingresos', number_format($totalIngresos, 2, '.', ''));
        $xml->addChild('total_deducciones', number_format($totalDeducciones, 2, '.', ''));
        $xml->addChild('salario_neto', number_format($salarioNeto, 2, '.', ''));

        $xml->asXML($xmlPath);
        $xmlString = file_get_contents($xmlPath);
        $data = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
        $data = json_decode(json_encode($data), true);
        $nombre = $data['nombre'];
        $cedula = $data['cedula'];
        $empresa = $data['empresa'];
        $puesto = $data['puesto'];
        $dpto = $data['dpto'];
        $deducciones = isset($data['deducciones']['concepto'])
            ? (isset($data['deducciones']['concepto'][0]) ? (array) $data['deducciones']['concepto'] : [(array) $data['deducciones']['concepto']])
            : [];

        $ingresos = isset($data['ingresos']['concepto'])
            ? (isset($data['ingresos']['concepto'][0]) ? (array) $data['ingresos']['concepto'] : [(array) $data['ingresos']['concepto']])
            : [];

        $totalIngresos = $data['total_ingresos'];
        $totalDeducciones = $data['total_deducciones'];
        $salarioNeto = $data['salario_neto'];

        //dd($data);
        $pdf = Pdf::loadView('pdf.comprobanteToPdf', compact('id', 'nombre', 'cedula', 'ingresos', 'deducciones', 'totalIngresos', 'totalDeducciones', 'puesto','dpto','empresa', 'salarioNeto'))
            ->setPaper('ledger', 'landscape');

        // Guardar temporalmente el pdf
        $fileName =$employee->identification_id . '_comprobante_salarial.pdf';
        $pdfPath = storage_path('app/' . $fileName);
        $pdf->save($pdfPath);

        Cache::put('temp_pdf_path', $pdfPath);

        return $pdf->download($fileName);
    }

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


    public function sendEmail()
    {
        try {
            $pdfPath = Cache::get('temp_pdf_path');

            if (!$pdfPath || !file_exists($pdfPath)) {
                throw new \Exception("El comprobante no está disponible. Por favor, genera el PDF primero.");
            }

            $employeeIdinCache = Cache::get('selected_employee');
            $employeeCedula = HrEmployee::find($employeeIdinCache)
                ->identification_id;

            $pdfEmployeeId = basename($pdfPath, '_comprobante_salarial.pdf'); // solo deja el id

            if($employeeCedula != $pdfEmployeeId) {
                throw new \Exception("El comprobante no corresponde al empleado seleccionado.");
            }

            $email = '#'; // colocar el correo al que se enviará el comprobante, en un futuro debería tomarse el del empleado
            Mail::to($email)->send(new ContactMailable($pdfPath));
            unlink($pdfPath);
            Cache::forget('temp_pdf_path');

            $employeeName = HrEmployee::find($employeeIdinCache)->name_related;
            MoonShineUI::toast("Se ha enviado el correo existosamente al colaborador $employeeName", 'success');
            return back();
        } catch (\Exception $e) {
            MoonShineUI::toast('Error al enviar el comprobante: ' . $e->getMessage(), 'error');
            return back();
        }
    }

}
