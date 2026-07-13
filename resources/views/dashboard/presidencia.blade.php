@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')
    <div class="p-5 md:p-8 max-w-6xl space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-sm text-ink/50">{{ auth()->user()->organizacion->nombre }}</p>
                <h2 class="font-display font-semibold text-2xl">Hola, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
            </div>
            <a href="{{ route('actividades.create') }}"
                class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+
                Proponer actividad</a>
        </div>

        @if(!$trimestre)
            <div class="bg-brick/5 border border-brick/20 text-brick text-sm rounded-lg px-4 py-3">No hay un trimestre activo
                por el momento.</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Pendientes</p>
                <p class="font-display font-semibold text-3xl text-accent">{{ $pendientes }}</p>
                <p class="text-xs text-ink/40 mt-1">esperando al Consejo</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Aprobadas (trimestre)</p>
                <p class="font-display font-semibold text-3xl text-sage">{{ $aprobadas }}</p>
                <p class="text-xs text-ink/40 mt-1">listas para el calendario</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Próxima actividad</p>
                @if($proxima)
                    <p class="font-display font-semibold text-lg leading-tight">{{ $proxima->nombre }}</p>
                    <p class="text-xs text-ink/40 mt-1 font-mono">{{ $proxima->fecha->format('d M') }}</p>
                @else
                    <p class="font-display font-semibold text-xl text-ink/25">—</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border border-line rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-line">
                    <h3 class="font-display font-semibold">Tus actividades recientes</h3>
                </div>
                <div class="divide-y divide-line">
                    @forelse($recientes as $actividad)
                        <a href="{{ route('actividades.show', $actividad) }}"
                            class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-paper/60 transition-colors">
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate">{{ $actividad->nombre }}</p>
                                <p class="text-xs text-ink/40 font-mono">{{ $actividad->fecha->format('d M Y') }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0"
                                style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">{{ $actividad->estadoActual->nombre }}</span>
                        </a>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-ink/40">Todavía no has propuesto actividades este
                            trimestre.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-line rounded-xl p-5">
                <h3 class="font-display font-semibold mb-4">Presupuesto</h3>
                @if($presupuesto)
                    @php
                        $solicitado = $presupuesto->montoSolicitado();
                        $pct = $presupuesto->monto_asignado > 0 ? min(100, round(($solicitado / $presupuesto->monto_asignado) * 100)) : 0;
                    @endphp
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="font-mono text-2xl font-medium">${{ number_format($solicitado, 2) }}</span>
                        <span class="text-xs text-ink/40">de ${{ number_format($presupuesto->monto_asignado, 2) }}</span>
                    </div>
                    <div class="h-2 w-full bg-paper rounded-full overflow-hidden mb-1.5">
                        <div class="h-full bg-accent rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-ink/45 font-mono">${{ number_format($presupuesto->montoDisponible(), 2) }}
                        disponibles</p>
                @else
                    <p class="text-sm text-ink/40">Sin presupuesto asignado todavía.</p>
                @endif
                <a href="{{ route('presupuesto.index') }}"
                    class="text-xs font-medium text-primary hover:text-primary-light transition-colors mt-3 inline-block">Ver
                    detalle →</a>
            </div>
        </div>
    </div>
@endsection
