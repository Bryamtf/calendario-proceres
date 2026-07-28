<h2 class="font-display font-semibold text-lg mb-1">Contraseña</h2>
<p class="text-sm text-ink/50 mb-6">Usa una contraseña larga y única que no uses en ningún otro sitio.</p>

@if ($errors->updatePassword->any())
    <div class="bg-brick/5 border border-brick/20 rounded-lg p-4 mb-5">
        <ul class="text-sm text-brick/80 list-disc list-inside">
            @foreach ($errors->updatePassword->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="space-y-4" x-data="{ verActual: false, verNueva: false, verConfirmar: false }">
    @csrf
    @method('put')

    <div>
        <label class="form-label">Contraseña actual</label>
        <div class="relative">
            <input :type="verActual ? 'text' : 'password'" name="current_password" class="form-input pr-10" autocomplete="current-password">
            <button type="button" @click="verActual = !verActual" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/35 hover:text-ink/60 transition-colors">
                <svg x-show="!verActual" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="2.8"/></svg>
                <svg x-show="verActual" x-cloak class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3l18 18M10.6 10.6a2.8 2.8 0 003.9 3.9M9.5 5.6A10.4 10.4 0 0112 5.5c6 0 9.5 6.5 9.5 6.5a13.6 13.6 0 01-2.1 2.9M6.2 6.9C4 8.5 2.5 12 2.5 12a13.7 13.7 0 004.1 4.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <div>
        <label class="form-label">Contraseña nueva</label>
        <div class="relative">
            <input :type="verNueva ? 'text' : 'password'" name="password" class="form-input pr-10" autocomplete="new-password">
            <button type="button" @click="verNueva = !verNueva" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/35 hover:text-ink/60 transition-colors">
                <svg x-show="!verNueva" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="2.8"/></svg>
                <svg x-show="verNueva" x-cloak class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3l18 18M10.6 10.6a2.8 2.8 0 003.9 3.9M9.5 5.6A10.4 10.4 0 0112 5.5c6 0 9.5 6.5 9.5 6.5a13.6 13.6 0 01-2.1 2.9M6.2 6.9C4 8.5 2.5 12 2.5 12a13.7 13.7 0 004.1 4.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <div>
        <label class="form-label">Confirmar contraseña nueva</label>
        <div class="relative">
            <input :type="verConfirmar ? 'text' : 'password'" name="password_confirmation" class="form-input pr-10" autocomplete="new-password">
            <button type="button" @click="verConfirmar = !verConfirmar" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink/35 hover:text-ink/60 transition-colors">
                <svg x-show="!verConfirmar" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="2.8"/></svg>
                <svg x-show="verConfirmar" x-cloak class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3l18 18M10.6 10.6a2.8 2.8 0 003.9 3.9M9.5 5.6A10.4 10.4 0 0112 5.5c6 0 9.5 6.5 9.5 6.5a13.6 13.6 0 01-2.1 2.9M6.2 6.9C4 8.5 2.5 12 2.5 12a13.7 13.7 0 004.1 4.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button class="bg-primary text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-primary-light transition-colors">Actualizar contraseña</button>
    </div>
</form>
