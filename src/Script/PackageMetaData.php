<?php
/**
 * Script to get the package meta as JSON.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
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
		$factory  = new PackageMetaJSONSerializableFactory( $logger );
		$provider = $factory->create();
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		echo json_encode( $provider );
	}
}

if ( php_sapi_name() === 'cli' && realpath( $argv[0] ) === __FILE__ ) {
	PackageMetaData::main();
}
