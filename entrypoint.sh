#!/bin/bash
set -e
set -x

# Required ENV variables:
#   PACKAGE_ZIP_PATH: Path to the plugin or theme zip file
#   ANNOTATION_PREFIX: Prefix for annotation keys (default: org.codekaizen-github.wp-package-deploy-oras)
#   REGISTRY_USERNAME: Registry username
#   REGISTRY_PASSWORD: Registry password
#   IMAGE_NAME: Image name (e.g. ghcr.io/codekaizen-github/wp-github-gist-block:v1)

ANNOTATION_PREFIX="${ANNOTATION_PREFIX:-org.codekaizen-github.wp-package-deploy-oras}"

if [ -z "$PACKAGE_ZIP_PATH" ]; then
    echo "PACKAGE_ZIP_PATH env variable is required!" >&2
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
PACKAGE_METADATA=$(php -d memory_limit="${PHP_MEMORY_LIMIT:-512M}" "$SCRIPT_DIR/src/get_package_metadata.php")

# Login to registry
oras login --username "$REGISTRY_USERNAME" --password "$REGISTRY_PASSWORD" "$(echo "$IMAGE_NAME" | cut -d'/' -f1)"

# Get directory and filename from PACKAGE_ZIP_PATH
PACKAGE_ZIP_DIR="$(dirname "$PACKAGE_ZIP_PATH")"
PACKAGE_ZIP_FILE="$(basename "$PACKAGE_ZIP_PATH")"

# Change to the directory containing the zip file
pushd "$PACKAGE_ZIP_DIR" >/dev/null

# Push the zip file with annotations using only the filename (relative path)
oras push "$IMAGE_NAME" \
    "${PACKAGE_ZIP_FILE}:application/zip" \
    --annotation "$ANNOTATION_PREFIX.wp-package-metadata=$PACKAGE_METADATA"

popd >/dev/null
