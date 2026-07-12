@extends('layouts.app')

@section('titulo', auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia ? 'Mis Actividades' : 'Actividades')

@section('contenido')
<div class="p-5 md:p-8">
    <div class="max-w-6xl grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-6 items-start">

        {{-- Filtros --}}
        <form method="GET" action="{{ route('actividades.index') }}" class="bg-white border border-line rounded-xl p-5 space-y-4 lg:sticky lg:top-0">
            <h3 class="font-display font-semibold text-sm">Filtros</h3>

            @if($organizaciones)
                <div>
                    <label class="form-label">Organización</label>
                    <select name="organizacion_id" class="form-input" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach($organizaciones as $org)
                            <option value="{{ $org->id }}" @selected($filtros['organizacion_id'] == $org->id)>{{ $org->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="form-label">Estado</label>
                <div class="space-y-1.5">
                    @foreach(['Pendiente', 'Aprobada', 'Rechazada', 'Realizada', 'Cancelada', 'No Procesada'] as $estado)
                        <label class="flex items-center gap-2 text-sm text-ink/70">
                            <input type="checkbox" name="estados[]" value="{{ $estado }}" class="w-3.5 h-3.5 rounded accent-primary" {{ in_array($estado, $filtros['estados']) ? 'checked' : '' }} onchange="this.form.submit()">
                            <span>{{ $estado }}</span>
                        </label>
                    @endforeach
                </div>
                @if(!empty($filtros['estados']))
                    <a href="{{ route('actividades.index') }}" class="text-[11px] text-accent hover:text-accent/80 transition-colors mt-2 inline-block">Quitar filtro de estado</a>
                @endif
            </div>

            @if(auth()->user()->rolEnum() === \App\Enums\RolUsuario::Presidencia)
                <a href="{{ route('actividades.create') }}" class="block text-center bg-accent text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-accent/90 transition-colors">+ Nueva actividad</a>
            @endif
        </form>

        {{-- Tabla --}}
        <div class="bg-white border border-line rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-paper text-left text-[11px] uppercase tracking-wide text-ink/45">
                            <th class="px-5 py-2.5 font-medium">Actividad</th>
                            @if($organizaciones)
                                <th class="px-5 py-2.5 font-medium">Organización</th>
                            @endif
                            <th class="px-5 py-2.5 font-medium">Fecha</th>
                            <th class="px-5 py-2.5 font-medium">Estado</th>
                            <th class="px-5 py-2.5 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse($actividades as $actividad)
                            <tr class="hover:bg-paper/50 transition-colors">
                                <td class="px-5 py-3 font-medium">{{ $actividad->nombre }}</td>
                                @if($organizaciones)
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1.5 text-ink/70">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ $actividad->organizacion->color }}"></span>
                                            {{ $actividad->organizacion->nombre }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-5 py-3 font-mono text-ink/60">{{ $actividad->fecha->format('d/m/Y') }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: {{ $actividad->estadoActual->color }}1a; color: {{ $actividad->estadoActual->color }}">
                                        {{ $actividad->estadoActual->nombre }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('actividades.show', $actividad) }}" class="text-ink/30 hover:text-primary transition-colors p-1.5" title="Ver detalle">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="2.8"/></svg>
                                        </a>
                                        @can('update', $actividad)
                                            @php $esRechazada = $actividad->estadoActual->nombre === 'Rechazada'; @endphp
                                            @if(!$actividad->estadoActualEnum()->esTerminal() || $esRechazada)
                                                <a href="{{ route('actividades.edit', $actividad) }}" class="text-ink/30 hover:text-primary transition-colors p-1.5" title="{{ $esRechazada ? 'Corregir y reenviar' : 'Editar' }}">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H5a1 1 0 00-1 1v14a1 1 0 001 1h14a1 1 0 001-1v-6M18.5 3.5a2.1 2.1 0 013 3L11 17l-4 1 1-4z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ $organizaciones ? 5 : 4 }}" class="px-5 py-8 text-center text-ink/40">No hay actividades con estos filtros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
    .form-input { width: 100%; border: 1px solid #E4DFD3; border-radius: 0.5rem; padding: 0.55rem 0.75rem; font-size: 0.8rem; background: #fff; }
    .form-label { display:block; font-size: 0.75rem; font-weight: 500; color:#26282B99; margin-bottom: 0.3rem; }
</style>
@endpush
