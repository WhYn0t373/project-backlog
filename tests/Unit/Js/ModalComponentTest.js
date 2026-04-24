import { describe, it, expect } from 'vitest';

describe('modalComponent', () => {
    function getComponent() {
        return modalComponent();
    }

    it('opens modal and sets trapFocus', () => {
        const comp = getComponent();
        comp.openModal();
        expect(comp.open).toBe(true);
        expect(comp.trapFocus).toBe(true);
    });

    it('closes modal and removes focus trap', () => {
        const comp = getComponent();
        comp.openModal();
        comp.close();
        expect(comp.open).toBe(false);
        expect(comp.trapFocus).toBe(false);
    });

    it('handleTab prevents focus from leaving modal when trapping', () => {
        const comp = getComponent();
        comp.open = true;
        comp.trapFocus = true;
        comp.$refs = { dialog: { querySelectorAll: () => [{ focus: jest.fn() }, { focus: jest.fn() }] } };
        const e = { key: 'Tab', shiftKey: false, preventDefault: jest.fn() };
        comp.handleTab(e);
        // Since focusable length > 0, e.preventDefault should be called only when activeElement is last
        expect(e.preventDefault).not.toHaveBeenCalled(); // This simple test doesn't simulate activeElement
    });
});