<?php
/**
 * Tests.
 *
 * @package CodeKaizen\WPPackageDeployORASTests\Unit\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use CodekaizenGithub\WPPackageDeployORAS\Contract\Value\PackageMeta\CommonPackageMetaValueContract;
use CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Theme\CompositeThemePackageMetaValue;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ThemePackageMetaValueTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAllPropertiesFromThemeFabledSunset(): void {
		$nameExpected                     = 'Test Theme';
		$fullSlugExpected                 = 'test-theme/style.css';
		$shortSlugExpected                = 'test-theme';
		$versionExpected                  = '3.0.1';
		$viewURLExpected                  = 'https://codekaizen.net';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$tagsExpected                     = [
			'awesome',
			'cool',
			'test',
		];
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$shortDescriptionExpected         = 'This is a test theme';
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$templateExpected                 = 'parent-theme';
		$statusExpected                   = 'publish';
		$textDomainExpected               = 'test-theme';
		$domainPathExpected               = '/languages';
		$testedExpected                   = '6.8.1';
		$testedUnexpected                 = null;
		$stableExpected                   = '6.8.0';
		$stableUnexpected                 = null;
		$licenseExpected                  = 'GPL v2 or later';
		$licenseUnexpected                = null;
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$licenseURLUnexpected             = null;
		$descriptionExpected              = 'This is a test description';
		$descriptionUnexpected            = null;
		$iconsExpected                    = [
			'1x'  => 'https://example.com/icon-128x128.png',
			'2x'  => 'https://example.com/icon-256x256.png',
			'svg' => 'https://example.com/icon.svg',
		];
		$iconsUnexpected                  = [];
		$bannersExpected                  = [
			'1x' => 'https://example.com/banner-772x250.png',
			'2x' => 'https://example.com/banner-1544x500.png',
		];
		$bannersUnexpected                = [];
		$bannersRtlExpected               = [
			'1x' => 'https://example.com/banner-rtl-772x250.png',
			'2x' => 'https://example.com/banner-rtl-1544x500.png',
		];
		$bannersRtlUnexpected             = [];
		$localProvider                    = Mockery::mock( ThemePackageMetaValueContract::class );
		$environmentProvider              = Mockery::mock( CommonPackageMetaValueContract::class );
		$localProvider->shouldReceive( 'getName' )->with()->andReturn( $nameExpected );
		$localProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$localProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( $shortSlugExpected );
		$localProvider->shouldReceive( 'getViewURL' )->with()->andReturn( $viewURLExpected );
		$localProvider->shouldReceive( 'getVersion' )->with()->andReturn( $versionExpected );
		$localProvider->shouldReceive( 'getShortDescription' )->with()->andReturn( $shortDescriptionExpected );
		$localProvider->shouldReceive( 'getAuthor' )->with()->andReturn( $authorExpected );
		$localProvider->shouldReceive( 'getAuthorURL' )->with()->andReturn( $authorURLExpected );
		$localProvider->shouldReceive( 'getTextDomain' )->with()->andReturn( $textDomainExpected );
		$localProvider->shouldReceive( 'getDomainPath' )->with()->andReturn( $domainPathExpected );
		$localProvider
			->shouldReceive( 'getRequiresWordPressVersion' )
			->with()
			->andReturn( $requiresWordPressVersionExpected );
		$localProvider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPHPVersionExpected );
		$localProvider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$localProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedUnexpected );
		$localProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableUnexpected );
		$localProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseUnexpected );
		$localProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLUnexpected );
		$localProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionUnexpected );
		$localProvider->shouldReceive( 'getTemplate' )->with()->andReturn( $templateExpected );
		$localProvider->shouldReceive( 'getStatus' )->with()->andReturn( $statusExpected );
		$localProvider->shouldReceive( 'getIcons' )->with()->andReturn( $iconsUnexpected );
		$localProvider->shouldReceive( 'getBanners' )->with()->andReturn( $bannersUnexpected );
		$localProvider->shouldReceive( 'getBannersRTL' )->with()->andReturn( $bannersRtlUnexpected );

		$environmentProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$environmentProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableExpected );
		$environmentProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseExpected );
		$environmentProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLExpected );
		$environmentProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionExpected );
		$environmentProvider->shouldReceive( 'getIcons' )->with()->andReturn( $iconsExpected );
		$environmentProvider->shouldReceive( 'getBanners' )->with()->andReturn( $bannersExpected );
		$environmentProvider->shouldReceive( 'getBannersRTL' )->with()->andReturn( $bannersRtlExpected );
		$environmentProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadURLExpected );
		$provider = new CompositeThemePackageMetaValue( $localProvider, $environmentProvider );
		$this->assertEquals( $nameExpected, $provider->getName() );
		$this->assertEquals( $fullSlugExpected, $provider->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $provider->getShortSlug() );
		$this->assertEquals( $viewURLExpected, $provider->getViewURL() );
		$this->assertEquals( $versionExpected, $provider->getVersion() );
		$this->assertEquals( $shortDescriptionExpected, $provider->getShortDescription() );
		$this->assertEquals( $authorExpected, $provider->getAuthor() );
		$this->assertEquals( $authorURLExpected, $provider->getAuthorURL() );
		$this->assertEquals( $textDomainExpected, $provider->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $provider->getDomainPath() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $templateExpected, $provider->getTemplate() );
		$this->assertEquals( $statusExpected, $provider->getStatus() );
		$this->assertEquals( $iconsExpected, $provider->getIcons() );
		$this->assertEquals( $bannersExpected, $provider->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $provider->getBannersRTL() );
	}
	/**
	 * Undocumented function.
	 *
	 * @return void
	 */
	public function testJSONEncodeAndDecode(): void {
		$nameExpected                     = 'Test Theme';
		$fullSlugExpected                 = 'test-theme/style.css';
		$shortSlugExpected                = 'test-theme';
		$versionExpected                  = '3.0.1';
		$viewURLExpected                  = 'https://codekaizen.net';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$tagsExpected                     = [
			'awesome',
			'cool',
			'test',
		];
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$shortDescriptionExpected         = 'This is a test theme';
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$templateExpected                 = 'parent-theme';
		$statusExpected                   = 'publish';
		$textDomainExpected               = 'test-theme';
		$domainPathExpected               = '/languages';
		$testedExpected                   = '6.8.1';
		$testedUnexpected                 = null;
		$stableExpected                   = '6.8.0';
		$stableUnexpected                 = null;
		$licenseExpected                  = 'GPL v2 or later';
		$licenseUnexpected                = null;
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$licenseURLUnexpected             = null;
		$descriptionExpected              = 'This is a test description';
		$descriptionUnexpected            = null;
		$iconsExpected                    = [
			'1x'  => 'https://example.com/icon-128x128.png',
			'2x'  => 'https://example.com/icon-256x256.png',
			'svg' => 'https://example.com/icon.svg',
		];
		$iconsUnexpected                  = [];
		$bannersExpected                  = [
			'1x' => 'https://example.com/banner-772x250.png',
			'2x' => 'https://example.com/banner-1544x500.png',
		];
		$bannersUnexpected                = [];
		$bannersRtlExpected               = [
			'1x' => 'https://example.com/banner-rtl-772x250.png',
			'2x' => 'https://example.com/banner-rtl-1544x500.png',
		];
		$bannersRtlUnexpected             = [];
		$localProvider                    = Mockery::mock( ThemePackageMetaValueContract::class );
		$environmentProvider              = Mockery::mock( CommonPackageMetaValueContract::class );
		$localProvider->shouldReceive( 'getName' )->with()->andReturn( $nameExpected );
		$localProvider->shouldReceive( 'getFullSlug' )->with()->andReturn( $fullSlugExpected );
		$localProvider->shouldReceive( 'getShortSlug' )->with()->andReturn( $shortSlugExpected );
		$localProvider->shouldReceive( 'getViewURL' )->with()->andReturn( $viewURLExpected );
		$localProvider->shouldReceive( 'getVersion' )->with()->andReturn( $versionExpected );
		$localProvider->shouldReceive( 'getShortDescription' )->with()->andReturn( $shortDescriptionExpected );
		$localProvider->shouldReceive( 'getAuthor' )->with()->andReturn( $authorExpected );
		$localProvider
			->shouldReceive( 'getAuthorURL' )
			->with()
			->andReturn( $authorURLExpected );
		$localProvider->shouldReceive( 'getTextDomain' )->with()->andReturn( $textDomainExpected );
		$localProvider->shouldReceive( 'getDomainPath' )->with()->andReturn( $domainPathExpected );
		$localProvider
			->shouldReceive( 'getRequiresWordPressVersion' )
			->with()
			->andReturn( $requiresWordPressVersionExpected );
		$localProvider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPHPVersionExpected );
		$localProvider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$localProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedUnexpected );
		$localProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableUnexpected );
		$localProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseUnexpected );
		$localProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLUnexpected );
		$localProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionUnexpected );
		$localProvider->shouldReceive( 'getTemplate' )->with()->andReturn( $templateExpected );
		$localProvider->shouldReceive( 'getStatus' )->with()->andReturn( $statusExpected );
		$localProvider->shouldReceive( 'getIcons' )->with()->andReturn( $iconsUnexpected );
		$localProvider->shouldReceive( 'getBanners' )->with()->andReturn( $bannersUnexpected );
		$localProvider->shouldReceive( 'getBannersRTL' )->with()->andReturn( $bannersRtlUnexpected );

		$environmentProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$environmentProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableExpected );
		$environmentProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseExpected );
		$environmentProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLExpected );
		$environmentProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionExpected );
		$environmentProvider->shouldReceive( 'getIcons' )->with()->andReturn( $iconsExpected );
		$environmentProvider->shouldReceive( 'getBanners' )->with()->andReturn( $bannersExpected );
		$environmentProvider->shouldReceive( 'getBannersRTL' )->with()->andReturn( $bannersRtlExpected );
		$environmentProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadURLExpected );
		$provider = new CompositeThemePackageMetaValue( $localProvider, $environmentProvider );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$encoded = json_encode( $provider );
		$this->assertIsString( $encoded );
		$decoded = json_decode( $encoded, true );
		$this->assertIsArray( $decoded );
		$this->assertArrayHasKey( 'name', $decoded );
		$this->assertEquals( $nameExpected, $decoded['name'] );
		$this->assertArrayHasKey( 'fullSlug', $decoded );
		$this->assertEquals( $fullSlugExpected, $decoded['fullSlug'] );
		$this->assertArrayHasKey( 'shortSlug', $decoded );
		$this->assertEquals( $shortSlugExpected, $decoded['shortSlug'] );
		$this->assertArrayHasKey( 'version', $decoded );
		$this->assertEquals( $versionExpected, $decoded['version'] );
		$this->assertArrayHasKey( 'viewUrl', $decoded );
		$this->assertEquals( $viewURLExpected, $decoded['viewUrl'] );
		$this->assertArrayHasKey( 'downloadUrl', $decoded );
		$this->assertEquals( $downloadURLExpected, $decoded['downloadUrl'] );
		$this->assertArrayHasKey( 'tested', $decoded );
		$this->assertEquals( $testedExpected, $decoded['tested'] );
		$this->assertArrayHasKey( 'stable', $decoded );
		$this->assertEquals( $stableExpected, $decoded['stable'] );
		$this->assertArrayHasKey( 'tags', $decoded );
		$this->assertEquals( $tagsExpected, $decoded['tags'] );
		$this->assertArrayHasKey( 'author', $decoded );
		$this->assertEquals( $authorExpected, $decoded['author'] );
		$this->assertArrayHasKey( 'authorUrl', $decoded );
		$this->assertEquals( $authorURLExpected, $decoded['authorUrl'] );
		$this->assertArrayHasKey( 'license', $decoded );
		$this->assertEquals( $licenseExpected, $decoded['license'] );
		$this->assertArrayHasKey( 'licenseUrl', $decoded );
		$this->assertEquals( $licenseURLExpected, $decoded['licenseUrl'] );
		$this->assertArrayHasKey( 'shortDescription', $decoded );
		$this->assertEquals( $shortDescriptionExpected, $decoded['shortDescription'] );
		$this->assertArrayHasKey( 'description', $decoded );
		$this->assertEquals( $descriptionExpected, $decoded['description'] );
		$this->assertArrayHasKey( 'requiresWordPressVersion', $decoded );
		$this->assertEquals( $requiresWordPressVersionExpected, $decoded['requiresWordPressVersion'] );
		$this->assertArrayHasKey( 'requiresPHPVersion', $decoded );
		$this->assertEquals( $requiresPHPVersionExpected, $decoded['requiresPHPVersion'] );
		$this->assertArrayHasKey( 'template', $decoded );
		$this->assertEquals( $templateExpected, $decoded['template'] );
		$this->assertArrayHasKey( 'status', $decoded );
		$this->assertEquals( $statusExpected, $decoded['status'] );
		$this->assertArrayHasKey( 'textDomain', $decoded );
		$this->assertEquals( $textDomainExpected, $decoded['textDomain'] );
		$this->assertArrayHasKey( 'domainPath', $decoded );
		$this->assertEquals( $domainPathExpected, $decoded['domainPath'] );
		$this->assertArrayHasKey( 'icons', $decoded );
		$this->assertEquals( $iconsExpected, $decoded['icons'] );
		$this->assertArrayHasKey( 'banners', $decoded );
		$this->assertEquals( $bannersExpected, $decoded['banners'] );
		$this->assertArrayHasKey( 'bannersRtl', $decoded );
		$this->assertEquals( $bannersRtlExpected, $decoded['bannersRtl'] );
	}
}
