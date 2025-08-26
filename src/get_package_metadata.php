<?php
require __DIR__ . '/../vendor/autoload.php';

use AndrewJDawes\WPPackageParser\WPPackage;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Model\ORASHubPackageMetaFromObjectPlugin;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Model\ORASHubPackageMetaFromObjectTheme;

$parser = new WPPackage(getenv('PACKAGE_ZIP_PATH'), getenv('PACKAGE_TYPE') ?: 'plugin', getenv('PARSE_README') ?: false);
$meta = $parser->getMetaData();

// Handle WP_PACKAGE_METADATA_OVERRIDES env variable (optional JSON string)
$overridesJson = getenv('WP_PACKAGE_METADATA_OVERRIDES');
if ($overridesJson) {
    $overrides = json_decode($overridesJson, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($overrides)) {
        $meta = deep_merge($meta, $overrides);
    } else {
        echo 'Invalid WP_PACKAGE_METADATA_OVERRIDES JSON: ' . json_last_error_msg();
        exit(1);
    }
}

$metaObject = json_decode(json_encode($meta));
$model = null;
switch ($parser->getType()) {
    case 'plugin':
        $model = new ORASHubPackageMetaFromObjectPlugin($metaObject);
        break;
    case 'theme':
        $model = new ORASHubPackageMetaFromObjectTheme($metaObject);
        break;
    default:
        echo 'Unknown package type: ' . $parser->getType();
        exit(1);
}

if (null === $model) {
    echo 'Unable to construct model';
    exit(1);
}

echo json_encode($model);

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
