<?php
/**
 * Local Theme Package Meta Provider
 *
 * Provides metadata for WordPress themes installed locally.
 *
 * @package  CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;

use CodekaizenGithub\WPPackageDeployORAS\Contract\Value\PackageMeta\CommonPackageMetaValueContract;

/**
 * Provider for local WordPress theme package metadata.
 *
 * Reads and parses metadata from theme files in the local filesystem.
 *
 * @since 1.0.0
 */
class StandardThemePackageMetaValue implements ThemePackageMetaValueContract {

	/**
	 *
	 * Unknown.
	 *
	 * @var ThemePackageMetaValueContract
	 */
	protected ThemePackageMetaValueContract $provider;

	/**
	 * Undocumented variable
	 *
	 * @var CommonPackageMetaValueContract
	 */
	protected CommonPackageMetaValueContract $environmentProvider;

	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaValueContract  $provider Package Meta Provider.
	 * @param CommonPackageMetaValueContract $environmentProvider Environment Provider.
	 */
	public function __construct(
		ThemePackageMetaValueContract $provider,
		CommonPackageMetaValueContract $environmentProvider
	) {
		$this->provider            = $provider;
		$this->environmentProvider = $environmentProvider;
	}
	/**
	 * Gets the name of the theme.
	 *
	 * @return string The theme name.
	 */
	public function getName(): string {
		return $this->provider->getName();
	}
	/**
	 * Gets the full slug, including any directory prefix and file extension.
	 *
	 * @return string The full slug.
	 */
	public function getFullSlug(): string {
		return $this->provider->getFullSlug();
	}
	/**
	 * Gets the short slug, minus any prefix. Should not contain a "/".
	 *
	 * @return string The short slug.
	 */
	public function getShortSlug(): string {
		return $this->provider->getShortSlug();
	}
	/**
	 * Gets the version of the theme.
	 *
	 * @return ?string The theme version or null if not available.
	 */
	public function getVersion(): ?string {
		return $this->provider->getVersion();
	}
	/**
	 * Gets the theme URI.
	 *
	 * @return ?string The theme URI or null if not available.
	 */
	public function getViewURL(): ?string {
		return $this->provider->getViewURL();
	}
	/**
	 * Gets the download URL for the theme.
	 *
	 * @return ?string The theme download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		return $this->environmentProvider->getDownloadURL();
	}
	/**
	 * Gets the WordPress version the theme has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		return $this->environmentProvider->getTested();
	}
	/**
	 * Gets the stable version of the theme.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		return $this->environmentProvider->getStable();
	}
	/**
	 * Gets the theme tags.
	 *
	 * @return string[] Array of theme tags.
	 */
	public function getTags(): array {
		return $this->provider->getTags();
	}
	/**
	 * Gets the theme author.
	 *
	 * @return ?string The theme author or null if not available.
	 */
	public function getAuthor(): ?string {
		return $this->provider->getAuthor();
	}
	/**
	 * Gets the theme author's URL.
	 *
	 * @return ?string The theme author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		return $this->provider->getAuthorURL();
	}
	/**
	 * Gets the theme license.
	 *
	 * @return ?string The theme license or null if not available.
	 */
	public function getLicense(): ?string {
		return $this->environmentProvider->getLicense();
	}
	/**
	 * Gets the theme license URL.
	 *
	 * @return ?string The theme license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		return $this->environmentProvider->getLicenseURL();
	}
	/**
	 * Gets the short description of the theme.
	 *
	 * @return ?string The theme short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		return $this->provider->getShortDescription();
	}
	/**
	 * Gets the full description of the theme.
	 *
	 * @return ?string The theme full description or null if not available.
	 */
	public function getDescription(): ?string {
		return $this->environmentProvider->getDescription();
	}
	/**
	 * Gets the minimum WordPress version required by the theme.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		return $this->provider->getRequiresWordPressVersion();
	}
	/**
	 * Gets the minimum PHP version required by the theme.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		return $this->provider->getRequiresPHPVersion();
	}
	/**
	 * Gets the text domain used by the theme for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		return $this->provider->getTextDomain();
	}
	/**
	 * Gets the domain path for the theme's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		return $this->provider->getDomainPath();
	}
	/**
	 * Gets the icons associated with the plugin.
	 *
	 * @return array<string,string> An array of icon URLs, keyed by icon size.
	 */
	public function getIcons(): array {
		return $this->environmentProvider->getIcons();
	}
	/**
	 * Gets the banners associated with the plugin.
	 *
	 * @return array<string,string> An array of banner URLs, keyed by banner size.
	 */
	public function getBanners(): array {
		return $this->environmentProvider->getBanners();
	}
	/**
	 * Gets the right-to-left (RTL) banners associated with the plugin.
	 *
	 * @return array<string,string> An array of RTL banner URLs, keyed by banner size.
	 */
	public function getBannersRTL(): array {
		return $this->environmentProvider->getBannersRTL();
	}
	/**
	 * Gets the template for the theme.
	 *
	 * @return ?string The template or null if not specified.
	 */
	public function getTemplate(): ?string {
		return $this->provider->getTemplate();
	}
	/**
	 * Gets the status for the theme.
	 *
	 * @return ?string The status or null if not specified.
	 */
	public function getStatus(): ?string {
		return $this->provider->getStatus();
	}
	/**
	 * Undocumented function
	 *
	 * @return mixed
	 */
	public function jsonSerialize(): mixed {
		return [
			'name'                     => $this->getName(),
			'fullSlug'                 => $this->getFullSlug(),
			'shortSlug'                => $this->getShortSlug(),
			'viewUrl'                  => $this->getViewUrl(),
			'version'                  => $this->getVersion(),
			'downloadUrl'              => $this->getDownloadUrl(),
			'tested'                   => $this->getTested(),
			'stable'                   => $this->getStable(),
			'tags'                     => $this->getTags(),
			'author'                   => $this->getAuthor(),
			'authorUrl'                => $this->getAuthorUrl(),
			'license'                  => $this->getLicense(),
			'licenseUrl'               => $this->getLicenseUrl(),
			'description'              => $this->getDescription(),
			'shortDescription'         => $this->getShortDescription(),
			'requiresWordPressVersion' => $this->getRequiresWordPressVersion(),
			'requiresPHPVersion'       => $this->getRequiresPHPVersion(),
			'textDomain'               => $this->getTextDomain(),
			'domainPath'               => $this->getDomainPath(),
			'icons'                    => $this->getIcons(),
			'banners'                  => $this->getBanners(),
			'bannersRtl'               => $this->getBannersRTL(),
			'template'                 => $this->getTemplate(),
			'status'                   => $this->getStatus(),
		];
	}
}
