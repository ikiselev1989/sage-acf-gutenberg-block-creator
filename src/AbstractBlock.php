<?php

namespace IK89\SageAcfGutenbergBlocksBuilder;

use StoutLogic\AcfBuilder\FieldsBuilder;

abstract class AbstractBlock
{
    const TEMPLATE_NAME = '';

    protected $data = [
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

    /* @var FieldsBuilder $block_fields */
    protected $block_fields;

    public function __construct(array $args = []) {
        $this->data = array_merge($this->data, $args);

        $this->data['render_callback'] = [$this, 'render_block'];

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

    public function render_block(array $block) {
        $template_data = ['block' => $block];
        $fields = get_fields() ?: [];

        echo \App\template(static::template_path($block), array_merge($template_data, $fields));
    }

    protected static function template_path(array $block) {
        $template_slug = static::TEMPLATE_NAME != '' ? static::TEMPLATE_NAME : strtolower($block['name']);

        $root = apply_filters('gutenberg-blocks-templates-root', '');

        return $root . '.' . $block['category'] . '.' . str_replace('acf/', '', $template_slug);
    }

    abstract function add_fields();
}

