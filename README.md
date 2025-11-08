# wp-package-deploy-oras

A tool to deploy WordPress plugins or themes to OCI-compatible registries using ORAS (OCI Registry As Storage).

## Environment Variables

### Required Environment Variables

- **ORASHUB_BASE_URL**: Base URL for the ORAS hub server (e.g. `https://orashub.example.com`) - used for constructing download URLs
- **IMAGE_REGISTRY_HOST**: Registry hostname (e.g. `ghcr.io`)
- **IMAGE_REGISTRY_USERNAME**: Username for authentication with the registry
- **IMAGE_REGISTRY_PASSWORD**: Password for authentication with the registry
- **IMAGE_REPOSITORY**: Repository path (e.g. `username/my-plugin`)
- **IMAGE_TAG**: Version tag for the image (e.g. `v1.0.0`)
- **WP_PACKAGE_SLUG**: Slug of the WordPress package (e.g. `my-plugin` or `my-theme`)
- **WP_PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme'
- **WP_PACKAGE_HEADERS_FILE**: Relative (to `WP_PACKAGE_PATH`) file path to the WordPress file containing the package headers (e.g. `my-plugin.php` or `./my-plugin.php` or `style.css` or `./style.css`)


### Optional Environment Variables

- **META_ANNOTATION_KEY**: Prefix for annotation keys (default: `org.codekaizen-github.wp-package-deploy-oras`)
- **PHP_MEMORY_LIMIT**: Memory limit for PHP when parsing package metadata (default: `512M`)
- **WP_PACKAGE_PATH**: Directory where the WordPress package is located - defaults to current working directory (`/package` in the Docker container)
- **WP_PACKAGE_TESTED**: Tested up to WordPress version (e.g. `6.2`)
- **WP_PACKAGE_STABLE**: Stable tag/version of the package (e.g. `1.0.0`)
- **WP_PACKAGE_LICENSE**: License of the package (e.g. `GPLv2 or later`)
- **WP_PACKAGE_LICENSE_URL**: URL to the license (e.g. `https://www.gnu.org/licenses/gpl-2.0.html`)
- **WP_PACKAGE_DESCRIPTION**: Description of the package
- **WP_PACKAGE_SECTIONS**: Sections of the package in JSON format (e.g. `{"section1":"Section 1","section2":"Section 2"}`)
- **WP_PACKAGE_ICONS**: Icons of the package in JSON format (e.g. `{"1x":"https://example.com/icon-128x128.png","2x":"https://example.com/icon-256x256.png"}`)
- **WP_PACKAGE_BANNERS**: Banners of the package in JSON format (e.g. `{"1x":"https://example.com/banner-772x250.png","2x":"https://example.com/banner-1544x500.png"}`)
- **WP_PACKAGE_BANNERS_RTL**: RTL Banners of the package in JSON format (e.g. `{"1x":"https://example.com/banner-rtl-772x250.png","2x":"https://example.com/banner-rtl-1544x500.png"}`)
