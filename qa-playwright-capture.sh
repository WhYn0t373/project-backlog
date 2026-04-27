#!/usr/bin/env bash
set -euo pipefail

# Script to run Playwright tests on all configured BrowserStack projects.
# Screenshots are automatically captured by the test itself and stored under
# public/qa-screenshots/<browser>.

BROWSERS=("chromium" "firefox" "webkit")

for BROWSER in "${BROWSERS[@]}"; do
  echo "Running Playwright tests on $BROWSER..."
  npx playwright test --project="$BROWSER"
done

echo "All cross‑browser tests completed successfully."