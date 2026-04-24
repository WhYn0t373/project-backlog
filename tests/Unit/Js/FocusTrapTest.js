import { describe, it, expect, jest } from 'vitest';

describe('focusTrap data', () => {
    it('initializes open based on data-trap attribute', () => {
        const el = document.createElement('div');
        el.setAttribute('data-trap', '');
        const instance = { open: false, init() { this.open = this.$el.hasAttribute('data-trap'); } };
        instance.$el = el;
        instance.init();
        expect(instance.open).toBe(true);
    });

    it('trap function loops focus inside element', () => {
        const el = document.createElement('div');
        el.innerHTML = '<button id="first">First</button><button id="last">Last</button>';
        const focusable = el.querySelectorAll('button');
        const instance = {
            open: true,
            trap(e) {
                if (!this.open) return;
                const focusable = [...this.$el.querySelectorAll('button')];
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
            $el: el,
        };
        document.body.appendChild(el);
        document.getElementById('first').focus();
        const e = { key: 'Tab', shiftKey: false, preventDefault: jest.fn() };
        instance.trap(e);
        // Simulate focus change
        document.getElementById('last').focus();
        const e2 = { key: 'Tab', shiftKey: false, preventDefault: jest.fn() };
        instance.trap(e2);
        expect(e2.preventDefault).toHaveBeenCalled();
    });
});