<p x-show="sidebarOpen" class="px-3 mb-2 text-[11px] uppercase tracking-wider text-white/40 font-medium">General</p>

<a href="{{ route('dashboard') }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M4 12l8-8 8 8M6 10v9a1 1 0 001 1h4v-6h2v6h4a1 1 0 001-1v-9" stroke-linecap="round"
            stroke-linejoin="round" />
    </svg>
    <span x-show="sidebarOpen" x-transition>Dashboard</span>
</a>

<a href="{{ route('calendario.index') }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('calendario.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3.5" y="5" width="17" height="15" rx="2" />
        <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
    </svg>
    <span x-show="sidebarOpen" x-transition>Calendario</span>
</a>

<a href="{{ route('actividades.index') }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('actividades.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M8 3.5h8a1 1 0 011 1V5h1a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1h1v-.5a1 1 0 011-1z"
            stroke-linejoin="round" />
        <path d="M8.5 11h7M8.5 14.5h7M8.5 18h4" stroke-linecap="round" />
    </svg>
    <span x-show="sidebarOpen"
        x-transition>{{ auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia ? 'Mis Actividades' : 'Actividades' }}</span>
</a>

<a href="{{ route('presupuesto.index') }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('presupuesto.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="8.5" />
        <path d="M12 7.5v9M9 9.8c0-1 1-1.8 3-1.8s3 .8 3 1.8-1 1.5-3 1.7c-2 .2-3 .7-3 1.8s1 1.8 3 1.8 3-.8 3-1.8"
            stroke-linecap="round" />
    </svg>
    <span x-show="sidebarOpen" x-transition>Presupuesto</span>
</a>

@can('ver-reportes')
    <a href="{{ route('reportes.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('reportes.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M4 19V5a1 1 0 011-1h9l6 6v9a1 1 0 01-1 1H5a1 1 0 01-1-1z" stroke-linejoin="round" />
            <path d="M14 4v5a1 1 0 001 1h5M9 13.5h6M9 16.5h6" stroke-linecap="round" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Reportes</span>
    </a>
@endcan

@can('create', \App\Models\Trimestre::class)
    <a href="{{ route('trimestres.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('trimestres.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3.5" y="5" width="17" height="15" rx="2" />
            <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Trimestres</span>
    </a>
@endcan

@can('create', \App\Models\FechaEspecial::class)
    <a href="{{ route('fechas-especiales.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('fechas-especiales.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3.5" y="5" width="17" height="15" rx="2" stroke-dasharray="2 2" />
            <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Fechas Especiales</span>
    </a>
@endcan

@if(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Administrador)
    <p x-show="sidebarOpen" class="px-3 mt-5 mb-2 text-[11px] uppercase tracking-wider text-white/40 font-medium">
        Administración</p>
    <a href="{{ route('usuarios.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('usuarios.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <circle cx="9" cy="8.5" r="3" />
            <path d="M3.5 19c.7-3 2.7-4.5 5.5-4.5s4.8 1.5 5.5 4.5M16 8.2a2.6 2.6 0 110 5.2M18.5 19c-.4-2-1.4-3.4-3-4.1"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Usuarios</span>
    </a>
    <a href="{{ route('organizaciones.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('organizaciones.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3.5" y="6" width="7" height="7" rx="1" />
            <rect x="13.5" y="6" width="7" height="7" rx="1" />
            <rect x="8.5" y="14.5" width="7" height="4.5" rx="1" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Organizaciones</span>
    </a>
    <a href="{{ route('configuracion.edit') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('configuracion.*') ? 'bg-white/10 text-white font-medium' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="3" />
            <path
                d="M19.4 13.5a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V19a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H4a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3H10a1.6 1.6 0 001-1.5V4a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8V10a1.6 1.6 0 001.5 1H20a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span x-show="sidebarOpen" x-transition>Configuración</span>
    </a>
@endif
