@extends('layouts.app')

@section('titulo', 'Trimestres')

@section('contenido')
    <div class="max-w-3xl mx-auto p-5 md:p-8 space-y-5">

        @if(session('exito'))
            <div class="bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3">{{ session('exito') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-brick/5 border border-brick/20 text-brick text-sm rounded-lg px-4 py-3">{{ $errors->first() }}</div>
        @endif

        @can('create', \App\Models\Trimestre::class)
            <div class="flex justify-end">
                <a href="{{ route('trimestres.create') }}"
                    class="bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+
                    Abrir trimestre</a>
            </div>
        @endcan

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="divide-y divide-line">
                @foreach($trimestres as $trimestre)
                    <div class="px-5 py-4 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium">{{ $trimestre->nombre }}</p>
                            <p class="text-xs text-ink/45 font-mono">{{ $trimestre->fecha_inicio->format('d M Y') }} —
                                {{ $trimestre->fecha_fin->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span
                                class="text-[11px] font-medium px-2.5 py-1 rounded-full {{ $trimestre->estado === 'activo' ? 'bg-sage/10 text-sage' : 'bg-ink/5 text-ink/40' }}">
                                {{ ucfirst($trimestre->estado) }}
                            </span>
                            @if($trimestre->estado === 'activo')
                                @can('cerrar', $trimestre)
                                    <form method="POST" action="{{ route('trimestres.cerrar', $trimestre) }}"
                                        onsubmit="return confirm('¿Cerrar este trimestre? Las actividades Pendientes pasarán a No Procesada.');">
                                        @csrf
                                        <button
                                            class="text-xs font-medium text-brick hover:text-brick/80 transition-colors">Cerrar</button>
                                    </form>
                                @endcan
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
