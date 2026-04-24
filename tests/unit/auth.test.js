/**
 * @jest-environment jsdom
 */
import { getCsrfToken, setupAxiosCsrf } from '@/resources/js/auth';
import axios from 'axios';

describe('auth.js utilities', () => {
  beforeEach(() => {
    document.cookie = ''; // reset cookies
  });

  test('getCsrfToken returns token when cookie is present', () => {
    document.cookie = 'XSRF-TOKEN=abc123';
    expect(getCsrfToken()).toBe('abc123');
  });

  test('getCsrfToken returns decoded token', () => {
    document.cookie = 'XSRF-TOKEN=encoded%20token';
    expect(getCsrfToken()).toBe('encoded token');
  });

  test('getCsrfToken returns null when cookie is missing', () => {
    expect(getCsrfToken()).toBeNull();
  });

  test('setupAxiosCsrf attaches token to outgoing requests', () => {
    document.cookie = 'XSRF-TOKEN=token123';
    // axios interceptor returns a function that accepts config
    const interceptor = setupAxiosCsrf();
    const config = { headers: {} };
    const newConfig = interceptor(config);
    expect(newConfig.headers['X-CSRF-Token']).toBe('token123');
  });
});