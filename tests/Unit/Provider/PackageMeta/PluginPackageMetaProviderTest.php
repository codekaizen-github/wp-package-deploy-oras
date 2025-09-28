<?php
/**
 * Tests.
 *
 * @package CodeKaizen\WPPackageDeployORASTests
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodekaizenGithub\WPPackageDeployORAS\Contract\PackageMeta\CommonEnvironmentPackageMetaContract;
use CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta\PluginPackageMetaProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class PluginPackageMetaProviderTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAllPropertiesFromPluginMyBasicsPlugin(): void {
		$nameExpected                     = 'Test Plugin';
		$fullSlugExpected                 = 'test-plugin/test-plugin.php';
		$shortSlugExpected                = 'test-plugin';
		$viewURLExpected                  = 'https://codekaizen.net';
		$versionExpected                  = '3.0.1';
		$shortDescriptionExpected         = 'This is a test plugin';
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$textDomainExpected               = 'test-plugin';
		$domainPathExpected               = '/languages';
		$networkActualRaw                 = 'true';
		$networkExpected                  = true;
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$requiresPluginsActualRaw         = 'akismet,hello-dolly';
		$requiresPluginsExpected          = [ 'akismet', 'hello-dolly' ];
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
		$tagsExpected                     = [];
		$sectionsExpected                 = [
			'changelog' => 'changed',
			'about'     => 'this is a plugin about section',
		];
		$sectionsUnexpected               = [];
		$response                         = [
			'Name'            => $nameExpected,
			'PluginURI'       => $viewURLExpected,
			'Version'         => $versionExpected,
			'Description'     => $shortDescriptionExpected,
			'Author'          => $authorExpected,
			'AuthorURI'       => $authorURLExpected,
			'TextDomain'      => $textDomainExpected,
			'DomainPath'      => $domainPathExpected,
			'Network'         => $networkActualRaw,
			'RequiresWP'      => $requiresWordPressVersionExpected,
			'RequiresPHP'     => $requiresPHPVersionExpected,
			'UpdateURI'       => $downloadURLExpected,
			'RequiresPlugins' => $requiresPluginsActualRaw,
		];
		$localProvider                    = Mockery::mock( PluginPackageMetaContract::class );
		$environmentProvider              = Mockery::mock( CommonEnvironmentPackageMetaContract::class );
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
		$localProvider->shouldReceive( 'getNetwork' )->with()->andReturn( $networkExpected );
		$localProvider->shouldReceive( 'getRequiresWordPressVersion' )->with()->andReturn( $requiresWordPressVersionExpected );
		$localProvider->shouldReceive( 'getRequiresPHPVersion' )->with()->andReturn( $requiresPHPVersionExpected );
		$localProvider->shouldReceive( 'getDownloadURL' )->with()->andReturn( $downloadURLExpected );
		$localProvider->shouldReceive( 'getRequiresPlugins' )->with()->andReturn( $requiresPluginsExpected );
		$localProvider->shouldReceive( 'getTags' )->with()->andReturn( $tagsExpected );
		$localProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedUnexpected );
		$localProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableUnexpected );
		$localProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseUnexpected );
		$localProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLUnexpected );
		$localProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionUnexpected );
		$localProvider->shouldReceive( 'getSections' )->with()->andReturn( $sectionsUnexpected );

		$environmentProvider->shouldReceive( 'getTested' )->with()->andReturn( $testedExpected );
		$environmentProvider->shouldReceive( 'getStable' )->with()->andReturn( $stableExpected );
		$environmentProvider->shouldReceive( 'getLicense' )->with()->andReturn( $licenseExpected );
		$environmentProvider->shouldReceive( 'getLicenseURL' )->with()->andReturn( $licenseURLExpected );
		$environmentProvider->shouldReceive( 'getDescription' )->with()->andReturn( $descriptionExpected );
		$environmentProvider->shouldReceive( 'getSections' )->with()->andReturn( $sectionsExpected );
		$provider = new PluginPackageMetaProvider( $localProvider, $environmentProvider );
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
		$this->assertEquals( $networkExpected, $provider->getNetwork() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $requiresPluginsExpected, $provider->getRequiresPlugins() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $sectionsExpected, $provider->getSections() );
	}
}
