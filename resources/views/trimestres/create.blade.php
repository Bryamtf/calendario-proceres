@extends('layouts.app')

@section('titulo', 'Abrir trimestre')

@section('contenido')
    <div class="max-w-lg mx-auto p-5 md:p-10">
        <form method="POST" action="{{ route('trimestres.store') }}"
            class="bg-white border border-line rounded-xl p-6 md:p-7 space-y-5">
            @csrf

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
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-input" placeholder="Ej. Q4 2026" value="{{ old('nombre') }}"
                    required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-input" value="{{ old('fecha_inicio') }}" required>
                </div>
                <div>
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-input" value="{{ old('fecha_fin') }}" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('trimestres.index') }}"
                    class="text-sm font-medium text-ink/60 hover:text-ink px-4 py-2.5 transition-colors">Cancelar</a>
                <button
                    class="bg-accent text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">Abrir
                    trimestre</button>
            </div>
        </form>
    </div>
@endsection

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
    </style>
@endpush
