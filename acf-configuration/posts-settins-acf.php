<?php

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key'                   => 'group_oro_blog_settings_for_posts',
        'title'                 => 'Posts Settings',
        'fields'                => [
            [
                'key'               => 'field_select_blogs_to_show_post_on',
                'label'             => 'Select blogs',
                'name'              => 'select_blogs_to_show_post',
                'type'              => 'select',
                'instructions'      => 'Select blogs where you want to show this post',
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
                'key'               => 'field_select_post_thumb_decoration',
                'label'             => 'Select Image Decoration',
                'name'              => 'select_image_decor',
                'type'              => 'select',
                'choices'           => [
                    'decoration1' => 'Type 1',
//                    'decoration2' => 'Type 2'
                ],
                'default_value'     => [
                    'decoration1'
                ],
                'allow_null'        => 0,
                'multiple'          => 0,
                'ui'                => 1,
                'ajax'              => 0,
                'return_format'     => 'key',
            ],
        ],
        'location'              => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
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
