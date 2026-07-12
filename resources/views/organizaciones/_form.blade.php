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
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-input" value="{{ old('nombre', $organizacion->nombre ?? '') }}" required>
    </div>

    <div>
        <label class="form-label">Color</label>
        <div class="flex items-center gap-3">
            <input type="color" name="color" value="{{ old('color', $organizacion->color ?? '#2B3A4A') }}" class="w-12 h-10 rounded-lg border border-line cursor-pointer">
            <span class="text-xs text-ink/40 font-mono">Se usa en el calendario y las leyendas</span>
        </div>
    </div>
</div>

@push('head')
<style>
    .form-input { width: 100%; border: 1px solid #E4DFD3; border-radius: 0.5rem; padding: 0.6rem 0.75rem; font-size: 0.875rem; background: #fff; }
    .form-input:focus { border-color: #C08A3E; outline: none; }
    .form-label { display:block; font-size: 0.8rem; font-weight: 500; color:#26282B; margin-bottom: 0.35rem; }
</style>
@endpush
