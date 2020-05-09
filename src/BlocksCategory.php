<?php

namespace ikiselev1989\SageAcfGutenbergBlockCreator;

abstract class BlocksCategory
{
    const BLOCKS_CATEGORY_SLUG = null;
    const BLOCKS_CATEGORY_DOMAIN = '';
    const BLOCKS_DIR = null;

    public $blocks = [];

    public function __construct() {
        if (!static::BLOCKS_DIR || !static::BLOCKS_CATEGORY_SLUG) {
            return;
        }

        $this->register_hooks();

        $this->register_blocks();
    }

    public function register_hooks() {
        add_filter('block_categories', [$this, 'create_blocks_category'], 10, 2);
    }

    public function create_blocks_category(array $categories): array {
        return array_merge($categories, [[
            'slug'  => static::blocks_category_slug(),
            'title' => static::title(),
            'icon'  => 'dashicons-layout',
        ]]);
    }

    public static function blocks_category_slug() {
        return strtolower(static::BLOCKS_CATEGORY_SLUG);
    }

    public function register_blocks() {
        foreach ($this->blocks as $block) {
            new $block($this);
        }
    }

    abstract static function title();
}