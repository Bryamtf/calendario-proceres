<x-guest-layout>
    <h2 class="font-display font-semibold text-lg mb-1">¿Olvidaste tu contraseña?</h2>
    <p class="text-sm text-ink/50 mb-6">Escribe tu correo y te mandamos un link para restablecerla.</p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                placeholder="tucorreo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>Enviar link de restablecimiento</x-primary-button>

        <a href="{{ route('login') }}" class="block text-center text-xs text-ink/45 hover:text-ink transition-colors">←
            Volver a iniciar sesión</a>
    </form>
</x-guest-layout>
