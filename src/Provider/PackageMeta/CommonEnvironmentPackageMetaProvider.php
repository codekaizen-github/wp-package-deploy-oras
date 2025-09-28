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

/**
 * Undocumented class
 */
class CommonEnvironmentPackageMetaProvider implements CommonEnvironmentPackageMetaContract {
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getTested(): ?string {
		$value = getenv( 'WP_PACKAGE_TESTED' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new FlexibleSemanticVersionRule(),
			)
		)->check( $value );
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
	 */
	public function getStable(): ?string {
		$value = getenv( 'WP_PACKAGE_STABLE' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new FlexibleSemanticVersionRule(),
			)
		)->check( $value );
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
	 */
	public function getLicense(): ?string {
		$value = getenv( 'WP_PACKAGE_LICENSE' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new Rules\StringType(),
			)
		)->check( $value );
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
	 */
	public function getLicenseURL(): ?string {
		$value = getenv( 'WP_PACKAGE_LICENSE_URL' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new Rules\Url(),
			)
		)->check( $value );
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
	 */
	public function getDescription(): ?string {
		$value = getenv( 'WP_PACKAGE_DESCRIPTION' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new Rules\StringType(),
			)
		)->check( $value );
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
	 */
	public function getSections(): array {
		$value = getenv( 'WP_PACKAGE_SECTIONS' );
		Validator::create(
			new Rules\AnyOf(
				new Rules\AllOf( new Rules\BoolType(), new Rules\FalseVal() ),
				new Rules\StringType(),
			)
		)->check( $value );
		/**
		 * Value will have been validated.
		 *
		 * @var false|string $value
		 * */
		if ( false === $value ) {
			return [];
		}
		$decoded = json_decode( $value, true );
		Validator::create(
			new Rules\AllOf(
				new Rules\ArrayType(),
				new Rules\Each( new Rules\StringType() ),
				new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
			)
		)->check( $decoded );
		/**
		 * Value will have been validated
		 *
		 * @var array<string,string> $decoded
		 */
		return $decoded;
	}
}
