<?php
/**
 * Factory.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS\Factory
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Factory;

use CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryV1;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryV1;
use CodekaizenGithub\WPPackageDeployORAS\Contract\Factory\JSONSerializableFactoryContract;
use CodekaizenGithub\WPPackageDeployORAS\Parser\Slug\ParentAndFilePathSlugParser;
use CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\CommonEnvironmentPackageMetaValue;
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
			// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar, WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException(
				'WP_PACKAGE_HEADERS_FILE must be valid file path of type string.
				Received: ' . print_r( $filePath, true )
			);
			// phpcs:enable Squiz.Commenting.InlineComment.InvalidEndChar, WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}
		/**
		* Value will have been validated.
		*
		* @var string $filePath File Path.
		*/
		$slugParser          = new ParentAndFilePathSlugParser( $parentSlug, $filePath );
		$environmentProvider = new CommonEnvironmentPackageMetaValue();
		switch ( $packageType ) {
			case 'plugin':
				$serviceFactory = new PluginPackageMetaValueServiceFactoryV1(
					$filePath,
					$slugParser,
					$this->logger
				);
				$service        = $serviceFactory->create();
				$value          = $service->getPackageMeta();
				return new PluginPackageMetaProvider( $value, $environmentProvider );
			case 'theme':
				$serviceFactory = new ThemePackageMetaValueServiceFactoryV1(
					$filePath,
					$slugParser,
					$this->logger
				);
				$service        = $serviceFactory->create();
				$value          = $service->getPackageMeta();
				return new ThemePackageMetaProvider( $value, $environmentProvider );
			default:
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
				throw new UnexpectedValueException( 'Unexpected package type: ' . $packageType );
		}
	}
}
