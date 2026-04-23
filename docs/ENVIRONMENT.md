# Environment Variables

The following environment variables are required by the application. Each variable has an example value and a brief description.

| Variable | Example Value | Purpose / Description |
|---|---|---|
| `APP_ENV` | `development` | The application environment (e.g., `development`, `staging`, `production`). |
| `DATABASE_URL` | `postgresql+asyncpg://user:password@localhost:5432/mydb` | Connection string for the PostgreSQL database. |
| `SECRET_KEY` | `supersecretkey1234567890` | Used to sign JSON Web Tokens and other cryptographic operations. |
| `JWT_ALGORITHM` | `HS256` | The algorithm used to encode JWTs. |
| `JWT_ACCESS_TOKEN_EXPIRES_MINUTES` | `15` | How long an access token is valid, in minutes. |
| `JWT_REFRESH_TOKEN_EXPIRES_DAYS` | `30` | How long a refresh token is valid, in days. |
| `EMAIL_HOST` | `smtp.gmail.com` | SMTP host used for sending emails. |
| `EMAIL_PORT` | `587` | SMTP port. |
| `EMAIL_HOST_USER` | `your.email@example.com` | Username for the SMTP server. |
| `EMAIL_HOST_PASSWORD` | `emailpassword` | Password for the SMTP server. |
| `EMAIL_USE_TLS` | `True` | Whether to use TLS for SMTP. |
| `EMAIL_USE_SSL` | `False` | Whether to use SSL for SMTP. |
| `LOG_LEVEL` | `INFO` | The minimum log level that will be emitted. |
| `ALLOWED_HOSTS` | `localhost,127.0.0.1` | List of hosts that are allowed to serve the application. |
| `SESSION_COOKIE_NAME` | `app_session` | Name of the session cookie. |
| `SESSION_COOKIE_SECURE` | `True` | Whether to only send the session cookie over HTTPS. |
| `SESSION_COOKIE_HTTPONLY` | `True` | Whether the session cookie is HTTP only. |
| `SESSION_COOKIE_SAMESITE` | `Lax` | SameSite policy for the session cookie. |
| `CELERY_BROKER_URL` | `redis://localhost:6379/0` | The broker URL used by Celery. |
| `CELERY_RESULT_BACKEND` | `redis://localhost:6379/0` | The backend used to store Celery task results. |
| `REDIS_URL` | `redis://localhost:6379/0` | Connection URL for the Redis cache. |
| `SENTRY_DSN` | `https://publicKey@o0.ingest.sentry.io/0` | DSN for Sentry error tracking. |

> **Note**: If you are using Docker Compose or a cloud platform, you can pass these values via a `.env` file or as part of the service definition.

Make sure all values are kept secret in CI/CD and local environments. For example, use a `.env` file that is added to `.gitignore` to avoid committing sensitive data.

--- 