@extends('layouts.app')

@section('titulo', 'Reportes')

@section('contenido')
<div class="p-5 md:p-8">
    <div class="max-w-6xl grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6 items-start">

        {{-- Filtros --}}
        <form method="GET" action="{{ route('reportes.index') }}" class="bg-white border border-line rounded-xl p-5 space-y-4 lg:sticky lg:top-0">
            <h3 class="font-display font-semibold text-sm">Filtros</h3>

            <div>
                <label class="form-label">Trimestre</label>
                <select name="trimestre_id" class="form-input" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($trimestres as $trimestre)
                        <option value="{{ $trimestre->id }}" @selected($filtros['trimestre_id'] == $trimestre->id)>
                            {{ $trimestre->nombre }} @if($trimestre->estado === 'activo') (activo) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Organización</label>
                <select name="organizacion_id" class="form-input" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    @foreach($organizaciones as $org)
                        <option value="{{ $org->id }}" @selected($filtros['organizacion_id'] == $org->id)>{{ $org->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Estado</label>
                <div class="space-y-1.5">
                    @foreach(['Pendiente', 'Aprobada', 'Rechazada', 'Realizada', 'Cancelada', 'No Procesada'] as $estado)
                        <label class="flex items-center gap-2 text-sm text-ink/70">
                            <input type="checkbox" name="estados[]" value="{{ $estado }}" class="w-3.5 h-3.5 rounded accent-primary" {{ in_array($estado, $filtros['estados']) ? 'checked' : '' }} onchange="this.form.submit()">
                            <span>{{ $estado }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </form>

        {{-- Resultado --}}
        <div class="space-y-5">
            <div class="bg-white border border-line rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h3 class="font-display font-semibold">Actividades</h3>
                        <p class="text-xs text-ink/45 mt-0.5">{{ $actividades->count() }} resultado(s)</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('reportes.pdf', request()->query()) }}" class="flex items-center gap-1.5 text-xs font-medium border border-line px-3 py-2 rounded-lg hover:bg-paper transition-colors">PDF</a>
                        <a href="{{ route('reportes.excel', request()->query()) }}" class="flex items-center gap-1.5 text-xs font-medium border border-line px-3 py-2 rounded-lg hover:bg-paper transition-colors">Excel</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-paper text-left text-[11px] uppercase tracking-wide text-ink/45">
                                <th class="px-5 py-2.5 font-medium">Actividad</th>
                                <th class="px-5 py-2.5 font-medium">Organización</th>
                                <th class="px-5 py-2.5 font-medium">Fecha</th>
                                <th class="px-5 py-2.5 font-medium">Estado</th>
                                <th class="px-5 py-2.5 font-medium text-right">Presupuesto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse($actividades as $actividad)
                                <tr class="hover:bg-paper/50 transition-colors">
                                    <td class="px-5 py-3 font-medium">{{ $actividad->nombre }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1.5 text-ink/70">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ $actividad->organizacion->color }}"></span>
                                            {{ $actividad->organizacion->nombre }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-ink/60">{{ $actividad->fecha->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">
                                            {{ $actividad->estadoActual->nombre }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-right">
                                        {{ $actividad->solicita_presupuesto ? '$' . number_format($actividad->montoTotalSolicitado(), 2) : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">No hay actividades con estos filtros.</td></tr>
                            @endforelse
                        </tbody>
                        @if($actividades->isNotEmpty())
                            <tfoot>
                                <tr class="border-t border-line bg-paper/60">
                                    <td colspan="4" class="px-5 py-3 text-xs font-medium text-ink/50">Total presupuesto solicitado</td>
                                    <td class="px-5 py-3 font-mono font-semibold text-right">${{ number_format($actividades->sum(fn($a) => $a->montoTotalSolicitado()), 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['Aprobada' => 'sage', 'Pendiente' => 'accent', 'Rechazada' => 'brick', 'Realizada' => 'sage'] as $estado => $color)
                    <div class="bg-white border border-line rounded-xl p-4">
                        <p class="font-display font-semibold text-2xl text-{{ $color }}">{{ $actividades->where('estadoActual.nombre', $estado)->count() }}</p>
                        <p class="text-[11px] text-ink/45">{{ $estado }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
    .form-input { width: 100%; border: 1px solid #E4DFD3; border-radius: 0.5rem; padding: 0.55rem 0.75rem; font-size: 0.8rem; background: #fff; }
    .form-label { display:block; font-size: 0.75rem; font-weight: 500; color:#26282B99; margin-bottom: 0.3rem; }
</style>
@endpush
