<x-guest-layout>
    <h2 class="font-display font-semibold text-lg mb-1">Bienvenido de vuelta</h2>
    <p class="text-sm text-ink/50 mb-6">Ingresa tus credenciales para continuar.</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ verPassword: false }">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="tucorreo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-0.5">
                <x-input-label for="password" value="Contraseña" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-[12px] text-accent hover:text-accent/80 transition-colors"
                        href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>
            <div class="relative">
                <input :type="verPassword ? 'text' : 'password'" id="password" name="password" required
                    autocomplete="current-password" placeholder="••••••••" class="form-input pr-10">
                <button type="button" @click="verPassword = !verPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/35 hover:text-ink/60 transition-colors">
                    <svg x-show="!verPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="2.8" />
                    </svg>
                    <svg x-show="verPassword" x-cloak class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path
                            d="M3 3l18 18M10.6 10.6a2.8 2.8 0 003.9 3.9M9.5 5.6A10.4 10.4 0 0112 5.5c6 0 9.5 6.5 9.5 6.5a13.6 13.6 0 01-2.1 2.9M6.2 6.9C4 8.5 2.5 12 2.5 12a13.7 13.7 0 004.1 4.7"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded accent-accent">
            <span class="text-sm text-ink/70">Recordarme</span>
        </label>

        <x-primary-button>Iniciar sesión</x-primary-button>
    </form>
</x-guest-layout>
