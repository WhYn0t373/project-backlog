#!/usr/bin/env bash
set -euo pipefail

# Start Laravel development server in the background
php artisan serve --host=0.0.0.0 --port=8000 > /dev/null 2>&1 &
SERVER_PID=$!

# Wait until the server is ready
echo "Waiting for the server to start..."
for i in {1..30}; do
  if curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo "Server is up."
    break
  fi
  sleep 1
done

# Run a simple benchmark: 10 consecutive requests to the health endpoint
echo "Running benchmark..."
START=$(date +%s%N)
for i in $(seq 1 10); do
  curl -s http://localhost:8000/api/health > /dev/null
done
END=$(date +%s%N)
DIFF=$((END - START))
echo "Total time for 10 requests: ${DIFF} ns"

# Stop the server
kill $SERVER_PID