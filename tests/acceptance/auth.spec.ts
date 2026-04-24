import { test, expect, request } from '@playwright/test';
import { login, refreshToken } from '../../resources/js/api/auth';

const BASE_URL = process.env.BASE_URL ?? 'http://localhost:8000';

test.describe('Auth token lifecycle and CSRF protection', () => {
  let apiContext: ReturnType<typeof request.newContext>;

  test.beforeAll(async () => {
    apiContext = await request.newContext({
      baseURL: BASE_URL,
      extraHTTPHeaders: {
        Accept: 'application/json',
      },
    });
  });

  test('Token expiration and refresh flow', async () => {
    // 1️⃣ Log in and obtain tokens
    const loginResp = await apiContext.post('/api/login', {
      data: { email: 'test@example.com', password: 'secret' },
    });
    expect(loginResp.ok()).toBeTruthy();
    const loginData = await loginResp.json();
    const { access_token, refresh_token } = loginData;

    // Set the access token for authenticated requests
    apiContext.setExtraHTTPHeaders({
      Authorization: `Bearer ${access_token}`,
    });

    // 2️⃣ Verify protected endpoint works with fresh token
    const protectedResp = await apiContext.get('/api/protected');
    expect(protectedResp.status()).toBe(200);

    // 3️⃣ Wait for the token to expire (assuming 1‑minute expiry for tests)
    await test.info().waitForTimeout(61_000); // 61 seconds

    // 4️⃣ Attempt a protected request, should now be unauthorized
    const unauthorizedResp = await apiContext.get('/api/protected');
    expect(unauthorizedResp.status()).toBe(401);

    // 5️⃣ Refresh the token
    const refreshResp = await apiContext.post('/api/auth/refresh', {
      data: { refresh_token },
    });
    expect(refreshResp.ok()).toBeTruthy();
    const newAuth = await refreshResp.json();

    // New access token must differ from the old one
    expect(newAuth.access_token).not.toBe(access_token);

    // Update header with new token and verify protected endpoint again
    apiContext.setExtraHTTPHeaders({
      Authorization: `Bearer ${newAuth.access_token}`,
    });
    const protectedAfterRefresh = await apiContext.get('/api/protected');
    expect(protectedAfterRefresh.status()).toBe(200);
  });

  test('CSRF protection rejects requests without valid token', async () => {
    // Create a context without CSRF cookie
    const noCsrfContext = await request.newContext({
      baseURL: BASE_URL,
      extraHTTPHeaders: {
        Accept: 'application/json',
      },
    });

    // Attempt a stateful POST that requires CSRF
    const csrfResp = await noCsrfContext.post('/api/protected', { data: {} });
    expect(csrfResp.status()).toBe(419); // 419 indicates CSRF failure
  });
});