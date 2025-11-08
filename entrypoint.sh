#!/bin/bash
set -e
set -x

# Required ENV variables:
#   IMAGE_REGISTRY_USERNAME: Registry username
#   IMAGE_REGISTRY_PASSWORD: Registry password
#   IMAGE_REGISTRY_HOSTNAME: Registry name (e.g. ghcr.io)
#   IMAGE_REPOSITORY: Repository path (e.g. codekaizen-github/wp-github-gist-block)
#   IMAGE_TAG: Image version tag (e.g. v1)

if [ -z "$WP_PACKAGE_SLUG" ]; then
    echo "WP_PACKAGE_SLUG env variable is required!" >&2
    exit 1
fi
if [ -z "${WP_PACKAGE_HEADERS_FILE:-}" ]; then
    echo "WP_PACKAGE_HEADERS_FILE env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_REGISTRY_USERNAME" ]; then
    echo "IMAGE_REGISTRY_USERNAME env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_REGISTRY_PASSWORD" ]; then
    echo "IMAGE_REGISTRY_PASSWORD env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_REGISTRY_HOSTNAME" ]; then
    echo "IMAGE_REGISTRY_HOSTNAME env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_REPOSITORY" ]; then
    echo "IMAGE_REPOSITORY env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_TAG" ]; then
    echo "IMAGE_TAG env variable is required!" >&2
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
    "$SCRIPT_DIR/bin/get-package-metadata" 2>/dev/null || echo '{}')

if [ -z "$PACKAGE_METADATA" ] || [ "$PACKAGE_METADATA" = "{}" ]; then
    echo "Failed to parse package metadata or no metadata found. Using empty JSON object." >&2
    PACKAGE_METADATA="{}"
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

# Login to registry
oras login --username "$IMAGE_REGISTRY_USERNAME" --password "$IMAGE_REGISTRY_PASSWORD" "$IMAGE_REGISTRY_HOSTNAME"

# Change to the directory containing the zip file
pushd "$PACKAGE_ZIP_DIR" >/dev/null
# Push the zip file with annotations using only the filename (relative path)
oras push "$FULL_IMAGE_NAME" \
    "${PACKAGE_ZIP_NAME}:application/zip" \
    --annotation "$META_ANNOTATION_KEY=$PACKAGE_METADATA"
popd >/dev/null
