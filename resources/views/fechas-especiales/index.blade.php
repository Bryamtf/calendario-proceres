@extends('layouts.app')

@section('titulo', 'Fechas Especiales')

@section('contenido')
    <div class="max-w-2xl mx-auto p-5 md:p-8 space-y-5">

        @if(session('exito'))
            <div class="bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3">{{ session('exito') }}</div>
        @endif

        @can('create', \App\Models\FechaEspecial::class)
            <div class="flex justify-end">
                <a href="{{ route('fechas-especiales.create') }}"
                    class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+
                    Nueva fecha especial</a>
            </div>
        @endcan

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="divide-y divide-line">
                @forelse($fechas as $fecha)
                    <div class="px-5 py-4 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="w-2 h-2 rounded-full shrink-0"
                                    style="background: {{ $fecha->tipo->color ?? '#26282B' }}"></span>
                                <span class="text-[11px] text-ink/45">{{ $fecha->tipo->nombre }}</span>
                            </div>
                            <p class="text-sm font-medium truncate">{{ $fecha->nombre }}</p>
                            <p class="text-xs text-ink/40 font-mono">
                                {{ $fecha->fecha_inicio->format('d M Y') }}
                                @if(!$fecha->fecha_inicio->isSameDay($fecha->fecha_fin))
                                    — {{ $fecha->fecha_fin->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            @can('update', $fecha)
                                <a href="{{ route('fechas-especiales.edit', $fecha) }}"
                                    class="text-ink/30 hover:text-primary transition-colors p-1.5" title="Editar">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path
                                            d="M11 4H5a1 1 0 00-1 1v14a1 1 0 001 1h14a1 1 0 001-1v-6M18.5 3.5a2.1 2.1 0 013 3L11 17l-4 1 1-4z"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            @endcan
                            @can('delete', $fecha)
                                <form method="POST" action="{{ route('fechas-especiales.destroy', $fecha) }}"
                                    onsubmit="return confirm('¿Eliminar esta fecha especial?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-ink/30 hover:text-brick transition-colors p-1.5" title="Eliminar">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path
                                                d="M4 7h16M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m2 0v13a1 1 0 01-1 1H8a1 1 0 01-1-1V7h10zM10 11v6M14 11v6"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-ink/40">No hay fechas especiales registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
