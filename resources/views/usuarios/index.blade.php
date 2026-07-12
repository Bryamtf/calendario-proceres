@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="max-w-4xl mx-auto p-5 md:p-8 space-y-5">


        <div class="flex justify-end">
            <a href="{{ route('usuarios.create') }}"
                class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+
                Nuevo usuario</a>
        </div>

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="divide-y divide-line">
                @foreach($usuarios as $usuario)
                    <div class="px-5 py-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center font-display font-semibold text-primary text-sm shrink-0">
                                {{ mb_substr($usuario->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate">{{ $usuario->name }}</p>
                                <p class="text-xs text-ink/45 truncate">
                                    {{ $usuario->email }} · {{ $usuario->role->nombre }}
                                    @if($usuario->organizacion) · {{ $usuario->organizacion->nombre }} @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span
                                class="text-[11px] font-medium px-2.5 py-1 rounded-full {{ $usuario->activo ? 'bg-sage/10 text-sage' : 'bg-ink/5 text-ink/40' }}">
                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                            <a href="{{ route('usuarios.edit', $usuario) }}"
                                class="text-ink/30 hover:text-primary transition-colors p-1.5" title="Editar">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path
                                        d="M11 4H5a1 1 0 00-1 1v14a1 1 0 001 1h14a1 1 0 001-1v-6M18.5 3.5a2.1 2.1 0 013 3L11 17l-4 1 1-4z"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            @if($usuario->id !== auth()->id() && $usuario->role->nombre !== 'Administrador')
                                <form method="POST" action="{{ route('usuarios.toggleActivo', $usuario) }}">
                                    @csrf
                                    @method('PATCH')
                                    @if($usuario->activo)
                                        <button class="text-ink/30 hover:text-brick transition-colors p-1.5" title="Desactivar">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path
                                                    d="M4 7h16M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m2 0v13a1 1 0 01-1 1H8a1 1 0 01-1-1V7h10zM10 11v6M14 11v6"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @else
                                        <button class="text-ink/30 hover:text-sage transition-colors p-1.5" title="Activar">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M4 4v5h5M20 20v-5h-5M4.5 9a8 8 0 0113.9-4.5M19.5 15a8 8 0 01-13.9 4.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
