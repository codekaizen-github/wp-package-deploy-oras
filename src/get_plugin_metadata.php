<?php
require __DIR__ . '/../vendor/autoload.php';

use AndrewJDawes\WPPackageParser\WPPackage;

if ($argc < 2) {
    fwrite(STDERR, "Usage: php get_plugin_metadata.php <plugin-zip-path>\n");
    exit(1);
}

$zipPath = $argv[1];
$parser = new WPPackage($zipPath, 'plugin', false);
$meta = $parser->getMetaData();

echo json_encode($meta);
