@extends('layouts.app')

@section('titulo', 'Calendario')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>
    <style>
        #calendar {
            font-family: 'Inter', sans-serif;
        }

        .fc {
            --fc-border-color: #E4DFD3;
            --fc-page-bg-color: #ffffff;
            --fc-neutral-bg-color: #FAF8F4;
            --fc-today-bg-color: #C08A3E14;
        }

        .fc .fc-toolbar {
            display: none;
        }

        .fc .fc-col-header-cell {
            background: #FAF8F4;
            padding: 10px 0;
            border-color: #E4DFD3;
        }

        .fc .fc-col-header-cell-cushion {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #26282B99;
            font-weight: 600;
            text-decoration: none;
        }

        .fc .fc-daygrid-day-number {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12.5px;
            color: #26282B;
            text-decoration: none;
            padding: 6px 8px;
        }

        .fc .fc-day-today .fc-daygrid-day-number {
            color: #C08A3E;
            font-weight: 600;
        }

        .fc-event {
            border: none !important;
            padding: 1px 6px !important;
            border-radius: 5px !important;
            font-size: 11.5px !important;
            font-weight: 500;
        }

        .fc-event.evt-pendiente {
            background: repeating-linear-gradient(135deg, var(--org-color), var(--org-color) 4px, color-mix(in srgb, var(--org-color) 60%, white) 4px, color-mix(in srgb, var(--org-color) 60%, white) 8px) !important;
            color: #fff !important;
        }

        .fc-event.evt-aprobada {
            background: var(--org-color) !important;
            color: #fff !important;
        }

        .fc-event.evt-especial {
            background: #2B3A4A !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: 500;
        }

        .fc-daygrid-event-dot {
            display: none;
        }

        .fc-daygrid-day-frame {
            cursor: pointer;
        }

        .fc-daygrid-day-frame:hover {
            background: #C08A3E0d;
        }
    </style>
@endpush

@section('contenido')
    <div class="p-5 md:p-8">
        <div class="max-w-7xl grid grid-cols-1 xl:grid-cols-[1fr_260px] gap-6 items-start" x-data="calendarioPagina()"
            x-init="init()">

            {{-- Calendario --}}
            <div class="bg-white border border-line rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-line flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <button @click="calendar.prev()"
                            class="w-8 h-8 rounded-lg border border-line hover:bg-paper flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button @click="calendar.next()"
                            class="w-8 h-8 rounded-lg border border-line hover:bg-paper flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <h2 id="cal-title" class="font-display font-semibold text-lg capitalize"></h2>
                        <button @click="calendar.today()"
                            class="text-xs px-3 py-1.5 rounded-lg border border-line hover:bg-paper transition-colors font-medium">Hoy</button>
                    </div>
                    <div class="flex items-center gap-1 bg-paper border border-line rounded-lg p-1">
                        <button @click="cambiarVista('dayGridMonth')"
                            :class="vista==='dayGridMonth' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">Mes</button>
                        <button @click="cambiarVista('timeGridWeek')"
                            :class="vista==='timeGridWeek' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">Semana</button>
                        <button @click="cambiarVista('listMonth')"
                            :class="vista==='listMonth' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">Lista</button>
                    </div>
                </div>
                <div class="p-3">
                    <div id="calendar"></div>
                </div>
            </div>

            {{-- Leyenda / filtros: organizaciones reales de la BD --}}
            <div class="space-y-4">
                <div class="bg-white border border-line rounded-xl p-5">
                    <h3 class="font-display font-semibold text-sm mb-3.5">Organizaciones</h3>
                    <div class="space-y-2.5">
                        @foreach($organizaciones as $org)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" checked data-org="{{ $org->id }}" class="filtro-org w-3.5 h-3.5 rounded"
                                    style="accent-color: {{ $org->color }}">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $org->color }}"></span>
                                <span class="text-sm text-ink/80 group-hover:text-ink">{{ $org->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-line rounded-xl p-5">
                    <h3 class="font-display font-semibold text-sm mb-3.5">Estado</h3>
                    <div class="space-y-2.5 text-sm text-ink/75">
                        <div class="flex items-center gap-2.5"><span
                                class="w-6 h-3 rounded bg-ink/40 shrink-0"></span>Aprobada <span class="text-ink/35">(color
                                de la organización)</span></div>
                        <div class="flex items-center gap-2.5"><span class="w-6 h-3 rounded shrink-0"
                                style="background:repeating-linear-gradient(135deg,#26282B66,#26282B66 3px,#26282B26 3px,#26282B26 6px)"></span>Pendiente
                        </div>
                        <div class="flex items-center gap-2.5"><span
                                class="w-6 h-3 rounded bg-primary shrink-0"></span>Fecha especial</div>
                    </div>
                </div>

                @if(auth()->user()->rolEnum() !== \App\Enums\RolUsuario::Administrador)
                    <a href="{{ route('actividades.create') }}"
                        class="block text-center bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">
                        + Proponer actividad
                    </a>
                @endif
            </div>

            {{-- Modal de resumen — se abre al hacer clic en una actividad del calendario --}}
            <div x-show="modalResumen || cargandoResumen" x-cloak
                class="fixed inset-0 bg-black/40 flex items-center justify-center p-5 z-50">
                <div class="bg-white rounded-xl max-w-md w-full p-6 max-h-[85vh] overflow-y-auto"
                    @click.outside="modalResumen = null">
                    <template x-if="cargandoResumen">
                        <p class="text-sm text-ink/40 text-center py-6">Cargando...</p>
                    </template>

                    <template x-if="modalResumen && !modalRechazar">
                        <div>
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full"
                                            :style="'background:' + modalResumen.color"></span>
                                        <span class="text-xs font-medium text-ink/50"
                                            x-text="modalResumen.organizacion"></span>
                                    </div>
                                    <h3 class="font-display font-semibold text-lg" x-text="modalResumen.nombre"></h3>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0"
                                    :style="'background:' + modalResumen.estadoColor + '1a; color:' + modalResumen.estadoColor"
                                    x-text="modalResumen.estado"></span>
                            </div>

                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex justify-between"><span class="text-ink/45">Fecha</span><span
                                        class="font-mono" x-text="modalResumen.fecha"></span></div>
                                <div class="flex justify-between"><span class="text-ink/45">Hora</span><span
                                        class="font-mono"
                                        x-text="modalResumen.horaInicio + ' – ' + modalResumen.horaFin"></span></div>
                                <div class="flex justify-between"><span class="text-ink/45">Lugar</span><span
                                        x-text="modalResumen.lugar"></span></div>
                                <div class="flex justify-between" x-show="modalResumen.solicitaPresupuesto">
                                    <span class="text-ink/45">Presupuesto</span>
                                    <span class="font-mono font-medium text-accent"
                                        x-text="'$' + (modalResumen.presupuestoTotal ?? 0).toFixed(2)"></span>
                                </div>
                            </div>

                            <div class="bg-paper rounded-lg p-3.5 mb-5">
                                <p class="text-[11px] text-ink/40 mb-1">Objetivo</p>
                                <p class="text-sm text-ink/70" x-text="modalResumen.objetivo"></p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-stretch gap-3">
                                <a :href="modalResumen.urlDetalle"
                                    class="text-center border border-line text-sm font-medium py-2.5 px-4 rounded-lg hover:bg-paper transition-colors">Ver
                                    detalle</a>
                                <template x-if="modalResumen.puedeAprobar && modalResumen.estado === 'Pendiente'">
                                    <div class="flex gap-2 flex-1">
                                        <button @click="modalRechazar = true"
                                            class="flex-1 border border-brick/30 text-brick text-sm font-medium py-2.5 rounded-lg hover:bg-brick/5 transition-colors">Rechazar</button>
                                        <form method="POST" :action="modalResumen.urlAprobar" class="flex-1">
                                            @csrf
                                            <button
                                                class="w-full bg-sage text-white text-sm font-medium py-2.5 rounded-lg hover:bg-sage/90 transition-colors">Aprobar</button>
                                        </form>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="modalResumen && modalRechazar">
                        <form method="POST" :action="modalResumen.urlRechazar">
                            @csrf
                            <h3 class="font-display font-semibold text-lg mb-1.5">Rechazar actividad</h3>
                            <p class="text-sm text-ink/60 mb-3">Indica el motivo — quedará visible para la presidencia.</p>
                            <textarea name="motivo" class="w-full border border-line rounded-lg p-3 text-sm mb-5" rows="3"
                                required></textarea>
                            <div class="flex gap-3">
                                <button type="button" @click="modalRechazar = false"
                                    class="flex-1 border border-line text-sm font-medium py-2.5 rounded-lg hover:bg-paper transition-colors">Cancelar</button>
                                <button
                                    class="flex-1 bg-brick text-white text-sm font-medium py-2.5 rounded-lg hover:bg-brick/90 transition-colors">Confirmar
                                    rechazo</button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function calendarioPagina() {
            return {
                calendar: null,
                vista: 'dayGridMonth',
                modalResumen: null,
                modalRechazar: false,
                cargandoResumen: false,
                init() {
                    this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        locale: 'es',
                        initialView: 'dayGridMonth',
                        headerToolbar: false,
                        height: 'auto',
                        firstDay: 1,
                        events: {
                            url: '{{ route('calendario.eventos') }}',
                            method: 'GET',
                        },
                        dateClick: (info) => {
                            window.location.href = '{{ route('actividades.create') }}?fecha=' + info.dateStr;
                        },
                        eventClick: (info) => {
                            info.jsEvent.preventDefault();
                            if (info.event.extendedProps.tipo !== 'actividad') return; // fechas especiales no abren modal
                            this.abrirResumen(info.event.extendedProps.actividadId);
                        },
                        eventDidMount: (info) => {
                            info.el.style.setProperty('--org-color', info.event.backgroundColor || '#2B3A4A');
                        },
                        datesSet: (info) => {
                            document.getElementById('cal-title').textContent = info.view.title;
                        },
                    });
                    this.calendar.render();

                    document.querySelectorAll('.filtro-org').forEach((checkbox) => {
                        checkbox.addEventListener('change', () => {
                            const orgId = parseInt(checkbox.dataset.org, 10);
                            const visible = checkbox.checked;
                            this.calendar.getEvents().forEach((event) => {
                                if (event.extendedProps.orgKey === orgId) {
                                    event.setProp('display', visible ? 'auto' : 'none');
                                }
                            });
                        });
                    });
                },
                abrirResumen(actividadId) {
                    this.modalResumen = null;
                    this.modalRechazar = false;
                    this.cargandoResumen = true;
                    fetch('/actividades/' + actividadId + '/resumen')
                        .then((r) => r.json())
                        .then((data) => { this.modalResumen = data; })
                        .finally(() => { this.cargandoResumen = false; });
                },
                cambiarVista(vista) {
                    this.vista = vista;
                    this.calendar.changeView(vista);
                },
            };
        }
    </script>
@endpush
