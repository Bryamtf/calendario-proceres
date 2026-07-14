import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("submit", function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    const botones = form.querySelectorAll('button:not([type="button"])');
    if (botones.length === 0) return;

    if (botones[0].disabled) {
        event.preventDefault();
        return;
    }

    botones.forEach((btn) => {
        btn.disabled = true;
        btn.classList.add("opacity-60", "cursor-not-allowed");
    });

    const overlay = document.createElement("div");
    overlay.className =
        "fixed inset-0 bg-black/30 z-[200] flex items-center justify-center";
    overlay.innerHTML = `
        <div class="bg-white rounded-xl px-6 py-5 flex items-center gap-3 shadow-lg">
            <svg class="animate-spin w-5 h-5 text-primary" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-sm font-medium text-ink">Guardando...</span>
        </div>`;
    document.body.appendChild(overlay);
});
