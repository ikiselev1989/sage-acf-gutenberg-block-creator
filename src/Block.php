<?php

namespace ikiselev1989\SageAcfGutenbergBlockCreator;

use StoutLogic\AcfBuilder\FieldsBuilder;

abstract class Block
{
    const BLOCK_FIELDS_PREFIX = 'GB_';

    protected static $name;
    protected static $title;
    protected static $description;
    protected static $icon;

    protected $data = [];

    protected $post_types = ['page', 'post'];

    /* @var FieldsBuilder $block_fields */
    protected $block_fields;

    public function __construct(BlocksCategory $category, array $args = []) {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        if (!isset(static::$name) || !isset(static::$title)) {
            return;
        }

        $this->data['post_types'] = $this->post_types ?? '';

        $this->data['category'] = $category::blocks_category_slug();

        $this->data['name'] = $this->data['category'] . '_' . strtolower(static::$name);

        $this->data['title'] = static::$title;

        $this->data['description'] = static::$description ?? '';

        $this->data['icon'] = static::$icon ?? '';

        $this->data['render_callback'] = function () use ($category) {
            if (class_exists('App')) {
                echo \App\template($category::BLOCKS_DIR . '.' . strtolower(static::$name), $this->parse_template_data());
            }
        };

        $this->data = array_merge($this->data, $args);

        $this->register_hooks();
    }

    public function register_hooks() {
        add_action('acf/init', [$this, 'register_acf_block_types']);
        add_action('acf/init', [$this, 'register_acf_blocks_fields']);
    }

    public function register_acf_block_types() {
        acf_register_block_type($this->data);
    }

    public function register_acf_blocks_fields() {
        $validated_block_data = acf_validate_block_type($this->data);

        $this->block_fields = new FieldsBuilder($this->data['name']);
        $this->block_fields->setLocation('block', '==', $validated_block_data['name']);

        $this->add_fields();

        acf_add_local_field_group($this->block_fields->build());
    }

    public function parse_template_data(): array {
        $data = [];

        $fields = $this->block_fields->getFields();

        foreach ($fields as $field) {
            $data[$field->getName()] = get_field($field->getName());
        }

        return $data;
    }

    abstract function add_fields();
}

