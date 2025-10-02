<?php
/**
 * Environment SlugParser
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Parser\Slug;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;

/**
 * Undocumented class
 */
class ParentAndFilePathSlugParser implements SlugParserContract {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $parentSlug;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $packageFilePath;
	/**
	 * Undocumented function
	 *
	 * @param string $parentSlug The top level slug.
	 * @param string $packageFilePath The path to file with package headers.
	 */
	public function __construct( string $parentSlug, string $packageFilePath ) {
		$this->parentSlug      = $parentSlug;
		$this->packageFilePath = $packageFilePath;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getFullSlug(): string {
		$basename = basename( $this->packageFilePath );
		// $directory         = dirname( $this->packageFilePath );
		// $directoryBasename = pathinfo( $directory, PATHINFO_BASENAME );
		// Includes any .php extension.
		$fullSlug = $this->parentSlug . '/' . $basename;
		return $fullSlug;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getShortSlug(): string {
		// $basename = basename( $this->packageFilePath );
		// Remove extension (if any) to get just the filename.
		// $shortSlug = pathinfo( $basename, PATHINFO_FILENAME );
		// return $shortSlug;
		return $this->parentSlug;
	}
}
