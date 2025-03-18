<?php

namespace App\Http\Controllers\Calculos;

use App\Http\Controllers\Controller;
use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrEmployee;
use http\Client\Request;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Event\Telemetry\System;

class ControllerCalculos extends Controller
{

    public function generarCalculos()
    {
        $id = Cache::get('selected_employee');
        $employee = HrEmployee::find($id);

        echo "Hola, $employee";
       // return back();
    }
}
