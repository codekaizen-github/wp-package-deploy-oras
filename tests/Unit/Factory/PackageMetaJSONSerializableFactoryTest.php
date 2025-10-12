<?php
/**
 * Tests.
 * phpcs:ignoreFile WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv Ignoring.
 * @package CodeKaizen\WPPackageDeployORASTests\Unit\Factory
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Factory;

use CodeKaizen\WPPackageDeployORASTests\Helper\FixturePathHelper;
use CodekaizenGithub\WPPackageDeployORAS\Factory\PackageMetaJSONSerializableFactory;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\PluginPackageMetaProvider;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\ThemePackageMetaProvider;
use JsonSerializable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class PackageMetaJSONSerializableFactoryTest extends TestCase {

	/**
	 * Original Vars.
	 *
	 * @var array<string|false> $originalEnvVars Vars.
	 */
	private array $originalEnvVars = [];

	/**
	 * Save original environment variables and set up provider before each test
	 */
	protected function setUp(): void {
		parent::setUp();
		$envVars = [
			'WP_PACKAGE_SLUG',
			'WP_PACKAGE_TYPE',
			'WP_PACKAGE_HEADERS_FILE',
		];
		foreach ( $envVars as $var ) {
			$this->originalEnvVars[ $var ] = getenv( $var );
			putenv( $var );
		}
	}

	/**
	 * Restore original environment after each test
	 */
	protected function tearDown(): void {
		foreach ( $this->originalEnvVars as $key => $value ) {
			if ( false === $value ) {
				putenv( $key );
			} else {
				putenv( "$key=$value" );
			}
		}
		parent::tearDown();
	}
	/**
	 * Test all methods with valid values
	 */
	public function testAllPluginValid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$logger = Mockery::mock(LoggerInterface::class);
		$factory = new PackageMetaJSONSerializableFactory($logger);
		$provider = $factory->create();
		$this->assertInstanceOf(PluginPackageMetaProvider::class, $provider);
	}
	/**
	 * Test all methods with valid values
	 */
	public function testAllThemeValid(): void {
		putenv('WP_PACKAGE_SLUG=my-theme');
		putenv( 'WP_PACKAGE_TYPE=theme' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$logger = Mockery::mock(LoggerInterface::class);
		$factory = new PackageMetaJSONSerializableFactory($logger);
		$provider = $factory->create();
		$this->assertInstanceOf(ThemePackageMetaProvider::class, $provider);
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testPackageTypeInvalid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=asdf' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$logger = Mockery::mock(LoggerInterface::class);
		$factory = new PackageMetaJSONSerializableFactory($logger);
		$this->expectException(UnexpectedValueException::class);
		$factory->create();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testFilePathInvalid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/fake.txt' );
		$logger = Mockery::mock(LoggerInterface::class);
		$factory = new PackageMetaJSONSerializableFactory($logger);
		$this->expectException(UnexpectedValueException::class);
		$factory->create();
	}
		/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testPackageSlugInvalid(): void {
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/fake.txt' );
		$logger = Mockery::mock(LoggerInterface::class);
		$factory = new PackageMetaJSONSerializableFactory($logger);
		$this->expectException(UnexpectedValueException::class);
		$factory->create();
	}
}
