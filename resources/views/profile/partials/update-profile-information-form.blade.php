<h2 class="font-display font-semibold text-lg mb-1">Información del perfil</h2>
<p class="text-sm text-ink/50 mb-6">Tu nombre completo, tal como aparece en el sistema.</p>

@if ($errors->updateProfileInformation->any())
    <div class="bg-brick/5 border border-brick/20 rounded-lg p-4 mb-5">
        <ul class="text-sm text-brick/80 list-disc list-inside">
            @foreach ($errors->updateProfileInformation->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <div>
        <label class="form-label">Nombre completo</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name) }}" required
            autofocus>
    </div>

    <div>
        <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-input" value="{{ auth()->user()->email }}" disabled>
        <p class="form-hint">Es tu usuario de acceso y a donde llegan las notificaciones. Si necesitas cambiarlo,
            pídeselo al Administrador.</p>
    </div>

    <div class="flex justify-end pt-2">
        <button
            class="bg-accent text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">Guardar</button>
    </div>
</form>
