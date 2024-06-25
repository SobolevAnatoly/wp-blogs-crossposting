<?php

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key'                   => 'group_oro_blog_settings',
        'title'                 => 'Blog Settings',
        'fields'                => [
            [
                'key'               => 'field_select_blogs_to_show_posts_from',
                'label'             => 'Select blogs to show posts from',
                'name'              => 'select_blogs_to_show_posts_from',
                'type'              => 'select',
                'instructions'      => 'Select blogs to display posts',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => [
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ],
                'choices'           => $this->get_cur_lang_blogs(),
                'default_value'     => [],
                'allow_null'        => 0,
                'multiple'          => 1,
                'ui'                => 1,
                'ajax'              => 0,
                'return_format'     => 'value',
                'placeholder'       => '',
            ],
            [
                'key'               => 'field_sorting_type_key',
                'label'             => 'Sorting type',
                'name'              => 'posts_sorting_type',
                'type'              => 'true_false',
                'instructions'      => 'If the option is enabled, posts will be sorted by date in descending order from newest to oldest
If the option is disabled, posts will be sorted in the order they were added to the field above.',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'message'           => '',
                'default_value'     => 1,
                'ui'                => 1,
                'ui_on_text'        => 'By Date',
                'ui_off_text'       => 'By Order',
            ]
        ],
        'location'              => [
            [
                [
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'posts_page',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
        'description'           => '',
    ]);
}
