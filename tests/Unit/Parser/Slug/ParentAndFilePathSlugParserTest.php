<?php
/**
 * Tests.
 *
 * @package CodeKaizen\WPPackageDeployORASTests
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Parser\Slug;

use CodekaizenGithub\WPPackageDeployORAS\Parser\Slug\ParentAndFilePathSlugParser;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class ParentAndFilePathSlugParserTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testPluginRelativeFilePathValid() {
		$parentSlug        = 'my-plugin-folder';
		$packageFilePath   = './my-plugin-file.php';
		$fullSlugExpected  = 'my-plugin-folder/my-plugin-file.php';
		$shortSlugExpected = 'my-plugin-folder';
		$sut               = new ParentAndFilePathSlugParser( $parentSlug, $packageFilePath );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testThemeRelativeFilePathValid() {
		$parentSlug        = 'my-theme-folder';
		$packageFilePath   = './style.css';
		$fullSlugExpected  = 'my-theme-folder/style.css';
		$shortSlugExpected = 'my-theme-folder';
		$sut               = new ParentAndFilePathSlugParser( $parentSlug, $packageFilePath );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testPluginAbsoluteFilePathValid() {
		$parentSlug        = 'my-plugin-folder';
		$packageFilePath   = '/path/to/my-plugin-folder/my-plugin-file.php';
		$fullSlugExpected  = 'my-plugin-folder/my-plugin-file.php';
		$shortSlugExpected = 'my-plugin-folder';
		$sut               = new ParentAndFilePathSlugParser( $parentSlug, $packageFilePath );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testThemeAbsoluteFilePathValid() {
		$parentSlug        = 'my-theme-folder';
		$packageFilePath   = '/path/to/my-theme-folder/style.css';
		$fullSlugExpected  = 'my-theme-folder/style.css';
		$shortSlugExpected = 'my-theme-folder';
		$sut               = new ParentAndFilePathSlugParser( $parentSlug, $packageFilePath );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
	}
}
