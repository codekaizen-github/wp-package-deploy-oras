<?php
/**
 * Environment variables.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Provider\PackageMeta;

use CodekaizenGithub\WPPackageDeployORAS\Contract\PackageMeta\CommonEnvironmentPackageMetaContract;
use Respect\Validation\Rules;
use Respect\Validation\Validator;
use CodekaizenGithub\WPPackageDeployORAS\Validator\Rule\Version\FlexibleSemanticVersionRule;
use Respect\Validation\Exceptions\ValidationException;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class CommonEnvironmentPackageMetaProvider implements CommonEnvironmentPackageMetaContract {
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getTested(): ?string {
		$key   = 'WP_PACKAGE_TESTED';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new FlexibleSemanticVersionRule(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid semantic version" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		return false === $value ? null : $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return string|null
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getStable(): ?string {
		$key   = 'WP_PACKAGE_STABLE';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new FlexibleSemanticVersionRule(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid semantic version" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		return false === $value ? null : $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return string|null
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getLicense(): ?string {
		$key   = 'WP_PACKAGE_LICENSE';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		return false === $value ? null : $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return string|null
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getLicenseURL(): ?string {
		$key   = 'WP_PACKAGE_LICENSE_URL';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\Url(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		return false === $value ? null : $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return string|null
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getDescription(): ?string {
		$key   = 'WP_PACKAGE_DESCRIPTION';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		return false === $value ? null : $value;
	}

	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getSections(): array {
		$key   = 'WP_PACKAGE_SECTIONS';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be of type string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		if ( false === $value ) {
			return [];
		}
		$decoded = json_decode( $value, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Each( new Rules\StringType() ),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
				)
			)->check( $decoded );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid JSON object with name/value pairs" );
		}
		/**
		 * Value will have been validated
		 *
		 * @var array<string,string> $decoded
		 */
		return $decoded;
	}

	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getIcons(): array {
		$key   = 'WP_PACKAGE_ICONS';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be of type string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		if ( false === $value ) {
			return [];
		}
		$decoded = json_decode( $value, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Each( new Rules\StringType() ),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
				)
			)->check( $decoded );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid JSON object with name/value pairs" );
		}
		/**
		 * Value will have been validated
		 *
		 * @var array<string,string> $decoded
		 */
		return $decoded;
	}

	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getBanners(): array {
		$key   = 'WP_PACKAGE_BANNERS';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be of type string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		if ( false === $value ) {
			return [];
		}
		$decoded = json_decode( $value, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Each( new Rules\StringType() ),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
				)
			)->check( $decoded );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid JSON object with name/value pairs" );
		}
		/**
		 * Value will have been validated
		 *
		 * @var array<string,string> $decoded
		 */
		return $decoded;
	}

	/**
	 * Undocumented function
	 *
	 * @return array<string,string>
	 * @throws UnexpectedValueException On invalid value.
	 */
	public function getBannersRTL(): array {
		$key   = 'WP_PACKAGE_BANNERS_RTL';
		$value = getenv( $key );
		try {
			Validator::create(
				new Rules\AnyOf(
					new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
					new Rules\StringType(),
				)
			)->check( $value );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be of type string" );
		}
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		if ( false === $value ) {
			return [];
		}
		$decoded = json_decode( $value, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Each( new Rules\StringType() ),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
				)
			)->check( $decoded );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException( "If set, $key must be a valid JSON object with name/value pairs" );
		}
		/**
		 * Value will have been validated
		 *
		 * @var array<string,string> $decoded
		 */
		return $decoded;
	}

	/**
	 * Gets the URL to download the package from ORAS registry.
	 *
	 * @return string|null The download URL for the package or null if environment variables are not set.
	 * @throws UnexpectedValueException If environment variables are configured but invalid.
	 */
	public function getDownloadURL(): ?string {
		$requiredVars = [
			'ORASHUB_BASE_URL',
			'IMAGE_REGISTRY_HOSTNAME',
			'IMAGE_REPOSITORY',
			'IMAGE_TAG',
		];

		// Check if all required variables are set.
		foreach ( $requiredVars as $var ) {
			if ( false === getenv( $var ) ) {
				return null;
			}
		}

		// Get and validate each variable.
		$baseUrl    = getenv( 'ORASHUB_BASE_URL' );
		$registry   = getenv( 'IMAGE_REGISTRY_HOSTNAME' );
		$repository = getenv( 'IMAGE_REPOSITORY' );
		$tag        = getenv( 'IMAGE_TAG' );

		try {
			// Validate base URL.
			Validator::create(
				new Rules\Url()
			)->check( $baseUrl );

			// Validate registry, repository, and tag are non-empty strings.
			foreach ( [ $registry, $repository, $tag ] as $value ) {
				Validator::create(
					new Rules\AllOf(
						new Rules\StringType(),
						new Rules\NotEmpty()
					)
				)->check( $value );
			}
		} catch ( ValidationException $e ) {
			// phpcs:disable WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new UnexpectedValueException(
				'Invalid ORAS environment variables configuration: ' . $e->getMessage()
			);
			// phpcs:enable WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}
		/**
		 * Values will have been validated
		 *
		 * @var string $baseUrl
		 * @var string $registry
		 * @var string $repository
		 * @var string $tag
		 */
		// Construct download URL using the template format.
		return rtrim( $baseUrl, '/' ) . '/api/v1/' .
			implode( '/', [ $registry, $repository, $tag ] ) .
			'/download';
	}
}
