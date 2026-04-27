```js
import { _, axios } from '../../resources/js/vendor';

describe('vendor.js exports', () => {
  test('exports lodash as _', () => {
    expect(_).toBeDefined();
    expect(typeof _).toBe('function');
    // lodash functions
    expect(typeof _.isArray).toBe('function');
  });

  test('exports axios', () => {
    expect(axios).toBeDefined();
    expect(typeof axios).toBe('function');
    expect(typeof axios.get).toBe('function');
    expect(typeof axios.post).toBe('function');
  });
});
```