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
if [ -z "$WP_PACKAGE_PATH" ]; then
    echo "WP_PACKAGE_PATH env variable is required!" >&2
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

# Get the directory of this script for relative references
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Parse plugin metadata using wp-package-parser script
PACKAGE_METADATA=$(php -d memory_limit="${PHP_MEMORY_LIMIT:-512M}" "$SCRIPT_DIR/bin/get-package-metadata")

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
    --annotation "$ANNOTATION_PREFIX.wp-package-metadata=$PACKAGE_METADATA"

popd >/dev/null
