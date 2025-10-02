# wp-package-deploy-oras

A tool to deploy WordPress plugins or themes to OCI-compatible registries using ORAS (OCI Registry As Storage).

## Environment Variables

### Required Environment Variables

- **WP_PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme'
- **WP_PACKAGE_SLUG**: Slug of the WordPress package (e.g. `my-plugin` or `my-theme`)
- **WP_PACKAGE_PATH**: Directory where the WordPress package is located (e.g. `/github/workspace`)
- **WP_PACKAGE_HEADERS_FILE**: File path to the WordPress file containing the package headers (e.g. `/github/workspace/my-plugin/my-plugin.php` or `/github/workspace/my-theme/style.css`)
- **REGISTRY_USERNAME**: Username for authentication with the registry
- **REGISTRY_PASSWORD**: Password for authentication with the registry
- **IMAGE_NAME**: Image name with optional tag (e.g. `ghcr.io/username/my-plugin:v1.0.0`)


### Optional Environment Variables

- **ANNOTATION_PREFIX**: Prefix for annotation keys (default: `org.codekaizen-github.wp-package-deploy-oras`)
- **PHP_MEMORY_LIMIT**: Memory limit for PHP when parsing package metadata (default: `512M`)
- **PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme' (default: `plugin`)
- **PARSE_README**: Whether to parse the readme file for additional metadata (default: `false`)
- **WP_PACKAGE_METADATA_OVERRIDES**: Optional JSON string. If provided, its contents will be deep merged into the generated package metadata, overriding any existing values. This allows you to customize or supplement the metadata before it is pushed to the registry. Example: `'{"Version": "2.0.0", "Author": "Custom Author"}'`
