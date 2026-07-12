<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Planificación Trimestral') —
        {{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-sans bg-paper text-ink antialiased">

    <div x-data="{ sidebarOpen: true, mobileOpen: false }" class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        <aside :class="sidebarOpen ? 'w-72' : 'w-[76px]'"
            class="hidden md:flex md:flex-col shrink-0 bg-primary text-white transition-all duration-300 ease-in-out">
            <div class="h-16 flex items-center gap-3 px-5 border-b border-white/10 shrink-0">
                <div
                    class="w-8 h-8 rounded bg-accent/90 flex items-center justify-center font-display font-semibold text-primary shrink-0">
                    {{ mb_substr(\App\Models\ConfiguracionSistema::obtener()->nombre_barrio, 0, 1) }}
                </div>
                <div x-show="sidebarOpen" x-transition class="overflow-hidden whitespace-nowrap">
                    <p class="font-display font-semibold text-[15px] leading-tight">
                        {{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</p>
                    <p class="text-[11px] text-white/50 tracking-wide">Planificación Trimestral</p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
                <p x-show="sidebarOpen"
                    class="px-3 mb-2 text-[11px] uppercase tracking-wider text-white/40 font-medium">General</p>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M4 12l8-8 8 8M6 10v9a1 1 0 001 1h4v-6h2v6h4a1 1 0 001-1v-9" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>

                <a href="{{ route('calendario.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('calendario.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <rect x="3.5" y="5" width="17" height="15" rx="2" />
                        <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Calendario</span>
                </a>

                @unless(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Administrador)
                    <a href="{{ route('actividades.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('actividades.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path
                                d="M8 3.5h8a1 1 0 011 1V5h1a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1h1v-.5a1 1 0 011-1z"
                                stroke-linejoin="round" />
                            <path d="M8.5 11h7M8.5 14.5h7M8.5 18h4" stroke-linecap="round" />
                        </svg>
                        <span x-show="sidebarOpen"
                            x-transition>{{ auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia ? 'Mis Actividades' : 'Actividades' }}</span>
                    </a>
                @endunless

                @unless(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Administrador)
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white text-sm transition-colors">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="8.5" />
                            <path
                                d="M12 7.5v9M9 9.8c0-1 1-1.8 3-1.8s3 .8 3 1.8-1 1.5-3 1.7c-2 .2-3 .7-3 1.8s1 1.8 3 1.8 3-.8 3-1.8"
                                stroke-linecap="round" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Presupuesto</span>
                    </a>
                @endunless

                @can('ver-reportes')
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white text-sm transition-colors">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M4 19V5a1 1 0 011-1h9l6 6v9a1 1 0 01-1 1H5a1 1 0 01-1-1z" stroke-linejoin="round" />
                            <path d="M14 4v5a1 1 0 001 1h5M9 13.5h6M9 16.5h6" stroke-linecap="round" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Reportes</span>
                    </a>
                @endcan

                @can('create', \App\Models\Trimestre::class)
                    <a href="{{ route('trimestres.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('trimestres.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3.5" y="5" width="17" height="15" rx="2" />
                            <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Trimestres</span>
                    </a>
                @endcan

                @if(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Administrador)
                    <p x-show="sidebarOpen"
                        class="px-3 mt-5 mb-2 text-[11px] uppercase tracking-wider text-white/40 font-medium">Administración
                    </p>
                    <a href="{{ route('usuarios.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('usuarios.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="9" cy="8.5" r="3" />
                            <path
                                d="M3.5 19c.7-3 2.7-4.5 5.5-4.5s4.8 1.5 5.5 4.5M16 8.2a2.6 2.6 0 110 5.2M18.5 19c-.4-2-1.4-3.4-3-4.1"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Usuarios</span>
                    </a>
                    <a href="{{ route('organizaciones.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('organizaciones.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3.5" y="6" width="7" height="7" rx="1" />
                            <rect x="13.5" y="6" width="7" height="7" rx="1" />
                            <rect x="8.5" y="14.5" width="7" height="4.5" rx="1" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Organizaciones</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white text-sm transition-colors">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 13.5a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V19a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H4a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3H10a1.6 1.6 0 001-1.5V4a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8V10a1.6 1.6 0 001.5 1H20a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Configuración</span>
                    </a>
                @endif
            </nav>

            @php $trimestreActivo = \App\Models\Trimestre::obtenerActivo(); @endphp
            @if($trimestreActivo)
                <div x-show="sidebarOpen" x-transition class="mx-3 mb-3">
                    <div class="relative bg-white/[0.07] rounded-xl p-4 border-t border-dashed border-white/20">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[11px] uppercase tracking-wider text-white/50 font-medium">Trimestre
                                activo</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-sage"></span>
                        </div>
                        <p class="font-display font-semibold text-lg leading-none mb-2.5">{{ $trimestreActivo->nombre }}</p>
                        @php $pct = min(100, round(($trimestreActivo->diaActual() / $trimestreActivo->totalDias()) * 100)); @endphp
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden mb-1.5">
                            <div class="h-full bg-accent rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="font-mono text-[10px] text-white/50">
                            {{ $trimestreActivo->fecha_inicio->format('d M') }} —
                            {{ $trimestreActivo->fecha_fin->format('d M') }}
                            · día {{ $trimestreActivo->diaActual() }} de {{ $trimestreActivo->totalDias() }}
                        </p>
                    </div>
                </div>
            @endif

            <button @click="sidebarOpen = !sidebarOpen"
                class="h-12 border-t border-white/10 flex items-center justify-center text-white/50 hover:text-white hover:bg-white/5 transition-colors shrink-0">
                <svg class="w-4 h-4 transition-transform" :class="!sidebarOpen && 'rotate-180'" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </aside>

        {{-- ===== CONTENIDO ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="h-16 shrink-0 flex items-center justify-between px-5 md:px-8 bg-white border-b border-line gap-4">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = true" class="md:hidden text-ink/60">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-xs text-ink/40 font-mono">
                            {{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }} / Planificación</p>
                        <h1 class="font-display font-semibold text-xl text-ink">@yield('titulo', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="h-8 w-px bg-line hidden sm:block"></div>
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-display font-semibold text-primary text-sm">
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium leading-tight text-ink">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-ink/45 leading-tight">{{ auth()->user()->role->nombre }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-xs text-ink/40 hover:text-brick transition-colors">Salir</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                @yield('contenido')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
