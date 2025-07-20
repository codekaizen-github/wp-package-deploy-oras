<?php
require __DIR__ . '/../vendor/autoload.php';

use RenVentura\WPPackageParser\WPPackage;

if ($argc < 2) {
    fwrite(STDERR, "Usage: php get_plugin_metadata.php <plugin-zip-path>\n");
    exit(1);
}

$zipPath = $argv[1];
$parser = new WPPackage($zipPath);
$meta = $parser->getMetaData();
# override slug if provided
if (isset($_ENV['PLUGIN_SLUG'])) {
    $meta['slug'] = $_ENV['PLUGIN_SLUG'];
}
echo json_encode($meta);
