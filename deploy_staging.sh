#!/usr/bin/env bash
set -euo pipefail

# Environment variables expected:
#   REGISTRY_URL - e.g., ghcr.io/owner
#   IMAGE_NAME   - Docker image name (e.g., myapp)
#   IMAGE_TAG    - Commit SHA or tag to deploy

IMAGE="${REGISTRY_URL}/${IMAGE_NAME}:${IMAGE_TAG}"
CONTAINER_NAME="${IMAGE_NAME}-staging"

echo "Pulling latest image: $IMAGE"
docker pull "$IMAGE"

echo "Stopping and removing any existing container"
docker rm -f "$CONTAINER_NAME" || true

echo "Running new container"
docker run -d \
  --name "$CONTAINER_NAME" \
  -p 80:80 \
  "$IMAGE"

echo "Deployment completed. Container ${CONTAINER_NAME} is running."