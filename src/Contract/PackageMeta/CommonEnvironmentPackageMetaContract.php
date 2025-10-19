<?php
/**
 * Interface.
 *
 * @package  CodekaizenGithub\WPPackageDeployORAS\Contract\PackageMeta
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Contract\PackageMeta;

interface CommonEnvironmentPackageMetaContract {
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getTested(): ?string;
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getStable(): ?string;
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getLicense(): ?string;
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getLicenseURL(): ?string;
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string;
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getSections(): array;
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getIcons(): array;
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getBanners(): array;
	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 */
	public function getBannersRTL(): array;
}
