# wp-package-deploy-oras

A tool to deploy WordPress plugins or themes to OCI-compatible registries using ORAS (OCI Registry As Storage).

## Environment Variables

### Required Environment Variables

- **WP_PACKAGE_SLUG**: Slug of the WordPress package (e.g. `my-plugin` or `my-theme`)
- **WP_PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme'
- **WP_PACKAGE_PATH**: Directory where the WordPress package is located (e.g. `/github/workspace`)
- **WP_PACKAGE_HEADERS_FILE**: File path to the WordPress file containing the package headers (e.g. `/github/workspace/my-plugin/my-plugin.php` or `/github/workspace/my-theme/style.css`)
- **REGISTRY_USERNAME**: Username for authentication with the registry
- **REGISTRY_PASSWORD**: Password for authentication with the registry
- **IMAGE_NAME**: Image name with optional tag (e.g. `ghcr.io/username/my-plugin:v1.0.0`)


### Optional Environment Variables

- **META_ANNOTATION_KEY**: Prefix for annotation keys (default: `org.codekaizen-github.wp-package-deploy-oras`)
- **PHP_MEMORY_LIMIT**: Memory limit for PHP when parsing package metadata (default: `512M`)
- **WP_PACKAGE_TESTED**: Tested up to WordPress version (e.g. `6.2`)
- **WP_PACKAGE_STABLE**: Stable tag/version of the package (e.g. `1.0.0`)
- **WP_PACKAGE_LICENSE**: License of the package (e.g. `GPLv2 or later`)
- **WP_PACKAGE_LICENSE_URL**: URL to the license (e.g. `https://www.gnu.org/licenses/gpl-2.0.html`)
- **WP_PACKAGE_DESCRIPTION**: Description of the package
- **WP_PACKAGE_SECTIONS**: Sections of the package in JSON format (e.g. `{"section1":"Section 1","section2":"Section 2"}`)
