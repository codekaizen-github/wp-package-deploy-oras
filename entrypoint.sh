#!/bin/bash
set -e
set -x

# Required ENV variables:
#   PLUGIN_ZIP_PATH: Path to the plugin zip file
#   ANNOTATION_PREFIX: Prefix for annotation keys (default: org.codekaizen-github.wordpress-plugin-registry-oras)
#   REGISTRY_USERNAME: Registry username
#   REGISTRY_PASSWORD: Registry password
#   IMAGE_NAME: Image name (e.g. ghcr.io/codekaizen-github/wp-github-gist-block:v1)

ANNOTATION_PREFIX="${ANNOTATION_PREFIX:-org.codekaizen-github.wordpress-plugin-registry-oras}"

if [ -z "$PLUGIN_ZIP_PATH" ]; then
    echo "PLUGIN_ZIP_PATH env variable is required!" >&2
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
PLUGIN_META=$(php "$SCRIPT_DIR/src/get_plugin_metadata.php" "$PLUGIN_ZIP_PATH")

# Prepare annotation args for oras
ANNOTATION_ARGS=()
for key in $(echo "$PLUGIN_META" | jq -r 'keys[]'); do
    value=$(echo "$PLUGIN_META" | jq -r --arg k "$key" '.[$k]')
    # Only add if value is not empty
    if [ -n "$value" ] && [ "$value" != "null" ]; then
        ANNOTATION_ARGS+=("--annotation" "$ANNOTATION_PREFIX.$key=$value")
    fi
done

echo "Pushing plugin zip file with annotations: ${ANNOTATION_ARGS[*]}"

# Login to registry
oras login --username "$REGISTRY_USERNAME" --password "$REGISTRY_PASSWORD" "$(echo $IMAGE_NAME | cut -d'/' -f1)"

# Push the zip file with annotations
oras push "$IMAGE_NAME" \
    "${PLUGIN_ZIP_PATH}:application/zip" \
    "${ANNOTATION_ARGS[@]}"
