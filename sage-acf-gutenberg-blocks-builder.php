<?php

namespace IK89\SageAcfGutenbergBlocksBuilder;

if (!function_exists('acf_register_block_type')) {
    return;
}
if (!function_exists('add_filter')) {
    return;
}
if (!function_exists('add_action')) {
    return;
}

require __DIR__ . '/vendor/autoload.php';

add_filter('gutenberg-blocks-templates-root', function (): string {
    return 'gutenberg-blocks';
});