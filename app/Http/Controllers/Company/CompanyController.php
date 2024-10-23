<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use MoonShine\MoonShineUI;

class CompanyController extends Controller
{
    public function changeCompany(Request $request): RedirectResponse
    {
        // Validar que se haya enviado una empresa


        // Obtener la empresa seleccionada
        $company = $request->input('company');

        // Guardar la empresa en la caché o en la sesión (puedes elegir el que prefieras)
        Cache::put('company', $company);
        // O si prefieres guardar en la sesión
        // session(['selected_company' => $company]);

        // Mostrar un mensaje de éxito en la interfaz
        MoonShineUI::toast('Empresa cambiada a ' . $company, 'success');

        // Redirigir de vuelta a la misma página o a una diferente
        return redirect()->back()->with('success', 'La empresa ha sido cambiada correctamente.');

    }
}
