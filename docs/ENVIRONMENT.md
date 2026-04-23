# Environment Variables

This document provides a reference for all environment variables required by the project.  
These variables configure application runtime, database connectivity, email delivery, queueing, and other optional features.  

| Variable | Description | Example Value | Default / Recommended |
|----------|-------------|---------------|---------------------------|
| **APP_ENV** | Current environment of the application (e.g. `development`, `staging`, `production`). | `development` | `development` |
| **APP_KEY** | Base64 encoded application key used for encryption / signing. | `base64:Kz0s8q2J1+...` | Must be generated (`openssl rand -base64 32`) |
| **APP_DEBUG** | Enables debug mode. Set to `true` in dev, `false` in prod. | `false` | `false` |
| **APP_URL** | Base URL of the application. | `https://example.com` | None |
| **DB_CONNECTION** | Database driver (`postgres`, `mysql`, `sqlite`, etc.). | `postgres` | `postgres` |
| **DB_HOST** | Hostname of the database server. | `127.0.0.1` | `127.0.0.1` |
| **DB_PORT** | Port of the database server. | `5432` | Depends on driver |
| **DB_DATABASE** | Database name. | `app_db` | None |
| **DB_USERNAME** | Database user. | `db_user` | None |
| **DB_PASSWORD** | Password for the database user. | `supersecret` | None |
| **DB_CHARSET** | Character set used by the database. | `utf8mb4` | `utf8mb4` |
| **DB_POOL_SIZE** | Maximum number of connections in the pool. | `10` | `10` |
| **DB_MAX_OVERFLOW** | Maximum number of connections that can be created above the pool size. | `20` | `20` |
| **MAIL_MAILER** | Mailer backend (`smtp`, `mailgun`, `ses`, etc.). | `smtp` | `smtp` |
| **MAIL_HOST** | SMTP host. | `smtp.mailtrap.io` | None |
| **MAIL_PORT** | SMTP port. | `2525` | `25`, `465`, `587` |
| **MAIL_USERNAME** | SMTP username. | `user@example.com` | None |
| **MAIL_PASSWORD** | SMTP password. | `s3cr3t` | None |
| **MAIL_ENCRYPTION** | Encryption protocol (`tls`, `ssl`, or empty). | `tls` | `tls` |
| **MAIL_FROM_ADDRESS** | Default “from” email address. | `noreply@example.com` | None |
| **MAIL_FROM_NAME** | Default “from” display name. | `Example App` | None |
| **QUEUE_CONNECTION** | Queue driver (`redis`, `sqs`, `database`, `sync`). | `redis` | `sync` |
| **QUEUE_HOST** | Queue host (for redis or other external drivers). | `127.0.0.1` | `127.0.0.1` |
| **QUEUE_PORT** | Queue port. | `6379` | Depends on driver |
| **QUEUE_DATABASE** | Queue database index (for redis). | `0` | `0` |
| **QUEUE_PASSWORD** | Queue password (if required). | `qpass` | None |
| **JWT_SECRET** | Secret key used to sign JWT tokens. | `my_jwt_secret` | Must be 256‑bit string |
| **JWT_EXPIRES_IN** | Token expiration time (e.g. `3600`). | `3600` | `3600` |
| **LOG_LEVEL** | Logging verbosity (`debug`, `info`, `warning`, `error`). | `info` | `info` |
| **API_PREFIX** | Base path prefix for all API endpoints. | `/api/v1` | `/api/v1` |
| **CORS_ALLOWED_ORIGINS** | Comma separated list of allowed origins. | `http://localhost:3000,https://example.com` | `http://localhost:3000` |
| **REDIS_URL** | Full Redis URL (used by some libraries). | `redis://localhost:6379/0` | None |

> **Note**: Not all variables are mandatory. Some are optional and only required when you enable the corresponding feature (e.g., email, queueing, JWT authentication).

## Example `.env` File

Below is a minimal `.env` file you can copy to the root of your project and then modify as needed:

```
# General Application
APP_ENV=development
APP_KEY=base64:Kz0s8q2J1+...   # Replace with your own key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=postgres
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=app_db
DB_USERNAME=app_user
DB_PASSWORD=supersecret

# Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=user@example.com
MAIL_PASSWORD=s3cr3t
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Example App"

# Queue
QUEUE_CONNECTION=redis
QUEUE_HOST=127.0.0.1
QUEUE_PORT=6379
QUEUE_DATABASE=0

# JWT
JWT_SECRET=my_jwt_secret
JWT_EXPIRES_IN=3600

# Logging
LOG_LEVEL=info

# API
API_PREFIX=/api/v1

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000

# Redis URL (optional)
# REDIS_URL=redis://localhost:6379/0
```

## Security
- **Never commit your real secrets to version control.** The `.env` file should be added to `.gitignore`.
- **Environment Separation**: For each deployment target (dev, staging, prod) create a dedicated `.env.{environment}` file and load it with the appropriate loader.
- **Docker**: When running with Docker Compose or Kubernetes, you can map these variables into the container via the `environment` section or secrets.
- **Reloading**: Changes to environment variables usually require a restart of the FastAPI application.
- **Validation**: The project uses `pydantic` settings to load and validate these variables; invalid or missing required fields will cause the application to fail on startup with clear error messages.

--- 

For any missing or custom variables, refer to the respective module documentation or the code where they are accessed. This file should serve as the single source of truth for all configuration values needed to run the application.
