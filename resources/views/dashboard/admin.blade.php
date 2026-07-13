@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('contenido')
    <div class="p-5 md:p-8 max-w-6xl space-y-6">
        <div>
            <p class="text-sm text-ink/50">Administrador</p>
            <h2 class="font-display font-semibold text-2xl">Panorama del sistema</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Usuarios activos</p>
                <p class="font-display font-semibold text-3xl">{{ $usuariosActivos }}</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Organizaciones</p>
                <p class="font-display font-semibold text-3xl">{{ $organizacionesCount }}</p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Trimestre activo</p>
                @if($trimestre)
                    <p class="font-display font-semibold text-xl">{{ $trimestre->nombre }}</p>
                    <p class="text-xs text-ink/40 font-mono mt-0.5">
                        {{ max(0, $trimestre->totalDias() - $trimestre->diaActual()) }} días restantes</p>
                @else
                    <p class="font-display font-semibold text-xl text-ink/25">Ninguno</p>
                @endif
            </div>
            <div class="bg-white border border-line rounded-xl p-5">
                <p class="text-xs text-ink/50 mb-1">Actividades (histórico)</p>
                <p class="font-display font-semibold text-3xl">{{ $actividadesTotal }}</p>
            </div>
        </div>

        <div>
            <h3 class="font-display font-semibold mb-3">Accesos rápidos</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('usuarios.index') }}"
                    class="bg-white border border-line rounded-xl p-5 flex items-center gap-4 hover:border-primary/30 hover:shadow-sm transition-all">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg
                            class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="9" cy="8.5" r="3" />
                            <path
                                d="M3.5 19c.7-3 2.7-4.5 5.5-4.5s4.8 1.5 5.5 4.5M16 8.2a2.6 2.6 0 110 5.2M18.5 19c-.4-2-1.4-3.4-3-4.1"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-medium">Usuarios</p>
                        <p class="text-xs text-ink/45">Crear, editar y asignar roles</p>
                    </div>
                </a>
                <a href="{{ route('organizaciones.index') }}"
                    class="bg-white border border-line rounded-xl p-5 flex items-center gap-4 hover:border-primary/30 hover:shadow-sm transition-all">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg
                            class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3.5" y="6" width="7" height="7" rx="1" />
                            <rect x="13.5" y="6" width="7" height="7" rx="1" />
                            <rect x="8.5" y="14.5" width="7" height="4.5" rx="1" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-medium">Organizaciones</p>
                        <p class="text-xs text-ink/45">Catálogo configurable, colores</p>
                    </div>
                </a>
                <a href="{{ route('trimestres.index') }}"
                    class="bg-white border border-line rounded-xl p-5 flex items-center gap-4 hover:border-primary/30 hover:shadow-sm transition-all">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg
                            class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3.5" y="5" width="17" height="15" rx="2" />
                            <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-medium">Trimestres</p>
                        <p class="text-xs text-ink/45">Abrir, cerrar, días de gracia</p>
                    </div>
                </a>
                <a href="{{ route('configuracion.edit') }}"
                    class="bg-white border border-line rounded-xl p-5 flex items-center gap-4 hover:border-primary/30 hover:shadow-sm transition-all">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg
                            class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 13.5a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V19a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H4a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3H10a1.6 1.6 0 001-1.5V4a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8V10a1.6 1.6 0 001.5 1H20a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg></div>
                    <div>
                        <p class="text-sm font-medium">Configuración</p>
                        <p class="text-xs text-ink/45">Nombre del barrio, logo, catálogos</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
