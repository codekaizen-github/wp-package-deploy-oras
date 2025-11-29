<?php
/**
 * Tests.
 * phpcs:ignoreFile WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv Ignoring.
 * @package CodeKaizen\WPPackageDeployORASTests\Unit\Factory\JSONSerializable
 */

namespace CodeKaizen\WPPackageDeployORASTests\Unit\Factory\JSONSerializable;

use CodeKaizen\WPPackageDeployORASTests\Helper\FixturePathHelper;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use CodekaizenGithub\WPPackageDeployORAS\Factory\JSONSerializable\PackageMetaJSONSerializableFactory;
use CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Plugin\CompositePluginPackageMetaValue;
use CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Theme\CompositeThemePackageMetaValue;
use Mockery;
use Mockery\MockInterface;
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
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $slugValue;


	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $environmentProvider;

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $pluginPackageMetaValueServiceFactory;


	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $themePackageMetaValueServiceFactory;

	/**
	 * Undocumented variable
	 *
	 * @var (PluginPackageMetaValueServiceContract&MockInterface)|null
	 */
	protected ?PluginPackageMetaValueServiceContract $pluginPackageMetaValueService;

	/**
	 * Undocumented variable
	 *
	 * @var (ThemePackageMetaValueServiceContract&MockInterface)|null
	 */
	protected ?ThemePackageMetaValueServiceContract $themePackageMetaValueService;

	/**
	 * Undocumented variable
	 *
	 * @var (PluginPackageMetaValueContract&MockInterface)|null
	 */
	protected ?PluginPackageMetaValueContract $pluginPackageMetaValue;

	/**
	 * Undocumented variable
	 *
	 * @var (ThemePackageMetaValueContract&MockInterface)|null
	 */
	protected ?ThemePackageMetaValueContract $themePackageMetaValue;


	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $pluginPackageMetaProvider;


	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $themePackageMetaProvider;

	/**
	 * Undocumented variable
	 *
	 * @var (LoggerInterface&MockInterface)|null
	 */
	protected ?LoggerInterface $logger = null;

	/**
	 * Save original environment variables and set up provider before each test
	 */
	protected function setUp(): void {
		$envVars = [
			'WP_PACKAGE_SLUG',
			'WP_PACKAGE_TYPE',
			'WP_PACKAGE_HEADERS_FILE',
		];
		foreach ( $envVars as $var ) {
			$this->originalEnvVars[ $var ] = getenv( $var );
			putenv( $var );
		}
		$this->slugValue = Mockery::mock(
			'overload:CodekaizenGithub\WPPackageDeployORAS\Value\Slug\ParentAndFilePathSlugValue',
			'CodeKaizen\WPPackageMetaProviderLocal\Contract\Value\SlugValueContract'
		);
		$this->environmentProvider = Mockery::mock(
			'overload:CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Common\EnvironmentCommonPackageMetaValue',
			'CodekaizenGithub\WPPackageDeployORAS\Contract\Value\PackageMeta\CommonPackageMetaValueContract'
		);
		$this->pluginPackageMetaValueServiceFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory',
		);
		$this->themePackageMetaValueServiceFactory = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory',
		);
		$this->pluginPackageMetaValueService = Mockery::mock(PluginPackageMetaValueServiceContract::class);
		$this->themePackageMetaValueService = Mockery::mock(ThemePackageMetaValueServiceContract::class);
		$this->pluginPackageMetaValue = Mockery::mock(PluginPackageMetaValueContract::class);
		$this->themePackageMetaValue = Mockery::mock(ThemePackageMetaValueContract::class);
		$this->pluginPackageMetaProvider = Mockery::mock(
			'overload:CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Plugin\CompositePluginPackageMetaValue',
			'CodekaizenGithub\WPPackageDeployORAS\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract',
			'JSONSerializable'
		);
		$this->themePackageMetaProvider = Mockery::mock(
			'overload:CodekaizenGithub\WPPackageDeployORAS\Value\PackageMeta\Theme\CompositeThemePackageMetaValue',
			'CodekaizenGithub\WPPackageDeployORAS\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract',
			'JSONSerializable'
		);
		$this->getPluginPackageMetaValueServiceFactory()->allows([
			'create' => $this->pluginPackageMetaValueService,
		]);
		$this->getThemePackageMetaValueServiceFactory()->allows([
			'create' => $this->themePackageMetaValueService,
		]);
		$this->getPluginPackageMetaValueService()->allows([
			'getPackageMeta' => $this->pluginPackageMetaValue,
		]);
		$this->getThemePackageMetaValueService()->allows([
			'getPackageMeta' => $this->themePackageMetaValue,
		]);
		$this->logger = Mockery::mock(LoggerInterface::class);
	}

	/**
	 * Restore original environment after each test
	 */
	protected function tearDown(): void {
		Mockery::close();
		foreach ( $this->originalEnvVars as $key => $value ) {
			if ( false === $value ) {
				putenv( $key );
			} else {
				putenv( "$key=$value" );
			}
		}
	}


	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getSlugValue(): MockInterface {
		self::assertNotNull( $this->slugValue );
		return $this->slugValue;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getEnvironmentProvider(): MockInterface {
		self::assertNotNull( $this->environmentProvider );
		return $this->environmentProvider;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getPluginPackageMetaValueServiceFactory(): MockInterface {
		self::assertNotNull( $this->pluginPackageMetaValueServiceFactory );
		return $this->pluginPackageMetaValueServiceFactory;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getThemePackageMetaValueServiceFactory(): MockInterface {
		self::assertNotNull( $this->themePackageMetaValueServiceFactory );
		return $this->themePackageMetaValueServiceFactory;
	}

	/**
	 * Undocumented function
	 *
	 * @return PluginPackageMetaValueServiceContract&MockInterface
	 */
	public function getPluginPackageMetaValueService(): PluginPackageMetaValueServiceContract&MockInterface {
		self::assertNotNull( $this->pluginPackageMetaValueService );
		return $this->pluginPackageMetaValueService;
	}

	/**
	 * Undocumented function
	 *
	 * @return ThemePackageMetaValueServiceContract&MockInterface
	 */
	public function getThemePackageMetaValueService(): ThemePackageMetaValueServiceContract&MockInterface {
		self::assertNotNull( $this->themePackageMetaValueService );
		return $this->themePackageMetaValueService;
	}

	/**
	 * Undocumented function
	 *
	 * @return PluginPackageMetaValueContract&MockInterface
	 */
	public function getPluginPackageMetaValue(): PluginPackageMetaValueContract&MockInterface {
		self::assertNotNull( $this->pluginPackageMetaValue );
		return $this->pluginPackageMetaValue;
	}

	/**
	 * Undocumented function
	 *
	 * @return ThemePackageMetaValueContract&MockInterface
	 */
	public function getThemePackageMetaValue(): ThemePackageMetaValueContract&MockInterface {
		self::assertNotNull( $this->themePackageMetaValue );
		return $this->themePackageMetaValue;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getPluginPackageMetaProvider(): MockInterface {
		self::assertNotNull( $this->pluginPackageMetaProvider );
		return $this->pluginPackageMetaProvider;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getThemePackageMetaProvider(): MockInterface {
		self::assertNotNull( $this->themePackageMetaProvider );
		return $this->themePackageMetaProvider;
	}

	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface&MockInterface
	 */
	public function getLogger(): LoggerInterface&MockInterface {
		self::assertNotNull( $this->logger );
		return $this->logger;
	}

	/**
	 * Test all methods with valid values
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testAllPluginValid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$sut = new PackageMetaJSONSerializableFactory($this->getLogger());
		$provider = $sut->create();
		$this->assertInstanceOf(CompositePluginPackageMetaValue::class, $provider);
	}

	/**
	 * Test all methods with valid values
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testAllThemeValid(): void {
		putenv('WP_PACKAGE_SLUG=my-theme');
		putenv( 'WP_PACKAGE_TYPE=theme' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$sut = new PackageMetaJSONSerializableFactory($this->getLogger());
		$provider = $sut->create();
		$this->assertInstanceOf(CompositeThemePackageMetaValue::class, $provider);
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testPackageTypeInvalid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=asdf' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/real.txt' );
		$sut = new PackageMetaJSONSerializableFactory($this->getLogger());
		$this->expectException(UnexpectedValueException::class);
		$sut->create();
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testFilePathInvalid(): void {
		putenv('WP_PACKAGE_SLUG=my-plugin');
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/fake.txt' );
		$sut = new PackageMetaJSONSerializableFactory($this->getLogger());
		$this->expectException(UnexpectedValueException::class);
		$sut->create();
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testPackageSlugInvalid(): void {
		putenv( 'WP_PACKAGE_TYPE=plugin' );
		putenv( 'WP_PACKAGE_HEADERS_FILE=' . FixturePathHelper::getPathForFile() . '/fake.txt' );
		$sut = new PackageMetaJSONSerializableFactory($this->getLogger());
		$this->expectException(UnexpectedValueException::class);
		$sut->create();
	}
}
