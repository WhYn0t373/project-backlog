@props(['title' => 'Modal Title', 'id' => 'modal'])

<div x-data="modalComponent()"
     @keydown.escape.window="close"
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center"
     role="dialog"
     :aria-labelledby="`{{
        id
     }}-title`"
     aria-modal="true"
     x-ref="dialog">
    <div class="fixed inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded shadow-lg z-10 max-w-lg w-full mx-4 p-6" @click.away="close">
        <h2 :id="`${{ id }}-title`" class="text-xl font-semibold mb-4">{{ $title }}</h2>
        <div>
            {{ $slot }}
        </div>
        <button type="button"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
                aria-label="Close modal"
                @click="close">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
function modalComponent() {
    return {
        open: false,
        trapFocus: false,
        openModal() {
            this.open = true;
            this.$nextTick(() => {
                this.trapFocus = true;
                const focusable = this.$refs.dialog.querySelectorAll('a, button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
                if (focusable.length) focusable[0].focus();
                document.addEventListener('keydown', this.handleTab);
            });
        },
        close() {
            this.open = false;
            this.trapFocus = false;
            document.removeEventListener('keydown', this.handleTab);
        },
        handleTab(e) {
            if (!this.trapFocus) return;
            if (e.key === 'Tab') {
                const focusable = [...this.$refs.dialog.querySelectorAll('a, button, textarea, input, select, [tabindex]:not([tabindex="-1"])')];
                if (focusable.length === 0) return;
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        }
    };
}
</script>