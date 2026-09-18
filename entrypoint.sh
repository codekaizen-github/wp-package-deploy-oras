#!/bin/bash
set -euo pipefail

# Required ENV variables:
#   IMAGE_REGISTRY_USERNAME: Registry username
#   IMAGE_REGISTRY_PASSWORD or IMAGE_REGISTRY_PASSWORD_FILE: Registry password
#   IMAGE_REGISTRY_HOSTNAME: Registry name (e.g. registry.example.com)
#   IMAGE_REPOSITORY: Repository path (e.g. org/my-plugin)
#   IMAGE_TAG: Image version tag (e.g. v1)

if [ -z "${WP_PACKAGE_SLUG:-}" ]; then
    echo "WP_PACKAGE_SLUG env variable is required!" >&2
    exit 1
fi
if [ -z "${WP_PACKAGE_HEADERS_FILE:-}" ]; then
    echo "WP_PACKAGE_HEADERS_FILE env variable is required!" >&2
    exit 1
fi
if [ -z "${IMAGE_REGISTRY_USERNAME:-}" ]; then
    echo "IMAGE_REGISTRY_USERNAME env variable is required!" >&2
    exit 1
fi
if [ -z "${IMAGE_REGISTRY_HOSTNAME:-}" ]; then
    echo "IMAGE_REGISTRY_HOSTNAME env variable is required!" >&2
    exit 1
fi
if [ -z "${IMAGE_REPOSITORY:-}" ]; then
    echo "IMAGE_REPOSITORY env variable is required!" >&2
    exit 1
fi
if [ -z "${IMAGE_TAG:-}" ]; then
    echo "IMAGE_TAG env variable is required!" >&2
    exit 1
fi

# Prefer a password file (safe for multi-line secrets).
if [ -n "${IMAGE_REGISTRY_PASSWORD_FILE:-}" ]; then
    if [ ! -f "$IMAGE_REGISTRY_PASSWORD_FILE" ]; then
        echo "IMAGE_REGISTRY_PASSWORD_FILE does not exist: $IMAGE_REGISTRY_PASSWORD_FILE" >&2
        exit 1
    fi
    IMAGE_REGISTRY_PASSWORD="$(cat "$IMAGE_REGISTRY_PASSWORD_FILE")"
fi
if [ -z "${IMAGE_REGISTRY_PASSWORD:-}" ]; then
    echo "IMAGE_REGISTRY_PASSWORD or IMAGE_REGISTRY_PASSWORD_FILE env variable is required!" >&2
    exit 1
fi

# Default path to the WordPress package files (mounted volume)
WP_PACKAGE_PATH="${WP_PACKAGE_PATH:-/package}"

# The headers file should be relative to the package path
WP_PACKAGE_HEADERS_FILE="${WP_PACKAGE_PATH}/${WP_PACKAGE_HEADERS_FILE}"

META_ANNOTATION_KEY="${META_ANNOTATION_KEY:-org.codekaizen-github.wp-package-deploy.wp-package-metadata}"

# Get the directory of this script for relative references
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Verify that the package path exists
ls -la "$WP_PACKAGE_PATH"

# Parse plugin metadata using wp-package-parser script
PACKAGE_METADATA=$(php -d memory_limit="${PHP_MEMORY_LIMIT:-512M}" \
    -d display_errors=0 \
    -d display_startup_errors=0 \
    -d error_reporting=0 \
    -d log_errors=0 \
    "$SCRIPT_DIR/bin/get-package-metadata" 2>/dev/null || true)

if [ -z "$PACKAGE_METADATA" ] || [ "$PACKAGE_METADATA" = "{}" ]; then
    echo "Failed to parse package metadata or no metadata found. Refusing to publish." >&2
    exit 1
fi

# Create a temporary directory to hold the symlink to the package
PACKAGE_LINK_DIR=$(mktemp -d)
PACKAGE_LINK_PATH="${PACKAGE_LINK_DIR}/${WP_PACKAGE_SLUG}"
ln -s "$WP_PACKAGE_PATH" "$PACKAGE_LINK_PATH"
# Create a zip file of the package
PACKAGE_ZIP_DIR=$(mktemp -d)
PACKAGE_ZIP_NAME="${WP_PACKAGE_SLUG}.zip"
PACKAGE_ZIP_FILE="${PACKAGE_ZIP_DIR}/${PACKAGE_ZIP_NAME}"

# Change to the directory containing the zip file
pushd "$PACKAGE_LINK_DIR" >/dev/null
zip -r "$PACKAGE_ZIP_FILE" "$WP_PACKAGE_SLUG"
popd >/dev/null

# Construct full image name
FULL_IMAGE_NAME="${IMAGE_REGISTRY_HOSTNAME}/${IMAGE_REPOSITORY}:${IMAGE_TAG}"

echo "Logging in to registry: $IMAGE_REGISTRY_HOSTNAME (user: $IMAGE_REGISTRY_USERNAME)"
# Use --password-stdin so secrets are never expanded on the command line
printf '%s' "$IMAGE_REGISTRY_PASSWORD" | oras login \
    --username "$IMAGE_REGISTRY_USERNAME" \
    --password-stdin \
    "$IMAGE_REGISTRY_HOSTNAME"

ORAS_ANNOTATIONS=(--annotation "$META_ANNOTATION_KEY=$PACKAGE_METADATA")

# Standard OCI annotation. Registries that support it (GitHub Container Registry,
# GitLab, Harbor, ...) use it to associate the artifact with its source repository.
# The caller supplies the URL so no particular repository host is assumed.
if [ -n "${IMAGE_SOURCE_URL:-}" ]; then
    ORAS_ANNOTATIONS+=(--annotation "org.opencontainers.image.source=${IMAGE_SOURCE_URL}")
fi

# Change to the directory containing the zip file
pushd "$PACKAGE_ZIP_DIR" >/dev/null
# Push the zip file with annotations using only the filename (relative path)
oras push "$FULL_IMAGE_NAME" \
    "${PACKAGE_ZIP_NAME}:application/zip" \
    "${ORAS_ANNOTATIONS[@]}"
popd >/dev/null
