@if ($errors->any())
    <div class="bg-brick/5 border border-brick/20 rounded-lg p-4 mb-5">
        <ul class="text-sm text-brick/80 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
    <div>
        <label class="form-label">Tipo</label>
        <select name="tipo_fecha_especial_id" class="form-input" required>
            <option value="">Selecciona...</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}" @selected(old('tipo_fecha_especial_id', $fechaEspecial->tipo_fecha_especial_id ?? '') == $tipo->id)>{{ $tipo->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-input" placeholder="Ej. Conferencia General de octubre"
            value="{{ old('nombre', $fechaEspecial->nombre ?? '') }}" required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-input"
                value="{{ old('fecha_inicio', isset($fechaEspecial) ? $fechaEspecial->fecha_inicio->format('Y-m-d') : '') }}"
                required>
        </div>
        <div>
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-input"
                value="{{ old('fecha_fin', isset($fechaEspecial) ? $fechaEspecial->fecha_fin->format('Y-m-d') : '') }}"
                required>
            <p class="form-hint">Igual a la de inicio si es de un solo día.</p>
        </div>
    </div>

    <div>
        <label class="form-label">Descripción <span class="text-ink/35 font-normal">(opcional)</span></label>
        <textarea name="descripcion" class="form-input"
            rows="2">{{ old('descripcion', $fechaEspecial->descripcion ?? '') }}</textarea>
    </div>
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
