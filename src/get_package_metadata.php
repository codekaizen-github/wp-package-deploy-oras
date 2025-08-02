<?php
require __DIR__ . '/../vendor/autoload.php';

use AndrewJDawes\WPPackageParser\WPPackage;

$parser = new WPPackage(getenv('PACKAGE_ZIP_PATH'), getenv('PACKAGE_TYPE') ?: 'plugin', getenv('PARSE_README') ?: false);
$meta = $parser->getMetaData();

echo json_encode($meta);
