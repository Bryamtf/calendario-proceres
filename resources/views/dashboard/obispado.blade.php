@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')
    <div class="p-5 md:p-8 max-w-6xl space-y-6">
        <div>
            <p class="text-sm text-ink/50">{{ auth()->user()->role->nombre }}</p>
            <h2 class="font-display font-semibold text-2xl">Resumen del trimestre</h2>
        </div>

        @if(!$trimestre)
            <div class="bg-brick/5 border border-brick/20 text-brick text-sm rounded-lg px-4 py-3">No hay un trimestre activo
                por el momento.</div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Total actividades</p>
                <p class="font-display font-semibold text-3xl">{{ $total }}</p>
            </div>
            <div class="bg-white border-2 border-accent/30 rounded-xl p-5 bg-accent/5">
                <p class="text-xs text-ink/50 mb-1">Pendientes de revisar</p>
                <p class="font-display font-semibold text-3xl text-accent">{{ $pendientesCount }}</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Aprobadas</p>
                <p class="font-display font-semibold text-3xl text-sage">{{ $aprobadasCount }}</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Presupuesto solicitado</p>
                <p class="font-display font-semibold text-2xl font-mono">${{ number_format($totalPresupuesto, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border border-line rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                    <h3 class="font-display font-semibold">Cola de aprobación</h3>
                    <a href="{{ route('actividades.index') }}"
                        class="text-xs text-primary hover:text-primary-light transition-colors">Ver todas →</a>
                </div>
                <div class="divide-y divide-line">
                    @forelse($colaAprobacion as $actividad)
                        <a href="{{ route('actividades.show', $actividad) }}"
                            class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-paper/60 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-2 h-2 rounded-full shrink-0"
                                    style="background: {{ $actividad->organizacion->color }}"></span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $actividad->nombre }}</p>
                                    <p class="text-xs text-ink/40">{{ $actividad->organizacion->nombre }} ·
                                        {{ $actividad->fecha->format('d M') }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-ink/40 shrink-0">Ver →</span>
                        </a>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-ink/40">No hay actividades pendientes de revisión.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-line rounded-xl p-5">
                <h3 class="font-display font-semibold mb-4">Próximas actividades</h3>
                @forelse($proximas as $actividad)
                    <div class="flex gap-3 mb-3 last:mb-0">
                        <div class="w-10 text-center shrink-0">
                            <p class="font-mono text-xs text-ink/40">{{ mb_strtoupper($actividad->fecha->format('M')) }}</p>
                            <p class="font-display font-semibold text-lg leading-none">{{ $actividad->fecha->format('d') }}</p>
                        </div>
                        <p class="text-sm pt-0.5">{{ $actividad->nombre }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink/40">Nada aprobado próximamente.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                <h3 class="font-display font-semibold text-sm">Presupuesto por organización</h3>
                <a href="{{ route('presupuesto.index') }}"
                    class="text-xs text-primary hover:text-primary-light transition-colors">Ver todo →</a>
            </div>
            <div class="divide-y divide-line">
                @foreach($organizaciones as $organizacion)
                    @php
                        $p = $organizacion->presupuestos->first();
                        $asignado = $p->monto_asignado ?? 0;
                        $solicitado = $p?->montoSolicitado() ?? 0;
                        $pct = $asignado > 0 ? min(100, round(($solicitado / $asignado) * 100)) : 0;
                    @endphp
                    <div class="px-5 py-3 flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $organizacion->color }}"></span>
                        <div class="w-40 shrink-0">
                            <p class="text-sm">{{ $organizacion->nombre }}</p>
                        </div>
                        <div class="flex-1">
                            <div class="h-1.5 w-full bg-paper rounded-full overflow-hidden">
                                <div class="h-full rounded-full"
                                    style="width: {{ $pct }}%; background: {{ $organizacion->color }}"></div>
                            </div>
                        </div>
                        <span
                            class="font-mono text-xs text-ink/50 w-32 text-right shrink-0">${{ number_format($solicitado, 2) }}
                            / ${{ number_format($asignado, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
