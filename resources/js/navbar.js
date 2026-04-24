document.addEventListener('alpine:init', () => {
  Alpine.data('navbar', () => ({
    open: false,
    toggle() {
      this.open = !this.open;
    }
  }));
});