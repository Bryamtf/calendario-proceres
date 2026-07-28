<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit');
    }

    /**
     * Solo se puede editar el nombre — el correo es de solo lectura 
     * es el usuario de login y el destino de notificaciones críticas, un cambio
     * accidental deja a la persona sin poder entrar. Si hace falta cambiarlo,
     * lo hace el Admin desde /usuarios (con trazabilidad de quién lo cambió).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updateProfileInformation', [
            'name' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->update(['name' => $validated['name']]);

        return redirect()
            ->route('profile.edit')
            ->with('exito', 'Perfil actualizado.');
    }

}
