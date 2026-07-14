<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Barrio') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .form-input {
            width: 100%;
            border: 1px solid #E4DFD3;
            border-radius: 0.5rem;
            padding: 0.65rem 0.75rem;
            font-size: 0.875rem;
            background: #fff;
            transition: border-color .15s;
        }

        .form-input:focus {
            border-color: #C08A3E;
            outline: 2px solid #C08A3E33;
            outline-offset: 1px;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #26282B;
            margin-bottom: 0.4rem;
        }
    </style>
</head>

<body class="font-sans bg-paper text-ink antialiased">

    <div class="relative min-h-screen flex items-center justify-center p-5 overflow-hidden">

        {{-- Fondo decorativo: dos manchas suaves de color --}}
        <div class="absolute w-72 h-72 rounded-full blur-[60px] opacity-35 bg-primary/20 -top-10 -left-16"></div>
        <div class="absolute w-80 h-80 rounded-full blur-[60px] opacity-35 bg-accent/25 -bottom-16 -right-10"></div>

        <div class="relative w-full max-w-sm">

            {{-- Marca --}}
            <div class="text-center mb-7">
                <div
                    class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-primary flex items-center justify-center font-display font-semibold text-2xl text-accent shadow-sm">
                    {{ mb_substr(\App\Models\ConfiguracionSistema::obtener()->nombre_barrio, 0, 1) }}
                </div>
                <h1 class="font-display font-semibold text-xl text-ink">
                    {{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</h1>
                <p class="text-[11px] font-mono uppercase tracking-widest text-ink/40 mt-1">Planificación Trimestral</p>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-line rounded-2xl shadow-sm p-7">
                {{ $slot }}
            </div>

            <p class="text-center text-[11px] text-ink/35 mt-6 leading-relaxed px-4">
                Sistema interno del barrio — no reemplaza las herramientas oficiales de la Iglesia.
            </p>
        </div>
    </div>

</body>

</html>
