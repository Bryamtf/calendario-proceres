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

    @include('components.toast')

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
                @include('partials.sidebar-nav')
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

        {{-- ===== SIDEBAR (móvil, drawer) ===== --}}
        <div x-show="mobileOpen" x-transition.opacity class="md:hidden fixed inset-0 bg-black/40 z-40"
            @click="mobileOpen = false"></div>
        <aside x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="md:hidden fixed inset-y-0 left-0 w-72 bg-primary text-white z-50 flex flex-col">
            <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded bg-accent/90 flex items-center justify-center font-display font-semibold text-primary shrink-0">
                        {{ mb_substr(\App\Models\ConfiguracionSistema::obtener()->nombre_barrio, 0, 1) }}
                    </div>
                    <p class="font-display font-semibold text-[15px]">
                        {{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</p>
                </div>
                <button @click="mobileOpen = false" class="text-white/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5" @click="mobileOpen = false">
                @include('partials.sidebar-nav')
            </nav>
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
                    @php $noLeidas = auth()->user()->unreadNotifications; @endphp
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen"
                            class="relative text-ink/50 hover:text-ink transition-colors p-1.5">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path
                                    d="M12 3.5a5.5 5.5 0 00-5.5 5.5v3.2c0 .5-.2 1-.5 1.4L4.7 15a1 1 0 00.7 1.7h13.2a1 1 0 00.7-1.7l-1.3-1.4c-.3-.4-.5-.9-.5-1.4V9a5.5 5.5 0 00-5.5-5.5zM9.5 19a2.5 2.5 0 005 0"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            @if($noLeidas->count())
                                <span
                                    class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-brick rounded-full text-[9px] text-white flex items-center justify-center">{{ $noLeidas->count() > 9 ? '9+' : $noLeidas->count() }}</span>
                            @endif
                        </button>
                        <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false" x-transition
                            class="absolute right-0 mt-2 w-80 bg-white border border-line rounded-xl shadow-lg z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-line flex items-center justify-between">
                                <p class="text-sm font-medium">Notificaciones</p>
                                @if($noLeidas->count())
                                    <form method="POST" action="{{ route('notificaciones.marcarTodas') }}">
                                        @csrf
                                        <button
                                            class="text-[11px] text-primary hover:text-primary-light transition-colors">Marcar
                                            todas leídas</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto divide-y divide-line">
                                @forelse(auth()->user()->notifications()->take(6)->get() as $notificacion)
                                    <form method="POST" action="{{ route('notificaciones.leer', $notificacion->id) }}">
                                        @csrf
                                        <button
                                            class="w-full text-left px-4 py-3 hover:bg-paper/60 transition-colors {{ $notificacion->read_at ? '' : 'bg-accent/5' }}">
                                            <p class="text-xs text-ink/70">
                                                {{ $notificacion->data['mensaje'] ?? 'Notificación' }}</p>
                                            <p class="text-[10px] text-ink/35 mt-1 font-mono">
                                                {{ $notificacion->created_at->diffForHumans() }}</p>
                                        </button>
                                    </form>
                                @empty
                                    <p class="px-4 py-6 text-center text-xs text-ink/40">Sin notificaciones todavía.</p>
                                @endforelse
                            </div>
                            <a href="{{ route('notificaciones.index') }}"
                                class="block text-center text-xs text-primary hover:text-primary-light py-2.5 border-t border-line transition-colors">Ver
                                todas</a>
                        </div>
                    </div>

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
