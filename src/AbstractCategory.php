<?php


namespace IK89\SageAcfGutenbergBlocksBuilder;


abstract class AbstractCategory
{
    protected $data;

    public function __construct(array $args = []) {
        $this->data = apply_filters('gutenberg-blocks-category-default-attributes', []);
        $this->data = array_merge($this->data, $args);

        if (empty($this->data['slug']) || empty($this->data['title'])) {
            return;
        }

        $this->register_hooks();
    }

    public function register_hooks() {
        add_filter('block_categories', [$this, 'create_blocks_category'], 10, 2);
    }

    public function create_blocks_category(array $categories): array {
        return array_merge($categories, [$this->data]);
    }
}