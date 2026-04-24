#!/usr/bin/env bash
set -euo pipefail

echo "=== Running PHP CS Fixer ==="
php-cs-fixer fix --dry-run --diff

echo "=== Running ESLint ==="
npm run lint