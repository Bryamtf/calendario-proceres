@extends('layouts.app')

@section('titulo', 'Organizaciones')

@section('contenido')
<div class="max-w-2xl mx-auto p-5 md:p-8 space-y-5">

    @if(session('exito'))
        <div class="bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3">{{ session('exito') }}</div>
    @endif

    <div class="flex justify-end">
        <a href="{{ route('organizaciones.create') }}" class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+ Nueva organización</a>
    </div>

    <div class="bg-white border border-line rounded-xl overflow-hidden">
        <div class="divide-y divide-line">
            @foreach($organizaciones as $organizacion)
                <div class="px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $organizacion->color }}"></span>
                        <p class="text-sm font-medium">{{ $organizacion->nombre }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-[11px] font-medium px-2.5 py-1 rounded-full {{ $organizacion->estado === 'activo' ? 'bg-sage/10 text-sage' : 'bg-ink/5 text-ink/40' }}">
                            {{ ucfirst($organizacion->estado) }}
                        </span>
                        <a href="{{ route('organizaciones.edit', $organizacion) }}" class="text-xs font-medium text-primary hover:text-primary-light transition-colors">Editar</a>
                        <form method="POST" action="{{ route('organizaciones.toggleActivo', $organizacion) }}">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs font-medium text-ink/40 hover:text-brick transition-colors">
                                {{ $organizacion->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
