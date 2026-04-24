import axios from 'axios';
import { setupAxiosCsrf } from '../auth';

setupAxiosCsrf();

/**
 * Response shape for authentication endpoints.
 */
export interface AuthResponse {
  access_token: string;
  token_type: string;
  expires_in: number;
  refresh_token?: string;
}

/**
 * Log in with email & password.
 * @param email User's email
 * @param password User's password
 */
export async function login(email: string, password: string): Promise<AuthResponse> {
  const response = await axios.post<AuthResponse>('/api/login', {
    email,
    password,
  });
  return response.data;
}

/**
 * Refresh an access token using a refresh token.
 * @param refreshToken Token used to obtain a new access token
 */
export async function refreshToken(refreshToken: string): Promise<AuthResponse> {
  const response = await axios.post<AuthResponse>('/api/auth/refresh', {
    refresh_token: refreshToken,
  });
  return response.data;
}

/**
 * Log out the current user.
 */
export async function logout(): Promise<void> {
  await axios.post('/api/logout');
}