<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Próximas actividades — {{ $configuracion->nombre_barrio }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="font-sans bg-paper text-ink antialiased">

    <div class="max-w-lg mx-auto p-5 md:p-8">

        {{-- Marca --}}
        <div class="text-center mb-7 pt-4">
            <div
                class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-primary flex items-center justify-center font-display font-semibold text-2xl text-accent shadow-sm">
                {{ mb_substr($configuracion->nombre_barrio, 0, 1) }}
            </div>
            <h1 class="font-display font-semibold text-xl text-ink">{{ $configuracion->nombre_barrio }}</h1>
            <p class="text-[11px] font-mono uppercase tracking-widest text-ink/40 mt-1">Próximas actividades</p>
        </div>

        <div class="space-y-4">
            @forelse($actividades as $actividad)
                <div class="bg-white border border-line rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0"
                            style="background: {{ $actividad->organizacion->color }}"></span>
                        <span class="text-xs font-medium text-ink/50">{{ $actividad->organizacion->nombre }}</span>
                    </div>

                    <h2 class="font-display font-semibold text-lg mb-3 leading-snug">{{ $actividad->nombre }}</h2>

                    <div class="space-y-1.5 text-sm text-ink/70">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-ink/35 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <rect x="3.5" y="5" width="17" height="15" rx="2" />
                                <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
                            </svg>
                            {{ ucfirst($actividad->fecha->translatedFormat('l d \d\e F')) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-ink/35 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <circle cx="12" cy="12" r="8.5" />
                                <path d="M12 7.5v5l3.5 2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ \Carbon\Carbon::parse($actividad->hora_inicio)->format('g:i a') }} –
                            {{ \Carbon\Carbon::parse($actividad->hora_fin)->format('g:i a') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-ink/35 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M12 21s-7-6.5-7-11.5A7 7 0 0112 2a7 7 0 017 7.5C19 14.5 12 21 12 21z"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="12" cy="9.5" r="2.2" />
                            </svg>
                            {{ $actividad->lugar }}
                        </div>
                    </div>

                    <p class="text-xs text-ink/40 mt-4 pt-3 border-t border-line">
                        ¿Preguntas? Comunícate con la presidencia de {{ $actividad->organizacion->nombre }}.
                    </p>
                </div>
            @empty
                <div class="bg-white border border-line rounded-xl p-8 text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3.5" y="5" width="17" height="15" rx="2" />
                            <path d="M3.5 9.5h17M8 3v3M16 3v3" stroke-linecap="round" />
                        </svg>
                    </div>
                    <p class="text-sm text-ink/50">Aún no hay actividades programadas.<br>Vuelve pronto.</p>
                </div>
            @endforelse
        </div>

        <p class="text-center text-[11px] text-ink/30 mt-8 pb-4">
            Barrio {{ $configuracion->nombre_barrio }} — Planificación Trimestral
        </p>
    </div>

</body>

</html>
