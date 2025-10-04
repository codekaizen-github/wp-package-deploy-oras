<?php
/**
 * Environment variables.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
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
}
