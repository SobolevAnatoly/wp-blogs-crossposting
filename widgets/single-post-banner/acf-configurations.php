<?php
// For each post
acf_add_local_field_group(array (
    'key' => 'group_5901bcdf0post',
    'title' => 'Sidebar Widget',
    'fields' => array (
        [
            'key' => 'field_5a99331da685asd',
            'label' => __('Image', 'oro-acf-template'),
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'key' => 'field_5a99331da878image',
            'label' => __('Image', 'oro-acf-template'),
            'name' => 'banner_link_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'id',
            'preview_size' => 'medium_large',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ],
        [
            'key' => 'field_5a99331da685dsazxc',
            'label' => __('Link', 'oro-acf-template'),
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'key' => 'field_5a99331da685url',
            'label' => __('Link Url', 'oro-acf-template'),
            'name' => 'banner_link_url',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
        ],
        [
            'key' => 'field_5a99331da685text',
            'label' => __('Link Title', 'oro-acf-template'),
            'name' => 'banner_link_title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
        ],
        [
            'key' => 'field_5a99331da685label',
            'label' => __('Whitepaper Label', 'oro-acf-template'),
            'name' => 'banner_link_whitepaper_label',
            'type' => 'text',
            'default' => '',
            'instructions' => '',
            'required' => 0,
        ],
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
    ),
));

// for widget
acf_add_local_field_group(array (
    'key' => 'group_5901bcdf0widget',
    'title' => 'Sidebar Widget',
    'fields' => array (
        [
            'key' => 'field_5a99331da685asd',
            'label' => __('Image', 'oro-acf-template'),
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'key' => 'field_5a99331da878image',
            'label' => __('Image', 'oro-acf-template'),
            'name' => 'banner_link_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'return_format' => 'id',
            'preview_size' => 'medium_large',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ],
        [
            'key' => 'field_5a99331da685dsazxc',
            'label' => __('Link', 'oro-acf-template'),
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'placement' => 'top',
            'endpoint' => 0,
        ],
        [
            'key' => 'field_5a99331da685url',
            'label' => __('Link Url', 'oro-acf-template'),
            'name' => 'banner_link_url',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
        ],
        [
            'key' => 'field_5a99331da685text',
            'label' => __('Link Title', 'oro-acf-template'),
            'name' => 'banner_link_title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
        ],
        [
            'key' => 'field_5a99331da685label',
            'label' => __('Whitepaper Label', 'oro-acf-template'),
            'name' => 'banner_link_whitepaper_label',
            'type' => 'text',
            'default' => '',
            'instructions' => '',
            'required' => 0,
        ],
    ),
    'location' => array (
        array(
            array (
                'param' => 'widget',
                'operator' => '==',
                'value' => 'oro_single_blog_post_banner',
            ),
        )
    ),
));


// for widget
acf_add_local_field_group(array (
    'key' => 'group_5901bcdf0widgetorocontribute',
    'title' => 'Sidebar Widget',
    'fields' => array (
        [
            'key' => 'field_5a99331da685contribute_link',
            'label' => __('Link', 'oro-acf-template'),
            'name' => 'widget_contribute_link',
            'type' => 'link',
            'default' => '',
            'instructions' => '',
            'required' => 0,
        ],
    ),
    'location' => array (
        array(
            array (
                'param' => 'widget',
                'operator' => '==',
                'value' => 'oro_contribute_to_blog',
            ),
        )
    ),
));
