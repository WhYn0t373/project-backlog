import { describe, test, expect } from '@jest/globals';

describe('Alpine navbar component', () => {
  test('toggle() flips the open state', () => {
    const data = {
      open: false,
      toggle() { this.open = !this.open; }
    };

    expect(data.open).toBe(false);
    data.toggle();
    expect(data.open).toBe(true);
    data.toggle();
    expect(data.open).toBe(false);
  });
});