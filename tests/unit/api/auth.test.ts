import axios from 'axios';
import { login, refreshToken, logout } from '@/resources/js/api/auth';

jest.mock('axios');

const mockedAxios = axios as jest.Mocked<typeof axios>;

describe('API Auth Service', () => {
  afterEach(() => {
    jest.clearAllMocks();
  });

  test('login returns AuthResponse and calls correct endpoint', async () => {
    const mockResponse = {
      data: {
        access_token: 'token',
        token_type: 'Bearer',
        expires_in: 3600,
        refresh_token: 'refresh',
      },
    };
    mockedAxios.post.mockResolvedValueOnce(mockResponse);

    const result = await login('test@example.com', 'secret');

    expect(result).toEqual(mockResponse.data);
    expect(mockedAxios.post).toHaveBeenCalledWith('/api/login', {
      email: 'test@example.com',
      password: 'secret',
    });
  });

  test('refreshToken returns AuthResponse and calls correct endpoint', async () => {
    const mockResponse = {
      data: {
        access_token: 'newtoken',
        token_type: 'Bearer',
        expires_in: 3600,
      },
    };
    mockedAxios.post.mockResolvedValueOnce(mockResponse);

    const result = await refreshToken('oldrefresh');

    expect(result).toEqual(mockResponse.data);
    expect(mockedAxios.post).toHaveBeenCalledWith('/api/auth/refresh', {
      refresh_token: 'oldrefresh',
    });
  });

  test('logout posts to /api/logout', async () => {
    mockedAxios.post.mockResolvedValueOnce({ status: 204 });

    await logout();

    expect(mockedAxios.post).toHaveBeenCalledWith('/api/logout');
  });
});