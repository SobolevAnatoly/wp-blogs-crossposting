<?php

add_filter('widget_display_callback', 'categories_widget_custom_title', 10, 3);

if (is_active_sidebar('blog-sidebar') && function_exists('dynamic_sidebar')) {
    dynamic_sidebar('blog-sidebar');
}

function categories_widget_custom_title($instance, $widget, $args)
{
    if ($widget->id_base == 'categories_oroinc') {
        global $page;
        $blog_page_id = get_option('page_for_posts');

        if (is_category()) {
            $title_header = single_cat_title('', false) ?? get_the_title($blog_page_id);
        }
        if ( ! is_front_page() && is_home()) {
            $title_header = get_field('blog_title', $blog_page_id) ?? get_the_title($blog_page_id);
        }

        $title_header = !empty($title_header) ? $title_header : $instance['title'];
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        ! empty ($page) && 1 < $page && $paged = $page;
        if (get_locale() == 'de_DE'){
            $paged > 1 && $title_header .= ' | ' . sprintf(__('Seite %s'), $paged);
        } else{
            $paged > 1 && $title_header .= ' | ' . sprintf(__('Page %s'), $paged);
        };
        $instance['title'] = $title_header;
    }

    return $instance;
}