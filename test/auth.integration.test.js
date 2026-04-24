/**
 * Integration tests for authentication endpoints.
 *
 * These tests cover the full authentication flow:
 * 1. Signup
 * 2. Login
 * 3. Refresh
 * 4. Logout
 *
 * Each test verifies HTTP status codes, presence and format of JWT
 * tokens, and the ability to clean up test data after execution.
 */

const request = require('supertest');
const jwt = require('jsonwebtoken');
const path = require('path');

// Adjust the import paths based on your project structure.
// Assume the Express app is exported from `src/app.js`.
const app = require('../../src/app');

// Database helper to clean up test users.
// Adjust the import path to match your project.
const { getDbConnection } = require('../../src/database');

// Test credentials
const TEST_EMAIL = `testuser_${Date.now()}@example.com`;
const TEST_PASSWORD = 'TestPass123!';

// Store tokens for subsequent requests
let accessToken = null;
let refreshToken = null;

// Helper to decode JWT without verification (for structure checks)
const decodeJwt = (token) => {
  try {
    return jwt.decode(token, { complete: true });
  } catch (err) {
    return null;
  }
};

describe('Authentication Integration Tests', () => {
  let dbConnection;

  beforeAll(async () => {
    // Establish a test database connection.
    dbConnection = await getDbConnection();
  });

  afterAll(async () => {
    // Close the database connection after all tests.
    await dbConnection.close();
  });

  afterEach(async () => {
    // Clean up the test user to keep the database clean.
    await dbConnection
      .collection('users')
      .deleteMany({ email: TEST_EMAIL });
  });

  describe('Signup', () => {
    it('should create a user and return 201 with access and refresh tokens', async () => {
      const response = await request(app)
        .post('/api/auth/signup')
        .send({ email: TEST_EMAIL, password: TEST_PASSWORD })
        .expect(201);

      const { body } = response;
      expect(body).toHaveProperty('accessToken');
      expect(body).toHaveProperty('refreshToken');

      // Basic JWT structure check
      const decodedAccess = decodeJwt(body.accessToken);
      const decodedRefresh = decodeJwt(body.refreshToken);
      expect(decodedAccess).not.toBeNull();
      expect(decodedRefresh).not.toBeNull();

      // Store tokens for subsequent tests
      accessToken = body.accessToken;
      refreshToken = body.refreshToken;
    });
  });

  describe('Login', () => {
    beforeAll(async () => {
      // Ensure the test user exists by signing up again if necessary.
      if (!accessToken) {
        const signupResp = await request(app)
          .post('/api/auth/signup')
          .send({ email: TEST_EMAIL, password: TEST_PASSWORD })
          .expect(201);
        accessToken = signupResp.body.accessToken;
        refreshToken = signupResp.body.refreshToken;
      }
    });

    it('should authenticate the user and return 200 with tokens', async () => {
      const response = await request(app)
        .post('/api/auth/login')
        .send({ email: TEST_EMAIL, password: TEST_PASSWORD })
        .expect(200);

      const { body } = response;
      expect(body).toHaveProperty('accessToken');
      expect(body).toHaveProperty('refreshToken');

      // Basic JWT structure check
      const decodedAccess = decodeJwt(body.accessToken);
      const decodedRefresh = decodeJwt(body.refreshToken);
      expect(decodedAccess).not.toBeNull();
      expect(decodedRefresh).not.toBeNull();

      // Update stored tokens
      accessToken = body.accessToken;
      refreshToken = body.refreshToken;
    });
  });

  describe('Refresh', () => {
    beforeAll(async () => {
      // Ensure we have a refresh token from the login/signup flow.
      if (!refreshToken) {
        const signupResp = await request(app)
          .post('/api/auth/signup')
          .send({ email: TEST_EMAIL, password: TEST_PASSWORD })
          .expect(201);
        refreshToken = signupResp.body.refreshToken;
      }
    });

    it('should return a new access token (and optionally a new refresh token) with 200', async () => {
      const response = await request(app)
        .post('/api/auth/refresh')
        .send({ refreshToken })
        .expect(200);

      const { body } = response;
      expect(body).toHaveProperty('accessToken');
      // Some implementations may also issue a new refresh token.
      // This test accepts either scenario.
      const hasNewRefresh = !!body.refreshToken;

      const decodedAccess = decodeJwt(body.accessToken);
      expect(decodedAccess).not.toBeNull();

      if (hasNewRefresh) {
        const decodedRefresh = decodeJwt(body.refreshToken);
        expect(decodedRefresh).not.toBeNull();
        // Update stored tokens for subsequent logout test
        refreshToken = body.refreshToken;
      }

      // Store new access token for logout
      accessToken = body.accessToken;
    });
  });

  describe('Logout', () => {
    beforeAll(async () => {
      // Ensure we have a valid access and refresh token
      if (!accessToken || !refreshToken) {
        const signupResp = await request(app)
          .post('/api/auth/signup')
          .send({ email: TEST_EMAIL, password: TEST_PASSWORD })
          .expect(201);
        accessToken = signupResp.body.accessToken;
        refreshToken = signupResp.body.refreshToken;
      }
    });

    it('should invalidate the refresh token and return 204 No Content', async () => {
      await request(app)
        .post('/api/auth/logout')
        .send({ refreshToken })
        .expect(204);

      // Verify that the refresh token can no longer be used.
      await request(app)
        .post('/api/auth/refresh')
        .send({ refreshToken })
        .expect(401); // Unauthorized or similar error
    });
  });
});
