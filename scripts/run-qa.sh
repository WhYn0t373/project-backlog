#!/usr/bin/env bash
set -euo pipefail

# Run all Playwright tests; the config already defines projects for each browser
npx playwright test