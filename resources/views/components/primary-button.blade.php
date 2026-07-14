<button {{ $attributes->merge(['type' => 'submit', 'class' => 'block w-full bg-accent hover:bg-accent/90 text-white text-center text-sm font-medium py-2.5 rounded-lg transition-colors disabled:opacity-60 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
