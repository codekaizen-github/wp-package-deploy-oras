<?php
/**
 * Unknown.
 *
 * @package CodekaizenGithub\WPPackageDeployORAS
 */

namespace CodekaizenGithub\WPPackageDeployORAS\Contract\Factory;

use JsonSerializable;

interface JSONSerializableFactoryContract {
	/**
	 * Undocumented function
	 *
	 * @return JsonSerializable
	 */
	public function create(): JsonSerializable;
}
