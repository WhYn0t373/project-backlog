import axios from 'axios';

/**
 * Retrieves the CSRF token from the XSRF-TOKEN cookie.
 * @returns {string | null} The CSRF token or null if not found.
 */
export function getCsrfToken(): string | null {
  const match = document.cookie.match(/(^|;)\\s*XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[2]) : null;
}

/**
 * Sets up Axios to automatically include the CSRF token in the
 * X-CSRF-Token header for every outgoing request.
 */
export function setupAxiosCsrf(): void {
  axios.interceptors.request.use((config) => {
    const token = getCsrfToken();
    if (token && config.headers) {
      config.headers['X-CSRF-Token'] = token;
    }
    return config;
  });
}

export default { getCsrfToken, setupAxiosCsrf };