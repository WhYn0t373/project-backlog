#!/usr/bin/env bash
set -euo pipefail

if [ $# -ne 1 ]; then
  echo "Usage: $0 <image_tag>"
  exit 1
fi

IMAGE_TAG="$1"

echo "Pulling Docker image ${IMAGE_TAG}"
docker pull "${IMAGE_TAG}"

echo "Updating docker-compose services"
export IMAGE_TAG
docker compose pull
docker compose up -d --remove-orphans

echo "Deployment completed."