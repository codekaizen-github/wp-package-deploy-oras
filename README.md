# wp-package-deploy-oras

A tool to deploy WordPress plugins or themes to OCI-compatible registries using ORAS (OCI Registry As Storage).

## Environment Variables

### Required Environment Variables

- **PACKAGE_ZIP_PATH**: Path to the plugin or theme zip file
- **REGISTRY_USERNAME**: Username for authentication with the registry
- **REGISTRY_PASSWORD**: Password for authentication with the registry
- **IMAGE_NAME**: Image name with optional tag (e.g. `ghcr.io/username/my-plugin:v1.0.0`)

### Optional Environment Variables

- **ANNOTATION_PREFIX**: Prefix for annotation keys (default: `org.codekaizen-github.wp-package-deploy-oras`)
- **PHP_MEMORY_LIMIT**: Memory limit for PHP when parsing package metadata (default: `512M`)
- **PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme' (default: `plugin`)
- **PARSE_README**: Whether to parse the readme file for additional metadata (default: `false`)
