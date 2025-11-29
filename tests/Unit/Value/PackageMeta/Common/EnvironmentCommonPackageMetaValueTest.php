<?php
/**
 * Tests.
 * phpcs:ignoreFile WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv Ignoring.
 * @package CodeKaizen\WPPackageDeployORASTests\Unit\Value\PackageMeta\Common
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Value\PackageMeta\Common;

use CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Common\EnvironmentCommonPackageMetaValue;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Test environment variable reading and validation
 */
class EnvironmentCommonPackageMetaValueTest extends TestCase {
	/**
	 * Provider.
	 *
	 * @var EnvironmentCommonPackageMetaValue
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
		$envVars = [
			'WP_PACKAGE_TESTED',
			'WP_PACKAGE_STABLE',
			'WP_PACKAGE_LICENSE',
			'WP_PACKAGE_LICENSE_URL',
			'WP_PACKAGE_DESCRIPTION',
			'WP_PACKAGE_SECTIONS',
			'WP_PACKAGE_ICONS',
			'WP_PACKAGE_BANNERS',
			'WP_PACKAGE_BANNERS_RTL',
			'ORASHUB_BASE_URL',
			'IMAGE_REGISTRY_HOSTNAME',
			'IMAGE_REPOSITORY',
			'IMAGE_TAG',
		];
		foreach ( $envVars as $var ) {
			$this->originalEnvVars[ $var ] = getenv( $var );
			putenv( $var );
		}
		$this->provider = new EnvironmentCommonPackageMetaValue();
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
		putenv( 'WP_PACKAGE_ICONS={"1x":"https://example.com/icon-128x128.png","2x":"https://example.com/icon-256x256.png","svg":"https://example.com/icon.svg"}' );
		putenv( 'WP_PACKAGE_BANNERS={"1x":"https://example.com/banner-772x250.png","2x":"https://example.com/banner-1544x500.png"}' );
		putenv( 'WP_PACKAGE_BANNERS_RTL={"1x":"https://example.com/banner-rtl-772x250.png","2x":"https://example.com/banner-rtl-1544x500.png"}' );

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
		$this->assertEquals(
			[
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			$this->provider->getIcons()
		);
		$this->assertEquals(
			[
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			$this->provider->getBanners()
		);
		$this->assertEquals(
			[
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			$this->provider->getBannersRTL()
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
		$this->assertEquals( [], $this->provider->getIcons() );
		$this->assertEquals( [], $this->provider->getBanners() );
		$this->assertEquals( [], $this->provider->getBannersRTL() );
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

	/**
	 * Test invalid JSON for icons
	 */
	public function testInvalidIcons(): void {
		putenv( 'WP_PACKAGE_ICONS=not-json-data' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getIcons();
	}
	/**
	 * Test invalid JSON for banners
	 */
	public function testInvalidBanners(): void {
		putenv( 'WP_PACKAGE_BANNERS=not-json-data' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getBanners();
	}
	/**
	 * Test invalid JSON for RTL banners
	 */
	public function testInvalidBannersRTL(): void {
		putenv( 'WP_PACKAGE_BANNERS_RTL=not-json-data' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getBannersRTL();
	}

	/**
	 * Test valid download URL generation with all required variables set
	 */
	public function testValidDownloadUrl(): void {
		putenv( 'ORASHUB_BASE_URL=https://orashub.example.com' );
		putenv( 'IMAGE_REGISTRY_HOSTNAME=my-registry' );
		putenv( 'IMAGE_REPOSITORY=my-repo' );
		putenv( 'IMAGE_TAG=1.0.0' );

		$expectedUrl = 'https://orashub.example.com/api/v1/my-registry/my-repo/1.0.0/download';
		$this->assertEquals( $expectedUrl, $this->provider->getDownloadURL() );

		// Test with trailing slash in base URL
		putenv( 'ORASHUB_BASE_URL=https://orashub.example.com/' );
		$this->assertEquals( $expectedUrl, $this->provider->getDownloadURL() );
	}

	/**
	 * Test null return when environment variables are not set
	 */
	public function testNullDownloadUrlWhenVarsNotSet(): void {
		$this->assertNull( $this->provider->getDownloadURL() );

		// Test with only some variables set
		putenv( 'ORASHUB_BASE_URL=https://orashub.example.com' );
		putenv( 'IMAGE_REGISTRY_HOSTNAME=my-registry' );
		$this->assertNull( $this->provider->getDownloadURL() );
	}

	/**
	 * Test validation of download URL components
	 */
	public function testInvalidDownloadUrlComponents(): void {
		// Test invalid base URL
		putenv( 'ORASHUB_BASE_URL=not-a-url' );
		putenv( 'IMAGE_REGISTRY_HOSTNAME=my-registry' );
		putenv( 'IMAGE_REPOSITORY=my-repo' );
		putenv( 'IMAGE_TAG=1.0.0' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getDownloadURL();

		// Reset and test empty registry
		putenv( 'ORASHUB_BASE_URL=https://orashub.example.com' );
		putenv( 'IMAGE_REGISTRY_HOSTNAME=' );
		putenv( 'IMAGE_REPOSITORY=my-repo' );
		putenv( 'IMAGE_TAG=1.0.0' );
		$this->expectException( UnexpectedValueException::class );
		$this->provider->getDownloadURL();
	}
}
