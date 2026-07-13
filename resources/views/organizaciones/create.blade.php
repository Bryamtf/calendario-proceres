@extends('layouts.app')

@section('titulo', 'Nueva organización')

@section('contenido')
<div class="max-w-lg mx-auto p-5 md:p-10">
    <form method="POST" action="{{ route('organizaciones.store') }}" class="space-y-6">
        @csrf
        @include('organizaciones._form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('organizaciones.index') }}" class="text-sm font-medium text-ink/60 hover:text-ink px-4 py-2.5 transition-colors">Cancelar</a>
            <button class="bg-accent text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">Crear organización</button>
        </div>
    </form>
</div>
@endsection
