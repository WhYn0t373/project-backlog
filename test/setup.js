/**
 * Global test setup for authentication integration tests.
 *
 * This file is referenced in `jest.config.js` via `setupFilesAfterEnv`.
 * It loads environment variables from `.env.test`, ensures the test
 * environment is properly configured, and applies a generous timeout
 * for potentially long‑running database operations.
 */

require('dotenv').config({ path: '.env.test' });

/* Ensure the Node environment is explicitly set to "test" */
process.env.NODE_ENV = 'test';

/* Set a global Jest timeout to avoid flaky tests on CI */
jest.setTimeout(30_000); // 30 seconds