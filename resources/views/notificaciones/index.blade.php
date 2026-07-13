@extends('layouts.app')

@section('titulo', 'Notificaciones')

@section('contenido')
    <div class="max-w-2xl mx-auto p-5 md:p-8 space-y-5">

        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl">Notificaciones</h2>
            @if(auth()->user()->unreadNotifications->count())
                <form method="POST" action="{{ route('notificaciones.marcarTodas') }}">
                    @csrf
                    <button class="text-xs font-medium text-primary hover:text-primary-light transition-colors">Marcar todas
                        leídas</button>
                </form>
            @endif
        </div>

        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="divide-y divide-line">
                @forelse($notificaciones as $notificacion)
                    <form method="POST" action="{{ route('notificaciones.leer', $notificacion->id) }}">
                        @csrf
                        <button
                            class="w-full text-left px-5 py-4 hover:bg-paper/60 transition-colors {{ $notificacion->read_at ? '' : 'bg-accent/5' }}">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm text-ink/80">{{ $notificacion->data['mensaje'] ?? 'Notificación' }}</p>
                                @unless($notificacion->read_at)
                                    <span class="w-2 h-2 rounded-full bg-accent shrink-0 mt-1.5"></span>
                                @endunless
                            </div>
                            <p class="text-xs text-ink/40 mt-1 font-mono">{{ $notificacion->created_at->diffForHumans() }}</p>
                        </button>
                    </form>
                @empty
                    <p class="px-5 py-10 text-center text-sm text-ink/40">No tienes notificaciones todavía.</p>
                @endforelse
            </div>
        </div>

        {{ $notificaciones->links() }}
    </div>
@endsection
