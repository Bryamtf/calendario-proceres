@extends('layouts.app')

@section('titulo', 'Presupuesto')

@section('contenido')
    <div x-data="{ modalOrg: null, montoEditado: '' }" class="max-w-5xl mx-auto p-5 md:p-8 space-y-6">


        @if(!$trimestre)
            <div class="bg-brick/5 border border-brick/20 text-brick text-sm rounded-lg px-4 py-3">No hay un trimestre activo —
                no se puede asignar presupuesto hasta que se abra uno.</div>
        @else
            @php
                $totalAsignado = $organizaciones->sum(fn($o) => $o->presupuestos->first()?->monto_asignado ?? 0);
                $totalSolicitado = $organizaciones->sum(fn($o) => $o->presupuestos->first()?->montoSolicitado() ?? 0);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-line rounded-xl p-5">
                    <p class="text-xs text-ink/50 mb-1">Total asignado</p>
                    <p class="font-mono font-semibold text-2xl">${{ number_format($totalAsignado, 2) }}</p>
                </div>
                <div class="bg-white border border-line rounded-xl p-5">
                    <p class="text-xs text-ink/50 mb-1">Total solicitado</p>
                    <p class="font-mono font-semibold text-2xl text-accent">${{ number_format($totalSolicitado, 2) }}</p>
                </div>
                <div class="bg-white border border-line rounded-xl p-5">
                    <p class="text-xs text-ink/50 mb-1">Disponible</p>
                    <p class="font-mono font-semibold text-2xl text-sage">
                        ${{ number_format($totalAsignado - $totalSolicitado, 2) }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                <h3 class="font-display font-semibold text-sm">Por organización —
                    {{ $trimestre->nombre ?? 'sin trimestre activo' }}</h3>
                @can('create', \App\Models\PresupuestoOrganizacion::class)
                    <span class="text-[11px] text-ink/40">Clic en el lápiz para asignar</span>
                @endcan
            </div>
            <div class="divide-y divide-line">
                @foreach($organizaciones as $organizacion)
                    @php
                        $presupuesto = $organizacion->presupuestos->first();
                        $asignado = $presupuesto->monto_asignado ?? 0;
                        $solicitado = $presupuesto?->montoSolicitado() ?? 0;
                        $pct = $asignado > 0 ? min(100, round(($solicitado / $asignado) * 100)) : 0;
                    @endphp
                    <div class="px-5 py-4 flex items-center gap-4">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $organizacion->color }}"></span>
                        <div class="w-40 shrink-0">
                            <p class="text-sm font-medium">{{ $organizacion->nombre }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1 text-xs font-mono text-ink/50">
                                <span>${{ number_format($solicitado, 2) }} de ${{ number_format($asignado, 2) }}</span>
                                <span>${{ number_format(max(0, $asignado - $solicitado), 2) }} disp.</span>
                            </div>
                            <div class="h-1.5 w-full bg-paper rounded-full overflow-hidden">
                                <div class="h-full rounded-full"
                                    style="width: {{ $pct }}%; background: {{ $organizacion->color }}"></div>
                            </div>
                        </div>
                        @can('create', \App\Models\PresupuestoOrganizacion::class)
                            @if($trimestre)
                                <button
                                    @click="modalOrg = { id: {{ $organizacion->id }}, nombre: '{{ $organizacion->nombre }}', color: '{{ $organizacion->color }}', solicitado: {{ $solicitado }} }; montoEditado = '{{ $asignado }}'"
                                    class="text-ink/30 hover:text-primary transition-colors p-1.5 shrink-0"
                                    title="Editar monto asignado">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path
                                            d="M11 4H5a1 1 0 00-1 1v14a1 1 0 001 1h14a1 1 0 001-1v-6M18.5 3.5a2.1 2.1 0 013 3L11 17l-4 1 1-4z"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            @endif
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Modal de asignación --}}
        <div x-show="modalOrg" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center p-5 z-50">
            <div class="bg-white rounded-xl max-w-sm w-full p-6" @click.outside="modalOrg = null">
                <template x-if="modalOrg">
                    <form method="POST" :action="'{{ url('/presupuesto') }}/' + modalOrg.id">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full" :style="'background:' + modalOrg.color"></span>
                            <span class="text-xs text-ink/50" x-text="modalOrg.nombre"></span>
                        </div>
                        <h3 class="font-display font-semibold text-lg mb-4">Editar presupuesto asignado</h3>
                        <label class="form-label">Monto asignado — {{ $trimestre->nombre ?? '' }}</label>
                        <div class="relative mb-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40 font-mono text-sm">$</span>
                            <input type="number" name="monto_asignado" min="0" step="0.01" class="form-input font-mono pl-6"
                                x-model="montoEditado">
                        </div>
                        <p class="text-[11px] text-ink/40 mb-5">Ya solicitado este trimestre: $<span
                                x-text="modalOrg.solicitado.toFixed(2)"></span> (no editable aquí)</p>
                        <div class="flex gap-3">
                            <button type="button" @click="modalOrg = null"
                                class="flex-1 border border-line text-sm font-medium py-2.5 rounded-lg hover:bg-paper transition-colors">Cancelar</button>
                            <button
                                class="flex-1 bg-primary text-white text-sm font-medium py-2.5 rounded-lg hover:bg-primary-light transition-colors">Guardar</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <style>
        .form-input {
            width: 100%;
            border: 1px solid #E4DFD3;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.875rem;
            background: #fff;
        }

        .form-input:focus {
            border-color: #C08A3E;
            outline: none;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #26282B;
            margin-bottom: 0.35rem;
        }
    </style>
@endpush
