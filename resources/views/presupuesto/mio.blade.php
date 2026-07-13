@extends('layouts.app')

@section('titulo', 'Presupuesto')

@section('contenido')
    <div class="max-w-4xl mx-auto p-5 md:p-8 space-y-6">


        @if(!$trimestre)
            <div class="bg-brick/5 border border-brick/20 text-brick text-sm rounded-lg px-4 py-3">No hay un trimestre activo
                por el momento.</div>
        @elseif(!$presupuesto)
            <div class="bg-accent/5 border border-accent/20 text-ink/70 text-sm rounded-lg px-4 py-3">
                Tu organización todavía no tiene presupuesto asignado para {{ $trimestre->nombre }}. La Secretaría de Finanzas
                lo asigna al abrir el trimestre.
            </div>
        @else
            <div class="bg-primary text-white rounded-xl p-6 md:p-7">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-xs text-white/50 mb-0.5">{{ auth()->user()->organizacion->nombre }} ·
                            {{ $trimestre->nombre }}</p>
                        <h2 class="font-display font-semibold text-xl">Resumen de presupuesto</h2>
                    </div>
                    <span class="w-3 h-3 rounded-full shrink-0"
                        style="background: {{ auth()->user()->organizacion->color }}"></span>
                </div>
                @php
                    $solicitado = $presupuesto->montoSolicitado();
                    $pct = $presupuesto->monto_asignado > 0 ? min(100, round(($solicitado / $presupuesto->monto_asignado) * 100)) : 0;
                @endphp
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div>
                        <p class="text-[11px] text-white/50 mb-1">Asignado</p>
                        <p class="font-mono text-2xl font-semibold">${{ number_format($presupuesto->monto_asignado, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-white/50 mb-1">Solicitado</p>
                        <p class="font-mono text-2xl font-semibold text-accent">${{ number_format($solicitado, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-white/50 mb-1">Disponible</p>
                        <p class="font-mono text-2xl font-semibold text-sage">
                            ${{ number_format($presupuesto->montoDisponible(), 2) }}</p>
                    </div>
                </div>
                <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-accent rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endif

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-line">
                <h3 class="font-display font-semibold text-sm">Actividades con presupuesto solicitado</h3>
            </div>
            <div class="divide-y divide-line">
                @forelse($actividades as $actividad)
                    <a href="{{ route('actividades.show', $actividad) }}"
                        class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-paper/60 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate">{{ $actividad->nombre }}</p>
                            <p class="text-xs text-ink/40 font-mono">{{ $actividad->fecha->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                                style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">{{ $actividad->estadoActual->nombre }}</span>
                            <span
                                class="font-mono text-sm font-medium w-16 text-right">${{ number_format($actividad->montoTotalSolicitado(), 2) }}</span>
                        </div>
                    </a>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-ink/40">Ninguna actividad de este trimestre ha solicitado
                        presupuesto.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
