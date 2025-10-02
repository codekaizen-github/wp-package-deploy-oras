<?php
/**
 * Factory.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Factory;

use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1;
use CodekaizenGithub\WPPackageDeployORAS\Contract\Factory\JSONSerializableFactoryContract;
use CodekaizenGithub\WPPackageDeployORAS\Parser\Slug\ParentAndFilePathSlugParser;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\CommonEnvironmentPackageMetaProvider;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\PluginPackageMetaProvider;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\ThemePackageMetaProvider;
use JsonSerializable;
use Psr\Log\LoggerInterface;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class PackageMetaJSONSerializableFactory implements JSONSerializableFactoryContract {
	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Undocumented function
	 *
	 * @param LoggerInterface $logger Logger.
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}
	/**
	 * Undocumented function
	 *
	 * @return JsonSerializable
	 * @throws UnexpectedValueException On missing or invalid value.
	 */
	public function create(): JsonSerializable {
		$parentSlug = getenv( 'WP_PACKAGE_SLUG' );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\StringType(),
					new Rules\NotBlank(),
				)
			)->check( $parentSlug );
		} catch ( ValidationException $e ) {
			throw new UnexpectedValueException( 'WP_PACKAGE_SLUG must be a non-empty string' );
		}
		/**
		* Value will have been validated.
		*
		* @var string $parentSlug Parent Slug.
		*/
		$packageType = getenv( 'WP_PACKAGE_TYPE' );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\StringType(),
					new Rules\ContainsAny( [ 'plugin', 'theme' ], true )
				)
			)->check( $packageType );
		} catch ( ValidationException $e ) {
			throw new UnexpectedValueException( 'WP_PACKAGE_TYPE must be either "plugin" or "theme"' );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var string $packageType Package Type.
		 */
		$filePath = getenv( 'WP_PACKAGE_HEADERS_FILE' );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\StringType(),
					new Rules\File(),
				)
			)->check( $filePath );
		} catch ( ValidationException $e ) {
			throw new UnexpectedValueException(
				'WP_PACKAGE_HEADERS_FILE
			must be valid file path of type string'
			);
		}
		/**
		* Value will have been validated.
		*
		* @var string $filePath File Path.
		*/
		$slugParser          = new ParentAndFilePathSlugParser( $parentSlug, $filePath );
		$environmentProvider = new CommonEnvironmentPackageMetaProvider();
		switch ( $packageType ) {
			case 'plugin':
				$providerFactory = new PluginPackageMetaProviderFactoryV1(
					$filePath,
					$slugParser,
					$this->logger
				);
				return new PluginPackageMetaProvider( $providerFactory->create(), $environmentProvider );
			case 'theme':
				$providerFactory = new ThemePackageMetaProviderFactoryV1(
					$filePath,
					$slugParser,
					$this->logger
				);
				return new ThemePackageMetaProvider( $providerFactory->create(), $environmentProvider );
			default:
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
				throw new UnexpectedValueException( 'Unexpected package type: ' . $packageType );
		}
	}
}
