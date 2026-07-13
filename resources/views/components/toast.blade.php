@php
    $mensajes = [];
    if (session('exito')) {
        $mensajes[] = ['tipo' => 'exito', 'texto' => session('exito')];
    }
    if (session('error')) {
        $mensajes[] = ['tipo' => 'error', 'texto' => session('error')];
    }
@endphp

@if(count($mensajes))
    <div x-data="toastComponent()" x-init="init()"
        class="fixed top-5 right-5 z-[100] space-y-2 w-[calc(100%-2.5rem)] max-w-xs">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="rounded-lg shadow-lg border px-4 py-3 flex items-start gap-2.5 bg-white"
                :class="toast.tipo === 'exito' ? 'border-sage/30' : 'border-brick/30'">
                <svg class="w-4 h-4 mt-0.5 shrink-0" :class="toast.tipo === 'exito' ? 'text-sage' : 'text-brick'"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <template x-if="toast.tipo === 'exito'">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                    </template>
                    <template x-if="toast.tipo !== 'exito'">
                        <path d="M12 9v4m0 4h.01M12 3l9 16H3z" stroke-linecap="round" stroke-linejoin="round" />
                    </template>
                </svg>
                <p class="text-sm text-ink flex-1" x-text="toast.texto"></p>
                <button @click="quitar(toast.id)" class="text-ink/30 hover:text-ink shrink-0 transition-colors">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <script>
        function toastComponent() {
            return {
                toasts: @json($mensajes).map((t, i) => ({ ...t, id: i })),
                init() {
                    this.toasts.forEach((t) => setTimeout(() => this.quitar(t.id), 5000));
                },
                quitar(id) {
                    this.toasts = this.toasts.filter((t) => t.id !== id);
                },
            };
        }
    </script>
@endif
