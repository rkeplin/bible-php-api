#!/bin/bash
# Build + push the bible-php-api image and roll the k8s deployment.
set -euo pipefail

REPO_SLUG="rkeplin/bible-php-api"
TAG=$(git rev-parse --short HEAD)

docker build --platform linux/amd64 -t "$REPO_SLUG:$TAG" -t "$REPO_SLUG:latest" .
docker push "$REPO_SLUG:$TAG"
docker push "$REPO_SLUG:latest"

if kubectl -n bible get deploy/bible-php-api >/dev/null 2>&1; then
  kubectl -n bible set image deploy/bible-php-api api="$REPO_SLUG:$TAG"
  kubectl -n bible rollout status deploy/bible-php-api
else
  echo "deploy/bible-php-api not found yet — skipping rollout (run 'make k8s-deploy' first)"
fi
