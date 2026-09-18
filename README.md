# wp-package-deploy-oras

A tool to deploy WordPress plugins or themes to OCI-compatible registries using ORAS (OCI Registry As Storage).

## Environment Variables

### Required Environment Variables

- **ORASHUB_BASE_URL**: Base URL for the ORAS hub server (e.g. `https://orashub.example.com`) - used for constructing download URLs
- **IMAGE_REGISTRY_HOSTNAME**: Registry hostname (e.g. `registry.example.com`)
- **IMAGE_REGISTRY_USERNAME**: Username for authentication with the registry
- **IMAGE_REGISTRY_PASSWORD** *or* **IMAGE_REGISTRY_PASSWORD_FILE**: Password / token for the registry. Prefer `IMAGE_REGISTRY_PASSWORD_FILE` for multi-line secrets (path to a file whose contents are the password). The entrypoint logs in with `oras login --password-stdin` so credentials are not expanded on the command line or leaked by shell xtrace
- **IMAGE_REPOSITORY**: Repository path (e.g. `username/my-plugin`)
- **IMAGE_TAG**: Version tag for the image (e.g. `v1.0.0`)
- **WP_PACKAGE_SLUG**: Slug of the WordPress package (e.g. `my-plugin` or `my-theme`)
- **WP_PACKAGE_TYPE**: Type of WordPress package - either 'plugin' or 'theme'
- **WP_PACKAGE_HEADERS_FILE**: Relative (to `WP_PACKAGE_PATH`) file path to the WordPress file containing the package headers (e.g. `my-plugin.php` or `./my-plugin.php` or `style.css` or `./style.css`)

Auth is standard OCI username/password. No registry-specific tooling is required inside the image.

### Optional Environment Variables

- **IMAGE_SOURCE_URL**: Web URL of the source repository the package was built from (e.g. `https://github.com/username/my-plugin`, `https://gitlab.com/username/my-plugin`, `https://git.example.com/username/my-plugin`). Pushed as the standard `org.opencontainers.image.source` annotation. Registries that read it use it to link the published package to its repository; omit it and no source annotation is written
- **ORASHUB_DOWNLOAD_REGISTRY**: Registry (or named alias) segment in the constructed download URL. Defaults to `IMAGE_REGISTRY_HOSTNAME`. Use when the client-facing ORASHub path differs from the OCI push host
- **ORASHUB_DOWNLOAD_REPOSITORY**: Repository path segment(s) in the constructed download URL. Defaults to `IMAGE_REPOSITORY`. Use with `ORASHUB_DOWNLOAD_REGISTRY` when the client path differs from the push repository
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
