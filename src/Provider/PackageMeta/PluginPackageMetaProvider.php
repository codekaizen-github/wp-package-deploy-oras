<?php
/**
 * Unknown.
 *
 * @package  CodekaizenGithub\WPPackageDeployORAS
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodekaizenGithub\WPPackageDeployORAS\Contract\PackageMeta\CommonEnvironmentPackageMetaContract;

/**
 * Provider for local WordPress plugin package metadata.
 *
 * Reads and parses metadata from plugin files in the local filesystem.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProvider implements PluginPackageMetaContract {

	/**
	 *
	 * Unknown.
	 *
	 * @var PluginPackageMetaContract
	 */
	protected PluginPackageMetaContract $provider;

	/**
	 * Undocumented variable
	 *
	 * @var CommonEnvironmentPackageMetaContract
	 */
	protected CommonEnvironmentPackageMetaContract $environmentProvider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaContract            $provider Package Meta Provider.
	 * @param CommonEnvironmentPackageMetaContract $environmentProvider Environment Provider.
	 */
	public function __construct(
		PluginPackageMetaContract $provider,
		CommonEnvironmentPackageMetaContract $environmentProvider
	) {
		$this->provider            = $provider;
		$this->environmentProvider = $environmentProvider;
	}
	/**
	 * Gets the name of the plugin.
	 *
	 * @return string The plugin name.
	 */
	public function getName(): string {
		return $this->provider->getName();
	}
	/**
	 * Full slug, including any directory prefix and any file extension like .php - may contain a "/".
	 *
	 * @return string
	 */
	public function getFullSlug(): string {
		return $this->provider->getFullSlug();
	}
	/**
	 * Slug minus any prefix. Should not contain a "/".
	 *
	 * @return string
	 */
	public function getShortSlug(): string {
		return $this->provider->getShortSlug();
	}
	/**
	 * Gets the version of the plugin.
	 *
	 * @return ?string The plugin version or null if not available.
	 */
	public function getVersion(): ?string {
		return $this->provider->getVersion();
	}
	/**
	 * Gets the plugin URI.
	 *
	 * @return ?string The plugin URI or null if not available.
	 */
	public function getViewURL(): ?string {
		return $this->provider->getViewURL();
	}
	/**
	 * Gets the download URL for the plugin.
	 *
	 * @return ?string The plugin download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		return $this->provider->getDownloadURL();
	}
	/**
	 * Gets the WordPress version the plugin has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		return $this->environmentProvider->getTested();
	}
	/**
	 * Gets the stable version of the plugin.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		return $this->environmentProvider->getStable();
	}
	/**
	 * Gets the plugin tags.
	 *
	 * @return string[] Array of plugin tags.
	 */
	public function getTags(): array {
		return $this->provider->getTags();
	}
	/**
	 * Gets the plugin author.
	 *
	 * @return ?string The plugin author or null if not available.
	 */
	public function getAuthor(): ?string {
		return $this->provider->getAuthor();
	}
	/**
	 * Gets the plugin author's URL.
	 *
	 * @return ?string The plugin author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		return $this->provider->getAuthorURL();
	}
	/**
	 * Gets the plugin license.
	 *
	 * @return ?string The plugin license or null if not available.
	 */
	public function getLicense(): ?string {
		return $this->environmentProvider->getLicense();
	}
	/**
	 * Gets the plugin license URL.
	 *
	 * @return ?string The plugin license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		return $this->environmentProvider->getLicenseURL();
	}
	/**
	 * Gets the short description of the plugin.
	 *
	 * @return ?string The plugin short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		return $this->provider->getShortDescription();
	}
	/**
	 * Gets the full description of the plugin.
	 *
	 * @return ?string The plugin full description or null if not available.
	 */
	public function getDescription(): ?string {
		return $this->environmentProvider->getDescription();
	}
	/**
	 * Gets the minimum WordPress version required by the plugin.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		return $this->provider->getRequiresWordPressVersion();
	}
	/**
	 * Gets the minimum PHP version required by the plugin.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		return $this->provider->getRequiresPHPVersion();
	}
	/**
	 * Gets the text domain used by the plugin for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		return $this->provider->getTextDomain();
	}
	/**
	 * Gets the domain path for the plugin's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		return $this->provider->getDomainPath();
	}
	/**
	 * Gets the list of plugins that this plugin requires.
	 *
	 * @return string[] Array of required plugin identifiers.
	 */
	public function getRequiresPlugins(): array {
		return $this->provider->getRequiresPlugins();
	}
	/**
	 * Gets the sections of the plugin description.
	 *
	 * @return array<string,string> Associative array of section names and their content.
	 */
	public function getSections(): array {
		return $this->environmentProvider->getSections();
	}
	/**
	 * Determines if this plugin is a network-only plugin.
	 *
	 * @return boolean True if this is a network plugin, false otherwise.
	 */
	public function getNetwork(): bool {
		return $this->provider->getNetwork();
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
			'requiresPlugins'          => $this->getRequiresPlugins(),
			'sections'                 => $this->getSections(),
			'network'                  => $this->getNetwork(),
		];
	}
}
