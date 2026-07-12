@extends('layouts.app')

@section('titulo', 'Proponer actividad')

@section('contenido')
<div x-data="actividadForm()" x-init="init()" class="max-w-3xl mx-auto p-5 md:p-10">

    <a href="{{ route('actividades.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink transition-colors mb-6">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver
    </a>

    <div class="flex items-center gap-2 mb-8">
        <template x-for="(label, i) in pasos" :key="i">
            <div class="flex items-center gap-2 flex-1">
                <div class="h-2 rounded-full transition-all" :class="paso === i ? 'w-[22px] bg-accent' : (paso > i ? 'w-2 bg-sage' : 'w-2 bg-line')"></div>
                <span class="text-[11px] font-medium hidden sm:block" :class="paso === i ? 'text-ink' : 'text-ink/35'" x-text="label"></span>
                <div class="flex-1 h-px bg-line" x-show="i < pasos.length - 1"></div>
            </div>
        </template>
    </div>

    @if ($errors->any())
        <div class="bg-brick/5 border border-brick/20 rounded-lg p-4 mb-6">
            <p class="text-sm font-medium text-brick mb-1">Revisa lo siguiente:</p>
            <ul class="text-sm text-brick/80 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form x-ref="form" method="POST" action="{{ route('actividades.store') }}" class="space-y-6">
        @csrf

        {{-- ===== PASO 1: Datos generales ===== --}}
        <div x-show="paso === 0" x-cloak class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            <div>
                <h2 class="font-display font-semibold text-lg mb-1">Datos generales</h2>
                <p class="text-sm text-ink/50">Lo esencial: qué, cuándo y dónde.</p>
            </div>

            <div>
                <label class="form-label">Nombre de la actividad</label>
                <input type="text" name="nombre" class="form-input" placeholder="Ej. Noche de talentos" value="{{ old('nombre') }}" required>
            </div>

            @if($organizaciones)
                <div>
                    <label class="form-label">Organización</label>
                    <select name="organizacion_id" class="form-input" required>
                        <option value="">Selecciona...</option>
                        @foreach($organizaciones as $org)
                            <option value="{{ $org->id }}" @selected(old('organizacion_id') == $org->id)>{{ $org->nombre }}</option>
                        @endforeach
                    </select>
                    <p class="form-hint">No tienes una organización fija asignada — indica para cuál es esta actividad.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-input" x-model="fecha" required>
                </div>
                <div>
                    <label class="form-label">Hora inicio</label>
                    <input type="time" name="hora_inicio" class="form-input" value="{{ old('hora_inicio') }}" required>
                </div>
                <div>
                    <label class="form-label">Hora fin</label>
                    <input type="time" name="hora_fin" class="form-input" value="{{ old('hora_fin') }}" required>
                </div>
            </div>

            <div>
                <label class="form-label">Lugar</label>
                <input type="text" name="lugar" class="form-input" placeholder="Ej. Salón cultural" value="{{ old('lugar') }}" required>
            </div>

            <div>
                <label class="form-label">Objetivo</label>
                <textarea name="objetivo" class="form-input" rows="2" placeholder="¿Qué se busca lograr con esta actividad?" required>{{ old('objetivo') }}</textarea>
            </div>

            <div>
                <label class="form-label">Descripción <span class="text-ink/35 font-normal">(opcional)</span></label>
                <textarea name="descripcion" class="form-input" rows="3">{{ old('descripcion') }}</textarea>
            </div>
        </div>

        {{-- ===== PASO 2: Participación ===== --}}
        <div x-show="paso === 1" x-cloak class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            <div>
                <h2 class="font-display font-semibold text-lg mb-1">Participación esperada</h2>
                <p class="text-sm text-ink/50">Ayuda al Consejo a dimensionar la actividad.</p>
            </div>

            <div>
                <label class="form-label">Asistencia esperada</label>
                <input type="number" name="asistencia_esperada" min="0" class="form-input font-mono w-full sm:w-40" value="{{ old('asistencia_esperada') }}">
                <p class="form-hint">Estimado general — no requiere nombres.</p>
            </div>

            <div class="border-t border-line pt-5 space-y-5">
                <template x-for="grupo in gruposParticipacion" :key="grupo.tipo">
                    <div>
                        <label class="form-label" x-text="grupo.label"></label>
                        <input type="number" min="0" class="form-input font-mono w-full sm:w-40" :name="grupo.campoConteo">

                        <button type="button" @click="grupo.abierto = !grupo.abierto" class="mt-2 text-xs font-medium text-accent hover:text-accent/80 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5 transition-transform" :class="grupo.abierto && 'rotate-90'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round"/></svg>
                            <span x-text="grupo.abierto ? 'Ocultar nombres' : (grupo.nombres.length ? grupo.nombres.length + ' nombre(s) agregado(s)' : '+ Agregar nombres (opcional)')"></span>
                        </button>

                        <div x-show="grupo.abierto" x-cloak class="mt-3 p-3.5 bg-paper border border-line rounded-lg">
                            <div class="flex flex-wrap gap-2 mb-2.5" x-show="grupo.nombres.length">
                                <template x-for="(nombre, i) in grupo.nombres" :key="i">
                                    <span class="inline-flex items-center gap-1.5 text-xs bg-white border border-line pl-2.5 pr-1.5 py-1 rounded-full">
                                        <span x-text="nombre"></span>
                                        <input type="hidden" :name="'participantes[' + grupo.tipo + '_' + i + '][tipo]'" :value="grupo.tipo">
                                        <input type="hidden" :name="'participantes[' + grupo.tipo + '_' + i + '][nombre]'" :value="nombre">
                                        <button type="button" @click="grupo.nombres.splice(i, 1)" class="text-ink/30 hover:text-brick transition-colors">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" class="form-input text-sm bg-white" x-model="grupo.borrador" @keydown.enter.prevent="agregarParticipante(grupo)">
                                <button type="button" @click="agregarParticipante(grupo)" class="shrink-0 bg-primary text-white text-xs font-medium px-3.5 rounded-lg hover:bg-primary-light transition-colors">Agregar</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ===== PASO 3: Presupuesto ===== --}}
        <div x-show="paso === 2" x-cloak class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            <div>
                <h2 class="font-display font-semibold text-lg mb-1">Presupuesto</h2>
                <p class="text-sm text-ink/50">Si la actividad requiere fondos del barrio.</p>
            </div>

            <label class="flex items-center gap-3 p-3.5 border border-line rounded-lg cursor-pointer hover:bg-paper/60 transition-colors">
                <input type="checkbox" name="solicita_presupuesto" value="1" x-model="solicitaPresupuesto" class="w-4 h-4 rounded accent-accent">
                <span class="text-sm font-medium">Esta actividad solicita presupuesto</span>
            </label>

            <div x-show="solicitaPresupuesto" x-cloak class="space-y-5 pt-1">
                <div class="flex items-center gap-1 bg-paper border border-line rounded-lg p-1 w-fit">
                    <button type="button" @click="modoPresupuesto = 'aproximado'" :class="modoPresupuesto === 'aproximado' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'" class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-colors">Monto aproximado</button>
                    <button type="button" @click="modoPresupuesto = 'desglose'" :class="modoPresupuesto === 'desglose' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'" class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-colors">Desglose por rubro</button>
                </div>

                {{-- MODO APROXIMADO: 1 sola línea, categoria_presupuesto_id vacío = NULL --}}
                <div x-show="modoPresupuesto === 'aproximado'" x-cloak class="space-y-4">
                    <input type="hidden" name="presupuesto_items[aprox][categoria_presupuesto_id]" value="" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'aproximado')">
                    <div>
                        <label class="form-label">Monto aproximado</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40 font-mono text-sm">$</span>
                            <input type="number" min="0" step="0.01" name="presupuesto_items[aprox][monto]" class="form-input font-mono pl-6" placeholder="0.00" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'aproximado')">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Justificación</label>
                        <textarea name="presupuesto_items[aprox][justificacion]" class="form-input" rows="2" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'aproximado')"></textarea>
                    </div>
                </div>

                {{-- MODO DESGLOSE: N líneas dinámicas --}}
                <div x-show="modoPresupuesto === 'desglose'" x-cloak class="space-y-3">
                    <template x-for="(item, idx) in rubros" :key="idx">
                        <div class="flex flex-col sm:flex-row gap-2.5 items-start sm:items-center p-3 border border-line rounded-lg bg-paper/40">
                            <select :name="'presupuesto_items[' + idx + '][categoria_presupuesto_id]'" class="form-input sm:w-40 shrink-0" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'desglose')">
                                <option value="">Categoría...</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="relative sm:w-32 shrink-0 w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40 font-mono text-sm">$</span>
                                <input type="number" min="0" step="0.01" :name="'presupuesto_items[' + idx + '][monto]'" class="form-input font-mono pl-6" placeholder="0.00" x-model.number="item.monto" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'desglose')">
                            </div>
                            <input type="text" :name="'presupuesto_items[' + idx + '][justificacion]'" class="form-input flex-1" placeholder="Nota (opcional)" :disabled="!(solicitaPresupuesto && modoPresupuesto === 'desglose')">
                            <button type="button" @click="rubros.splice(idx, 1)" class="text-ink/30 hover:text-brick transition-colors p-1.5 shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="rubros.push({ monto: 0 })" class="text-sm font-medium text-accent hover:text-accent/80 flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                        Agregar rubro
                    </button>

                    <div class="flex items-center justify-between pt-2 border-t border-line">
                        <span class="text-sm font-medium text-ink/60">Total solicitado</span>
                        <span class="font-mono text-lg font-semibold" x-text="'$' + totalRubros().toFixed(2)"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== PASO 4: Recursos ===== --}}
        <div x-show="paso === 3" x-cloak class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            <div>
                <h2 class="font-display font-semibold text-lg mb-1">Recursos necesarios</h2>
                <p class="text-sm text-ink/50">Marca lo que necesitas para el día del evento.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($recursos as $recurso)
                    <label class="flex items-center gap-2.5 p-3 border border-line rounded-lg cursor-pointer hover:bg-paper/60 transition-colors">
                        <input type="checkbox" name="recursos[]" value="{{ $recurso->id }}" class="w-4 h-4 rounded accent-accent" @if($recurso->nombre === 'Otro') x-model="otroSeleccionado" @endif @checked(in_array($recurso->id, old('recursos', [])))>
                        <span class="text-sm">{{ $recurso->nombre }}</span>
                    </label>
                @endforeach
            </div>

            @php $recursoOtro = $recursos->firstWhere('nombre', 'Otro'); @endphp
            @if($recursoOtro)
                <div x-show="otroSeleccionado" x-cloak>
                    <label class="form-label">Especifica "Otro"</label>
                    <input type="text" name="recursos_detalle[{{ $recursoOtro->id }}]" class="form-input" placeholder="¿Qué recurso adicional necesitas?" value="{{ old('recursos_detalle.'.$recursoOtro->id) }}">
                </div>
            @endif
        </div>

        {{-- ===== PASO 5: Resumen ===== --}}
        <div x-show="paso === 4" x-cloak class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            <div>
                <h2 class="font-display font-semibold text-lg mb-1">Revisa antes de enviar</h2>
                <p class="text-sm text-ink/50">Esto es lo que verá el Consejo de Obispado.</p>
            </div>

            <div class="divide-y divide-line border border-line rounded-lg overflow-hidden">
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Actividad</span><span class="text-sm font-medium text-right" x-text="resumen.nombre || '—'"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Organización</span><span class="text-sm text-right" x-text="resumen.organizacion || '—'"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Fecha y hora</span><span class="text-sm font-mono text-right" x-text="resumen.fecha"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Lugar</span><span class="text-sm text-right" x-text="resumen.lugar || '—'"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Asistencia esperada</span><span class="text-sm font-mono text-right" x-text="resumen.asistencia || '0'"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Miembros nuevos</span><span class="text-sm text-right" x-text="resumen.miembrosNuevos"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Amigos en enseñanza</span><span class="text-sm text-right" x-text="resumen.amigos"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Menos activos</span><span class="text-sm text-right" x-text="resumen.menosActivos"></span></div>
                <div class="px-4 py-3 flex justify-between gap-4">
                    <span class="text-xs text-ink/50 shrink-0">Presupuesto</span>
                    <span class="text-sm font-mono text-right" x-text="resumen.presupuesto"></span>
                </div>
                <div class="px-4 py-3 flex justify-between gap-4"><span class="text-xs text-ink/50 shrink-0">Recursos</span><span class="text-sm text-right" x-text="resumen.recursos"></span></div>
            </div>

            <div class="bg-sage/5 border border-sage/20 rounded-lg p-4 flex gap-3">
                <svg class="w-5 h-5 text-sage shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <p class="text-sm text-ink/70">Al enviar, tu propuesta queda en estado <span class="font-medium text-ink">Pendiente</span> hasta el próximo Consejo de Obispado.</p>
            </div>
        </div>

        {{-- Navegación --}}
        <div class="flex items-center justify-between pt-2">
            <button type="button" @click="paso > 0 && paso--" x-show="paso > 0" class="text-sm font-medium text-ink/60 hover:text-ink px-4 py-2.5 transition-colors">← Atrás</button>
            <div class="flex-1"></div>
            <button type="button" @click="avanzar()" x-show="paso < 4" class="bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary-light transition-colors">Continuar →</button>
            <button type="submit" x-show="paso === 4" class="bg-accent text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">Enviar propuesta</button>
        </div>
    </form>
</div>
@endsection

@push('head')
<style>
    .form-input { width: 100%; border: 1px solid #E4DFD3; border-radius: 0.5rem; padding: 0.6rem 0.75rem; font-size: 0.875rem; background: #fff; }
    .form-input:focus { border-color: #C08A3E; outline: none; }
    .form-label { display:block; font-size: 0.8rem; font-weight: 500; color:#26282B; margin-bottom: 0.35rem; }
    .form-hint { font-size: 0.75rem; color: #26282B73; margin-top: 0.3rem; }
</style>
@endpush

@push('scripts')
<script>
    function actividadForm() {
        return {
            paso: 0,
            pasos: ['General', 'Participación', 'Presupuesto', 'Recursos', 'Resumen'],
            fecha: @json(old('fecha', $fechaPrecargada)),
            solicitaPresupuesto: {{ old('solicita_presupuesto') ? 'true' : 'false' }},
            modoPresupuesto: 'aproximado',
            rubros: [{ monto: 0 }],
            otroSeleccionado: @json($recursoOtro ? in_array($recursoOtro->id, old('recursos', [])) : false),
            gruposParticipacion: [
                { tipo: 'miembro_nuevo', label: 'Miembros nuevos', campoConteo: 'miembros_nuevos', nombres: [], borrador: '', abierto: false },
                { tipo: 'amigo_ensenanza', label: 'Amigos en enseñanza', campoConteo: 'amigos_ensenanza', nombres: [], borrador: '', abierto: false },
                { tipo: 'menos_activo', label: 'Miembros menos activos', campoConteo: 'miembros_menos_activos', nombres: [], borrador: '', abierto: false },
            ],
            recursosMap: @json($recursos->pluck('nombre', 'id')),
            organizacionesMap: @json($organizaciones ? $organizaciones->pluck('nombre', 'id') : []),
            organizacionUsuario: @json(auth()->user()->organizacion?->nombre),
            resumen: {},
            init() {},
            agregarParticipante(grupo) {
                const nombre = grupo.borrador.trim();
                if (!nombre) return;
                grupo.nombres.push(nombre);
                grupo.borrador = '';
            },
            totalRubros() {
                return this.rubros.reduce((sum, r) => sum + (parseFloat(r.monto) || 0), 0);
            },
            avanzar() {
                if (this.paso === 3) {
                    this.construirResumen();
                }
                if (this.paso < 4) this.paso++;
            },
            construirResumen() {
                const fd = new FormData(this.$refs.form);
                const val = (name) => fd.get(name) || '';

                const orgId = val('organizacion_id');
                const org = orgId ? (this.organizacionesMap[orgId] || null) : this.organizacionUsuario;

                let presupuesto = 'No solicita';
                if (this.solicitaPresupuesto) {
                    if (this.modoPresupuesto === 'aproximado') {
                        const monto = parseFloat(val('presupuesto_items[aprox][monto]')) || 0;
                        presupuesto = '$' + monto.toFixed(2) + ' (aproximado)';
                    } else {
                        presupuesto = '$' + this.totalRubros().toFixed(2) + ' (' + this.rubros.length + ' rubro(s))';
                    }
                }

                const recursosSeleccionados = fd.getAll('recursos[]').map((id) => this.recursosMap[id] || id);

                this.resumen = {
                    nombre: val('nombre'),
                    organizacion: org,
                    fecha: (val('fecha') || '—') + ' · ' + (val('hora_inicio') || '--:--') + ' a ' + (val('hora_fin') || '--:--'),
                    lugar: val('lugar'),
                    asistencia: val('asistencia_esperada'),
                    miembrosNuevos: this.gruposParticipacion[0].nombres.length
                        ? val('miembros_nuevos') + ' (' + this.gruposParticipacion[0].nombres.join(', ') + ')'
                        : (val('miembros_nuevos') || '0'),
                    amigos: this.gruposParticipacion[1].nombres.length
                        ? val('amigos_ensenanza') + ' (' + this.gruposParticipacion[1].nombres.join(', ') + ')'
                        : (val('amigos_ensenanza') || '0'),
                    menosActivos: this.gruposParticipacion[2].nombres.length
                        ? val('miembros_menos_activos') + ' (' + this.gruposParticipacion[2].nombres.join(', ') + ')'
                        : (val('miembros_menos_activos') || '0'),
                    presupuesto: presupuesto,
                    recursos: recursosSeleccionados.length ? recursosSeleccionados.join(', ') : 'Ninguno',
                };
            },
        };
    }
</script>
@endpush
