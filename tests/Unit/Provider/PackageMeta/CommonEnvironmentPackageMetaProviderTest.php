<?php
/**
 * Tests.
 * phpcs:ignoreFile WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv Ignoring.
 * @package CodeKaizen\WPPackageDeployORASTests
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Provider\PackageMeta;

use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\CommonEnvironmentPackageMetaProvider;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Test environment variable reading and validation
 */
class CommonEnvironmentPackageMetaProviderTest extends TestCase {
	/**
	 * Provider.
	 *
	 * @var CommonEnvironmentPackageMetaProvider
	 */
	private $provider;

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
			'WP_PACKAGE_TESTED',
			'WP_PACKAGE_STABLE',
			'WP_PACKAGE_LICENSE',
			'WP_PACKAGE_LICENSE_URL',
			'WP_PACKAGE_DESCRIPTION',
			'WP_PACKAGE_SECTIONS',
		];
		foreach ( $envVars as $var ) {
			$this->originalEnvVars[ $var ] = getenv( $var );
			putenv( $var );
		}
		$this->provider = new CommonEnvironmentPackageMetaProvider();
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
	public function testAllValid(): void {
		putenv( 'WP_PACKAGE_TESTED=6.8.2' );
		putenv( 'WP_PACKAGE_STABLE=1.5.0' );
		putenv( 'WP_PACKAGE_LICENSE=GPL-2.0' );
		putenv( 'WP_PACKAGE_LICENSE_URL=https://www.gnu.org/licenses/gpl-2.0.html' );
		putenv( 'WP_PACKAGE_DESCRIPTION=A test package' );
		putenv( 'WP_PACKAGE_SECTIONS={"changelog":"Latest changes","faq":"Frequently asked questions"}' );

		$this->assertEquals( '6.8.2', $this->provider->getTested() );
		$this->assertEquals( '1.5.0', $this->provider->getStable() );
		$this->assertEquals( 'GPL-2.0', $this->provider->getLicense() );
		$this->assertEquals( 'https://www.gnu.org/licenses/gpl-2.0.html', $this->provider->getLicenseURL() );
		$this->assertEquals( 'A test package', $this->provider->getDescription() );
		$this->assertEquals(
			[
				'changelog' => 'Latest changes',
				'faq'       => 'Frequently asked questions',
			],
			$this->provider->getSections()
		);
	}

	/**
	 * Test null returns when env vars are not set
	 */
	public function testNullReturnsWhenNotSet(): void {
		$this->assertNull( $this->provider->getTested() );
		$this->assertNull( $this->provider->getStable() );
		$this->assertNull( $this->provider->getLicense() );
		$this->assertNull( $this->provider->getLicenseURL() );
		$this->assertNull( $this->provider->getDescription() );
		$this->assertEquals( [], $this->provider->getSections() );
	}

	/**
	 * Test validation exceptions are thrown for invalid values
	 */
	public function testInvalidValues(): void {
		putenv( 'WP_PACKAGE_TESTED=not-a-version' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getTested();
	}

	/**
	 * Test invalid URL
	 */
	public function testInvalidUrl(): void {
		putenv( 'WP_PACKAGE_LICENSE_URL=not-a-url' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getLicenseURL();
	}

	/**
	 * Test invalid JSON for sections
	 */
	public function testInvalidSections(): void {
		putenv( 'WP_PACKAGE_SECTIONS=not-json-data' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getSections();
	}
}
