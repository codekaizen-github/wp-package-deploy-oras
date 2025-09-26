<?php
/**
 * Script to get the package meta as JSON.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Script;

use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class PackageMetaData {
	/**
	 * Undocumented function
	 *
	 * @return void
	 * @throws UnexpectedValueException On unexpected values.
	 */
	public static function main(): void {

		$logger = new Logger( 'cli' );
		$logger->pushHandler( new StreamHandler( 'php://stdout', Level::Info ) );
		$logger->pushHandler( new StreamHandler( 'php://stderr', Level::Warning ) );
		$filePath    = getenv( 'WP_PACKAGE_FILE_WITH_PACKAGE_HEADERS_FILEPATH' );
		$packageType = getenv( 'WP_PACKAGE_TYPE' );
		switch ( $packageType ) {
			case 'plugin':
				$provider = new PluginPackageMetaProviderFactoryV1( $filePath, $logger );
				break;
			case 'theme':
				$provider = new ThemePackageMetaProviderFactoryV1( $filePath, $logger );
				break;
			case false:
				throw new UnexpectedValueException( 'WP_PACKAGE_TYPE is required' );
			default:
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
				throw new UnexpectedValueException( 'Invalid WP_PACKAGE_TYPE: ' . $packageType );
		}

		if ( null === $provider ) {
			throw new UnexpectedValueException( 'Unable to construct provider' );
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		echo json_encode( $provider );
	}
}

if ( php_sapi_name() === 'cli' && realpath( $argv[0] ) === __FILE__ ) {
	PackageMetaData::main();
}
