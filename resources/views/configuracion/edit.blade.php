@extends('layouts.app')

@section('titulo', 'Configuración')

@section('contenido')
<div x-data="{ tab: 'general', catalogo: 'recursos' }" class="p-5 md:p-8">
    <div class="max-w-4xl grid grid-cols-1 md:grid-cols-[190px_1fr] gap-8">

        <div class="flex md:flex-col gap-1 overflow-x-auto md:overflow-visible">
            <button @click="tab='general'" :class="tab==='general' ? 'bg-white border-line text-ink shadow-sm' : 'border-transparent text-ink/50 hover:text-ink'" class="text-left px-3.5 py-2.5 rounded-lg text-sm font-medium border transition-colors shrink-0 whitespace-nowrap">General</button>
            <button @click="tab='catalogos'" :class="tab==='catalogos' ? 'bg-white border-line text-ink shadow-sm' : 'border-transparent text-ink/50 hover:text-ink'" class="text-left px-3.5 py-2.5 rounded-lg text-sm font-medium border transition-colors shrink-0 whitespace-nowrap">Catálogos</button>
            <button @click="tab='trimestre'" :class="tab==='trimestre' ? 'bg-white border-line text-ink shadow-sm' : 'border-transparent text-ink/50 hover:text-ink'" class="text-left px-3.5 py-2.5 rounded-lg text-sm font-medium border transition-colors shrink-0 whitespace-nowrap">Trimestre y cierre</button>
        </div>

        <div>
            @if ($errors->any())
                <div class="bg-brick/5 border border-brick/20 rounded-lg p-4 mb-5">
                    <ul class="text-sm text-brick/80 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ============ GENERAL ============ --}}
            <div x-show="tab==='general'" x-cloak class="space-y-6">
                <form method="POST" action="{{ route('configuracion.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white border border-line rounded-xl p-6 space-y-5">
                        <h2 class="font-display font-semibold text-lg mb-1">Identidad del barrio</h2>

                        <div class="flex items-center gap-4">
                            @if($configuracion->logo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($configuracion->logo_path) }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center font-display font-semibold text-primary text-xl shrink-0">
                                    {{ mb_substr($configuracion->nombre_barrio, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <input type="file" name="logo" accept="image/*" class="text-xs">
                                <p class="text-[11px] text-ink/40 mt-1.5">PNG o JPG, máx. 2MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Nombre del barrio</label>
                            <input type="text" name="nombre_barrio" class="form-input" value="{{ old('nombre_barrio', $configuracion->nombre_barrio) }}" required>
                        </div>
                    </div>

                    <input type="hidden" name="dias_gracia_cierre_trimestre" value="{{ $configuracion->dias_gracia_cierre_trimestre }}">

                    <div class="flex justify-end mt-4">
                        <button class="bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary-light transition-colors">Guardar cambios</button>
                    </div>
                </form>
            </div>

            {{-- ============ CATÁLOGOS ============ --}}
            <div x-show="tab==='catalogos'" x-cloak class="space-y-5">
                <div class="flex items-center gap-1 bg-white border border-line rounded-lg p-1 w-fit">
                    <button @click="catalogo='recursos'" :class="catalogo==='recursos' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'" class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-colors">Recursos</button>
                    <button @click="catalogo='fechas'" :class="catalogo==='fechas' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'" class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-colors">Tipos de fecha especial</button>
                    <button @click="catalogo='categorias'" :class="catalogo==='categorias' ? 'bg-primary text-white' : 'text-ink/50 hover:text-ink'" class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-colors">Categorías de presupuesto</button>
                </div>

                @foreach(['recursos' => $recursos, 'fechas' => $tiposFecha, 'categorias' => $categorias] as $tipo => $items)
                    <div x-show="catalogo==='{{ $tipo }}'" x-cloak class="bg-white border border-line rounded-xl overflow-hidden">
                        <div class="divide-y divide-line">
                            @forelse($items as $item)
                                <div class="px-5 py-3 flex items-center justify-between gap-3">
                                    <span class="text-sm {{ isset($item->estado) && $item->estado !== 'activo' ? 'text-ink/35 line-through' : '' }}">{{ $item->nombre }}</span>
                                    @if($tipo !== 'fechas')
                                        <form method="POST" action="{{ route('configuracion.catalogos.toggle', [$tipo, $item->id]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-[11px] font-medium px-2.5 py-1 rounded-full transition-colors {{ $item->estado === 'activo' ? 'bg-sage/10 text-sage' : 'bg-ink/5 text-ink/40' }}">
                                                {{ $item->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="px-5 py-6 text-center text-sm text-ink/40">Sin elementos todavía.</p>
                            @endforelse
                        </div>
                        <form method="POST" action="{{ route('configuracion.catalogos.store', $tipo) }}" class="p-3 bg-paper flex gap-2">
                            @csrf
                            <input type="text" name="nombre" placeholder="Nuevo elemento..." class="form-input text-sm bg-white" required>
                            <button class="shrink-0 bg-primary text-white text-xs font-medium px-4 rounded-lg hover:bg-primary-light transition-colors">Agregar</button>
                        </form>
                    </div>
                @endforeach

                <p class="text-xs text-ink/40 leading-relaxed">Estos catálogos alimentan los selectores del formulario de propuesta de actividad. Los elementos no se eliminan (para no romper actividades ya registradas que los usan) — solo se desactivan.</p>
            </div>

            {{-- ============ TRIMESTRE Y CIERRE ============ --}}
            <div x-show="tab==='trimestre'" x-cloak class="space-y-6">
                <form method="POST" action="{{ route('configuracion.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="nombre_barrio" value="{{ $configuracion->nombre_barrio }}">

                    <div class="bg-white border border-line rounded-xl p-6">
                        <h2 class="font-display font-semibold text-lg mb-1">Cierre de trimestre</h2>
                        <p class="text-sm text-ink/50 mb-5">El cierre puede ser manual (botón en Trimestres) o automático, lo que ocurra primero.</p>

                        <label class="form-label">Días de gracia antes del cierre automático</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="dias_gracia_cierre_trimestre" min="0" max="30" class="form-input font-mono w-24" value="{{ old('dias_gracia_cierre_trimestre', $configuracion->dias_gracia_cierre_trimestre) }}">
                            <span class="text-sm text-ink/50">días después de la fecha fin</span>
                        </div>
                        <p class="form-hint">Si nadie cierra el trimestre manualmente, el sistema lo hace solo pasado este plazo.</p>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button class="bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary-light transition-colors">Guardar cambios</button>
                    </div>
                </form>

                <div class="bg-accent/5 border border-accent/20 rounded-xl p-5">
                    <p class="text-xs font-medium text-ink mb-1.5">Gestión de trimestres</p>
                    <p class="text-sm text-ink/60 leading-relaxed mb-3">Abrir un nuevo trimestre, ver el histórico o forzar un cierre manual se hace desde el módulo de Trimestres, no aquí.</p>
                    <a href="{{ route('trimestres.index') }}" class="text-xs font-medium text-primary hover:text-primary-light transition-colors">Ir a Trimestres →</a>
                </div>
            </div>
        </div>
    </div>
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
