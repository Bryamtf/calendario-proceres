@extends('layouts.app')

@section('titulo', auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia ? 'Mis Actividades' : 'Actividades')

@section('contenido')
    <div class="max-w-4xl mx-auto p-5 md:p-8 space-y-5">

        @if(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia)
            <div class="flex justify-end">
                <a href="{{ route('actividades.create') }}"
                    class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+
                    Nueva actividad</a>
            </div>
        @endif

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="divide-y divide-line">
                @forelse($actividades as $actividad)
                    <a href="{{ route('actividades.show', $actividad) }}"
                        class="block px-5 py-4 hover:bg-paper/60 transition-colors">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate">{{ $actividad->nombre }}</p>
                                <p class="text-xs text-ink/45 font-mono">{{ $actividad->organizacion->nombre }} ·
                                    {{ $actividad->fecha->format('d M Y') }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0"
                                style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">
                                {{ $actividad->estadoActual->nombre }}
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-ink/40">No hay actividades para mostrar en este trimestre.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
