import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('alpine:init', () => {
  Alpine.data('focusTrap', () => ({
    open: false,
    init() {
      this.open = this.$el.hasAttribute('data-trap');
    },
    trap(e) {
      if (!this.open) return;
      const focusable = [...this.$el.querySelectorAll('a, button, textarea, input, select, [tabindex]:not([tabindex="-1"])')];
      if (!focusable.length) return;
      if (e.key === 'Tab') {
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
    },
    close() {
      this.open = false;
    },
  }));
});