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

add_filter('gutenberg-blocks-templates-root', function (): string {
    return 'gutenberg-blocks';
});

add_filter('gutenberg-blocks-default-attributes', function (): array {
    return [
        'name'            => '',
        'title'           => '',
        'description'     => '',
        'category'        => 'common',
        'icon'            => '',
        'mode'            => 'preview',
        'align'           => '',
        'keywords'        => [],
        'supports'        => [],
        'post_types'      => [],
        'render_template' => false,
        'render_callback' => false,
        'enqueue_style'   => false,
        'enqueue_script'  => false,
        'enqueue_assets'  => false,
    ];
});

add_filter('gutenberg-blocks-category-default-attributes', function (): array {
    return [
        'slug'  => '',
        'title' => '',
        'icon'  => null,
    ];
});