@php
    $rolesMap = $roles->pluck('nombre', 'id');
@endphp

<div x-data="usuarioForm()" class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">

    @if ($errors->any())
        <div class="bg-brick/5 border border-brick/20 rounded-lg p-4">
            <ul class="text-sm text-brick/80 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label class="form-label">Nombre completo</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $usuario->name ?? '') }}" required>
    </div>

    <div>
        <label class="form-label">Correo</label>
        <input type="email" name="email" class="form-input" value="{{ old('email', $usuario->email ?? '') }}" required>
    </div>

    <div>
        <label class="form-label">
            Contraseña
            @isset($usuario) <span class="text-ink/35 font-normal">(déjalo vacío para no cambiarla)</span> @endisset
        </label>
        <input type="password" name="password" class="form-input" {{ isset($usuario) ? '' : 'required' }}>
    </div>

    <div>
        <label class="form-label">Rol</label>
        <select name="role_id" class="form-input" x-model="rolId" required>
            <option value="">Selecciona...</option>
            @foreach($roles as $rol)
                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div x-show="rolesMap[rolId] === 'Presidencia'" x-cloak>
        <label class="form-label">Organización</label>
        <select name="organizacion_id" class="form-input">
            <option value="">Selecciona...</option>
            @foreach($organizaciones as $org)
                <option value="{{ $org->id }}" @selected(old('organizacion_id', $usuario->organizacion_id ?? '') == $org->id)>
                    {{ $org->nombre }}</option>
            @endforeach
        </select>
        <p class="form-hint">Cada Presidencia solo administra las actividades de su organización.</p>
    </div>

    <label class="flex items-center gap-2.5">
        <input type="checkbox" name="activo" value="1" class="w-4 h-4 rounded accent-accent" {{ old('activo', $usuario->activo ?? true) ? 'checked' : '' }}>
        <span class="text-sm">Usuario activo</span>
    </label>
</div>

@push('head')
    <style>
        .form-input {
            width: 100%;
            border: 1px solid #E4DFD3;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.875rem;
            background: #fff;
        }

        .form-input:focus {
            border-color: #C08A3E;
            outline: none;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #26282B;
            margin-bottom: 0.35rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #26282B73;
            margin-top: 0.3rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function usuarioForm() {
            return {
                rolId: '{{ old('role_id', $usuario->role_id ?? '') }}',
                rolesMap: @json($rolesMap),
            };
        }
    </script>
@endpush
