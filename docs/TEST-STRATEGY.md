# Test Strategy

## Overview
This document outlines the test strategy for the project. The goal is to ensure all code changes maintain or improve the quality and reliability of the application by executing a comprehensive set of tests across different layers of the stack.

## Scope of Tests

| Test Type | Description | Tool | Environment | Coverage Goal |
|-----------|-------------|------|-------------|---------------|
| **Unit** | Test isolated units of code (controllers, services, helpers). | PHPUnit | Local / GitHub Actions | ≥ 90 % statement coverage |
| **Integration** | Verify interactions between components (e.g., database, API endpoints). | PHPUnit + Laravel’s HTTP testing | Local / GitHub Actions | ≥ 80 % statement coverage |
| **End‑to‑End (E2E)** | Simulate user workflows and ensure the system behaves correctly. | Cypress (or Laravel Dusk) | Local / GitHub Actions | ≥ 70 % functional coverage |

> **Note:** E2E tests are optional for branches that do not touch critical flows. They can be run manually or on a scheduled basis.

## Test Environment

| Layer | Environment | Configuration |
|-------|-------------|---------------|
| **Local** | `docker-compose up -d` | Docker images for PHP, MySQL, Redis, and Node (if needed). |
| **CI** | GitHub Actions (ubuntu‑latest) | PHP 8.2, Composer, Xdebug for coverage. |
| **Staging** | Dedicated deployment | Mirrors production as closely as possible. |

## Test Data & Seeding

- Test databases are created via Laravel migrations.
- Factories and seeders are used for generating realistic test data.
- Test suites run against an isolated SQLite in‑memory database when possible to speed up execution.

## Reporting & Metrics

- **Coverage**: Coverage reports are generated and uploaded to Codecov. The pipeline fails if coverage drops below the thresholds.
- **Logs**: Test output is captured and stored in the GitHub Actions UI for easy review.
- **PR Checks**: GitHub status checks enforce that the test suite passes before merging.

## Maintenance

- All new feature branches must include any required test files (PHPUnit test classes or Cypress specs).
- When adding new dependencies that affect testing (e.g., a new PHP extension), update the CI workflow accordingly.
- Periodically review test coverage reports and refactor tests to eliminate redundancy.

---

**Signed off by**  
*Development Lead*  
*Date:* `2026‑04‑24`
