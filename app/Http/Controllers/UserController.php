<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Organizacion;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $usuarios = User::with(['role', 'organizacion'])->orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('usuarios.create', [
            'roles' => Role::orderBy('nivel_jerarquico')->get(),
            'organizaciones' => Organizacion::activas()->orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $datos['password'] = Hash::make($datos['password']);
        $datos['activo'] = $datos['activo'] ?? true;

        // organizacion_id solo tiene sentido si el rol es Presidencia (Fase 2)
        $rol = Role::find($datos['role_id']);
        if ($rol->nombre !== 'Presidencia') {
            $datos['organizacion_id'] = null;
        }

        User::create($datos);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario creado.');
    }

    public function edit(User $usuario): View
    {
        $this->authorize('update', $usuario);

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles' => Role::orderBy('nivel_jerarquico')->get(),
            'organizaciones' => Organizacion::activas()->orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $usuario): RedirectResponse
    {
        $datos = $request->validated();

        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $rol = Role::find($datos['role_id']);
        if ($rol->nombre !== 'Presidencia') {
            $datos['organizacion_id'] = null;
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado.');
    }

    /** Nunca se elimina — solo se desactiva. */
    public function toggleActivo(User $usuario): RedirectResponse
    {
        $this->authorize('update', $usuario);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes activar o desactivar tu propia cuenta.');
        }

        if ($usuario->role->nombre === 'Administrador') {
            return back()->with('error', 'La cuenta de Administrador no se puede desactivar.');
        }

        $usuario->update(['activo' => !$usuario->activo]);

        return back()->with('exito', $usuario->activo ? 'Usuario activado.' : 'Usuario desactivado.');
    }
}
