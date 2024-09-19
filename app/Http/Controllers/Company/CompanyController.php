<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function changeCompany(Request $request): RedirectResponse
    {
        $request->session()->put('company', $request->input('company'));
        return redirect()->back();

        // Redirigir de nuevo a la página actual o a otra
        //return redirect()->back()->with('success', 'Compañía cambiada con éxito');
    }
}
