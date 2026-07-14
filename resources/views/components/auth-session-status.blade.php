@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg px-4 py-3 mb-5']) }}>
        {{ $status }}
    </div>
@endif
