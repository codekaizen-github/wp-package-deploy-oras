<?php
require __DIR__ . '/../vendor/autoload.php';

use AndrewJDawes\WPPackageParser\WPPackage;

echo json_encode($meta);

$parser = new WPPackage(getenv('PACKAGE_ZIP_PATH'), getenv('PACKAGE_TYPE') ?: 'plugin', getenv('PARSE_README') ?: false);
$meta = $parser->getMetaData();

// Handle WP_PACKAGE_METADATA_OVERRIDES env variable (optional JSON string)
$overridesJson = getenv('WP_PACKAGE_METADATA_OVERRIDES');
if ($overridesJson) {
    $overrides = json_decode($overridesJson, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($overrides)) {
        $meta = deep_merge($meta, $overrides);
    }
}

echo json_encode($meta);

// Deep merge helper: merges $b into $a, favoring $b's values
function deep_merge(array $a, array $b): array
{
    foreach ($b as $key => $value) {
        if (array_key_exists($key, $a) && is_array($a[$key]) && is_array($value)) {
            $a[$key] = deep_merge($a[$key], $value);
        } else {
            $a[$key] = $value;
        }
    }
    return $a;
}
