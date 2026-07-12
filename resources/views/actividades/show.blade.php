@extends('layouts.app')

@section('titulo', 'Detalle de actividad')

@section('contenido')
    <div x-data="{ modalAprobar: false, modalRechazar: false, participacionAbierta: {} }"
        class="max-w-2xl mx-auto p-5 md:p-8 space-y-6">

        @if(session('exito'))
            <div class="bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3">{{ session('exito') }}</div>
        @endif

        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $actividad->organizacion->color }}"></span>
                    <span class="text-xs font-medium text-ink/50">{{ $actividad->organizacion->nombre }}</span>
                </div>
                <h2 class="font-display font-semibold text-2xl">{{ $actividad->nombre }}</h2>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0"
                style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">
                {{ $actividad->estadoActual->nombre }}
            </span>
        </div>

        {{-- Datos generales --}}
        <div class="bg-white border border-line rounded-xl p-5 space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-[11px] text-ink/45 mb-0.5">Fecha</p>
                    <p class="text-sm font-mono">{{ $actividad->fecha->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-ink/45 mb-0.5">Hora</p>
                    <p class="text-sm font-mono">{{ \Carbon\Carbon::parse($actividad->hora_inicio)->format('g:i a') }} –
                        {{ \Carbon\Carbon::parse($actividad->hora_fin)->format('g:i a') }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-ink/45 mb-0.5">Lugar</p>
                    <p class="text-sm">{{ $actividad->lugar }}</p>
                </div>
            </div>
            <div class="border-t border-line pt-4">
                <p class="text-[11px] text-ink/45 mb-1">Objetivo</p>
                <p class="text-sm text-ink/80 leading-relaxed">{{ $actividad->objetivo }}</p>
            </div>
            @if($actividad->descripcion)
                <div>
                    <p class="text-[11px] text-ink/45 mb-1">Descripción</p>
                    <p class="text-sm text-ink/80 leading-relaxed">{{ $actividad->descripcion }}</p>
                </div>
            @endif
        </div>

        {{-- Participación --}}
        <div class="bg-white border border-line rounded-xl p-5">
            <h3 class="text-xs font-medium text-ink/50 uppercase tracking-wide mb-3.5">Participación esperada</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <p class="font-display font-semibold text-xl">{{ $actividad->asistencia_esperada ?? '—' }}</p>
                    <p class="text-[11px] text-ink/45">Asistencia</p>
                </div>

                @foreach(['miembro_nuevo' => ['label' => 'Miembros nuevos', 'valor' => $actividad->miembros_nuevos], 'amigo_ensenanza' => ['label' => 'Amigos', 'valor' => $actividad->amigos_ensenanza], 'menos_activo' => ['label' => 'Menos activos', 'valor' => $actividad->miembros_menos_activos]] as $tipo => $info)
                    @php $nombres = $actividad->participantes->where('tipo', $tipo)->pluck('nombre'); @endphp
                    <div>
                        <p class="font-display font-semibold text-xl">{{ $info['valor'] ?? '—' }}</p>
                        <p class="text-[11px] text-ink/45 mb-1">{{ $info['label'] }}</p>
                        @if($nombres->isNotEmpty())
                            <button @click="participacionAbierta['{{ $tipo }}'] = !participacionAbierta['{{ $tipo }}']"
                                class="text-[11px] text-accent hover:text-accent/80 flex items-center gap-0.5 transition-colors">
                                <svg class="w-3 h-3 transition-transform"
                                    :class="participacionAbierta['{{ $tipo }}'] && 'rotate-90'" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M9 6l6 6-6 6" stroke-linecap="round" />
                                </svg>
                                Ver nombres
                            </button>
                            <div x-show="participacionAbierta['{{ $tipo }}']" x-cloak class="mt-2 flex flex-wrap gap-1.5">
                                @foreach($nombres as $nombre)
                                    <span class="text-xs bg-paper border border-line px-2 py-0.5 rounded-full">{{ $nombre }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Presupuesto --}}
        @if($actividad->solicita_presupuesto)
            <div class="bg-white border border-line rounded-xl p-5">
                <div class="flex items-center justify-between mb-3.5">
                    <h3 class="text-xs font-medium text-ink/50 uppercase tracking-wide">Presupuesto solicitado</h3>
                    <span
                        class="font-mono text-lg font-semibold text-accent">${{ number_format($actividad->montoTotalSolicitado(), 2) }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($actividad->presupuestoItems as $item)
                        <div class="flex items-center justify-between text-sm py-1.5 border-b border-line last:border-0">
                            <span class="text-ink/70">{{ $item->categoria?->nombre ?? 'Monto aproximado' }}</span>
                            <span class="font-mono text-ink/70">${{ number_format($item->monto, 2) }}</span>
                        </div>
                        @if($item->justificacion)
                            <p class="text-xs text-ink/40 -mt-1 pb-1">{{ $item->justificacion }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recursos --}}
        @if($actividad->recursos->isNotEmpty())
            <div class="bg-white border border-line rounded-xl p-5">
                <h3 class="text-xs font-medium text-ink/50 uppercase tracking-wide mb-3.5">Recursos necesarios</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($actividad->recursos as $recurso)
                        <span class="text-xs bg-paper border border-line px-3 py-1.5 rounded-full">{{ $recurso->nombre }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Comentarios --}}
        @can('comentar', $actividad)
            <div class="bg-white border border-line rounded-xl p-5">
                <h3 class="text-xs font-medium text-ink/50 uppercase tracking-wide mb-3.5">Comentarios</h3>
                <div class="space-y-3 mb-4">
                    @forelse($actividad->comentarios as $comentario)
                        <div class="flex gap-3">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center font-display font-semibold text-primary text-[11px] shrink-0">
                                {{ mb_substr($comentario->usuario->name, 0, 1) }}
                            </div>
                            <div class="bg-paper rounded-lg px-3.5 py-2.5 flex-1">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-xs font-medium">{{ $comentario->usuario->name }}</span>
                                    <span
                                        class="text-[10px] text-ink/35 font-mono">{{ $comentario->created_at->format('d M') }}</span>
                                </div>
                                <p class="text-sm text-ink/70">{{ $comentario->comentario }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink/40">Sin comentarios todavía.</p>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('actividades.comentar', $actividad) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="comentario" placeholder="Agregar un comentario..." class="form-input text-sm"
                        required>
                    <button
                        class="bg-primary text-white text-xs font-medium px-4 rounded-lg hover:bg-primary-light transition-colors shrink-0">Comentar</button>
                </form>
            </div>
        @endcan

        {{-- Acciones del Consejo de Obispado --}}
        @if($actividad->estadoActual->nombre === 'Pendiente')
            @can('aprobar', $actividad)
                <div class="sticky bottom-0 bg-paper pt-2 pb-1 border-t border-line flex gap-3">
                    <button @click="modalRechazar = true"
                        class="flex-1 border border-brick/30 text-brick text-sm font-medium py-2.5 rounded-lg hover:bg-brick/5 transition-colors">Rechazar</button>
                    <button @click="modalAprobar = true"
                        class="flex-1 bg-sage text-white text-sm font-medium py-2.5 rounded-lg hover:bg-sage/90 transition-colors">Aprobar</button>
                </div>
            @endcan
        @endif

        {{-- Migrar (si quedó "No Procesada" al cerrar el trimestre) --}}
        @if($actividad->estadoActual->nombre === 'No Procesada')
            @can('migrarANuevoTrimestre', $actividad)
                <form method="POST" action="{{ route('actividades.migrar', $actividad) }}">
                    @csrf
                    <button
                        class="w-full bg-primary text-white text-sm font-medium py-2.5 rounded-lg hover:bg-primary-light transition-colors">Migrar
                        al trimestre activo</button>
                </form>
            @endcan
        @endif

        {{-- Modal Aprobar --}}
        <div x-show="modalAprobar" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center p-5 z-50">
            <div class="bg-white rounded-xl max-w-sm w-full p-6" @click.outside="modalAprobar = false">
                <h3 class="font-display font-semibold text-lg mb-1.5">Aprobar actividad</h3>
                <p class="text-sm text-ink/60 mb-5 leading-relaxed">Confirmas que el Consejo de Obispado acordó aprobar esta
                    actividad. Quedará registrada bajo tu usuario como quien ejecutó la decisión.</p>
                <div class="flex gap-3">
                    <button @click="modalAprobar = false"
                        class="flex-1 border border-line text-sm font-medium py-2.5 rounded-lg hover:bg-paper transition-colors">Cancelar</button>
                    <form method="POST" action="{{ route('actividades.aprobar', $actividad) }}" class="flex-1">
                        @csrf
                        <button
                            class="w-full bg-sage text-white text-sm font-medium py-2.5 rounded-lg hover:bg-sage/90 transition-colors">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Rechazar --}}
        <div x-show="modalRechazar" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center p-5 z-50">
            <div class="bg-white rounded-xl max-w-sm w-full p-6" @click.outside="modalRechazar = false">
                <h3 class="font-display font-semibold text-lg mb-1.5">Rechazar actividad</h3>
                <form method="POST" action="{{ route('actividades.rechazar', $actividad) }}">
                    @csrf
                    <p class="text-sm text-ink/60 mb-3">Indica el motivo — quedará visible para la presidencia.</p>
                    <textarea name="motivo" class="form-input mb-5" rows="3" required></textarea>
                    <div class="flex gap-3">
                        <button type="button" @click="modalRechazar = false"
                            class="flex-1 border border-line text-sm font-medium py-2.5 rounded-lg hover:bg-paper transition-colors">Cancelar</button>
                        <button
                            class="flex-1 bg-brick text-white text-sm font-medium py-2.5 rounded-lg hover:bg-brick/90 transition-colors">Confirmar
                            rechazo</button>
                    </div>
                </form>
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
    </style>
@endpush
