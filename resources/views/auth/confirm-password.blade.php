<x-guest-layout>
    <h2 class="font-display font-semibold text-lg mb-1">Confirma tu contraseña</h2>
    <p class="text-sm text-ink/50 mb-6">Por seguridad, confirma tu contraseña antes de continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" autofocus placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button>Confirmar</x-primary-button>
    </form>
</x-guest-layout>
