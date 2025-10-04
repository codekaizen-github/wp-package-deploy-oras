#!/bin/bash
set -e
set -x

# Required ENV variables:
#   REGISTRY_USERNAME: Registry username
#   REGISTRY_PASSWORD: Registry password
#   IMAGE_NAME: Image name (e.g. ghcr.io/codekaizen-github/wp-github-gist-block:v1)

if [ -z "$WP_PACKAGE_SLUG" ]; then
    echo "WP_PACKAGE_SLUG env variable is required!" >&2
    exit 1
fi
if [ -z "${WP_PACKAGE_HEADERS_FILE:-}" ]; then
    echo "WP_PACKAGE_HEADERS_FILE env variable is required!" >&2
    exit 1
fi
if [ -z "$REGISTRY_USERNAME" ]; then
    echo "REGISTRY_USERNAME env variable is required!" >&2
    exit 1
fi
if [ -z "$REGISTRY_PASSWORD" ]; then
    echo "REGISTRY_PASSWORD env variable is required!" >&2
    exit 1
fi
if [ -z "$IMAGE_NAME" ]; then
    echo "IMAGE_NAME env variable is required!" >&2
    exit 1
fi

# Default path to the WordPress package files (mounted volume)
WP_PACKAGE_PATH="${WP_PACKAGE_PATH:-/package}"

# The headers file should be relative to the package path
WP_PACKAGE_HEADERS_FILE="${WP_PACKAGE_PATH}/${WP_PACKAGE_HEADERS_FILE}"

META_ANNOTATION_KEY="${META_ANNOTATION_KEY:-wp-package-metadata}"

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

# Login to registry
oras login --username "$REGISTRY_USERNAME" --password "$REGISTRY_PASSWORD" "$(echo "$IMAGE_NAME" | cut -d'/' -f1)"

# Create a zip file of the package
PACKAGE_ZIP_DIR=$(mktemp -d)
PACKAGE_ZIP_NAME="${WP_PACKAGE_SLUG}.zip"
PACKAGE_ZIP_FILE="${PACKAGE_ZIP_DIR}/${PACKAGE_ZIP_NAME}"
zip -r "$PACKAGE_ZIP_FILE" "$WP_PACKAGE_PATH"

# Change to the directory containing the zip file
pushd "$PACKAGE_ZIP_DIR" >/dev/null

# Push the zip file with annotations using only the filename (relative path)
oras push "$IMAGE_NAME" \
    "${PACKAGE_ZIP_NAME}:application/zip" \
    --annotation "$META_ANNOTATION_KEY=$PACKAGE_METADATA"

popd >/dev/null
