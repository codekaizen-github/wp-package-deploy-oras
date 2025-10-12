<?php
/**
 * Script to get the package meta as JSON.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS\Script
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Script;

use CodekaizenGithub\WPPackageDeployORAS\Factory\PackageMetaJSONSerializableFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

use UnexpectedValueException;

/**
 * Undocumented class
 */
class PackageMetaDataJSONFetcher {
	/**
	 * Undocumented function
	 *
	 * @return string
	 * @throws UnexpectedValueException On unexpected values.
	 */
	public static function main(): string {

		$logger = new Logger( 'cli' );
		$logger->pushHandler( new StreamHandler( 'php://stdout', Level::Info ) );
		$logger->pushHandler( new StreamHandler( 'php://stderr', Level::Warning ) );
		$factory  = new PackageMetaJSONSerializableFactory( $logger );
		$provider = $factory->create();
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$json = json_encode( $provider );
		if ( ! is_string( $json ) ) {
			throw new UnexpectedValueException( 'Unable to serialize provider data to JSON' );
		}
		return $json;
	}
}
