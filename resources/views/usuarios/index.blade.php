@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="max-w-4xl mx-auto p-5 md:p-8 space-y-5">

        @if(session('exito'))
            <div class="bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3">{{ session('exito') }}</div>
        @endif

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
                                class="text-xs font-medium text-primary hover:text-primary-light transition-colors">Editar</a>
                            <form method="POST" action="{{ route('usuarios.toggleActivo', $usuario) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs font-medium text-ink/40 hover:text-brick transition-colors">
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
