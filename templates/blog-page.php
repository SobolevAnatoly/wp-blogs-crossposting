<?php

/**
 * Template Name: Blog Page
 */

use OroBlogs\OroBlogs;

$real_blog_id      = get_current_blog_id();
$blogs_IDs         = OroBlogs::getChosenBlogsIds();
$blog_page_id      = get_option('page_for_posts');
$key_prefix_unique = 'page_id_' . get_the_ID();
$category          = false;
$title_header      = 'Oro Blog';

if (is_category()) {
    $title_header      = single_cat_title('', false) ?? get_the_title($blog_page_id);
    $category          = get_category(get_query_var('cat'));
    $key_prefix_unique = 'term_id_' . $category->term_id;
}
if ( ! is_front_page() && is_home()) {
    $title_header = get_field('blog_title', $blog_page_id) ?? get_the_title($blog_page_id);
}

get_header();
?>

<?php
OroBlogs::loadTemplatePart('top-contact-banner'); ?>

<section class="blog__intro">
    <?php
    OroBlogs::oroBlogSidebar('blog'); ?>
</section>

<?php
$featured_post_id = apply_filters('get_featured_posts', false);

if ($featured_post_id) {
    $featured_prefix = 'featured_post_id_' . $featured_post_id . '_featured_query_for_' . $key_prefix_unique;
    $key_featured    = OroBlogs::generateCacheKey($featured_prefix);

    if (false === ($featured_post_query = wp_cache_get($key_featured, OroBlogs::get_cache_group()))) {
        $featured_args = ['posts_per_page' => 1,];
        if ( ! empty($blogs_IDs)) {
            $featured_args['multisite'] = 1;
            $featured_args['sites__in'] = $blogs_IDs;
        }

        $featured_args['post__in'] = [$featured_post_id];
        $featured_post_query       = new WP_Query($featured_args);

        wp_cache_set($key_featured, $featured_post_query, OroBlogs::get_cache_group());
    }

    if ($featured_post_query->have_posts()) {
        while ($featured_post_query->have_posts()) {
            $featured_post_query->the_post();
            $key_featured_post_item = 'featured_post_item_' . $post->ID . '_from_blog_' . $post->site_ID;
            if (false === ($featured_item_html = wp_cache_get(
                    $key_featured_post_item,
                    'oro-blog-cache-group-global'
                ))) {
                switch_to_blog($post->site_ID);
                ob_start();
                OroBlogs::loadTemplatePart('content-feature-post');
                $featured_item_html = ob_get_clean();
                restore_current_blog();

                wp_cache_set($key_featured_post_item, $featured_item_html, 'oro-blog-cache-group-global');
            }
            echo $featured_item_html;
        }
    }
    wp_reset_postdata();
    wp_reset_query();
}
?>

<section class="blog__subscribe">
    <div class="container">
        <?php
        if (have_rows('field_subscribe_key', $blog_page_id)):
            while (have_rows('field_subscribe_key', $blog_page_id)) : the_row(); ?>
                <?php
                $subscribe_title = get_sub_field('field_subscribe_title_key', $blog_page_id) ?>
                <h3 class="h2"><?= $subscribe_title ?></h3>
                <div class="content">
                    <div class="text">
                        <p><?= get_sub_field('field_subscribe_text_key', $blog_page_id) ?></p>
                    </div>
                    <?php
                    OroBlogs::oroBlogSidebar('subscribe'); ?>
                </div>
            <?php
            endwhile;
        endif;
        ?>
    </div>
</section>

<section class="blog__article-list">
    <div class="container">
        <?php
        $prefix_unique     = 'wp_query_for_' . $key_prefix_unique;
        $key_blog_wp_query = OroBlogs::generateCacheKey(
            $prefix_unique . '_pagination_number_' . get_query_var('paged') ?: 1
        );

        if (false === ($wp_transient_query = wp_cache_get($key_blog_wp_query, OroBlogs::get_cache_group()))) {
            $manually_selected_posts = OroBlogs::getManuallySelectedPosts($real_blog_id);
            $exclude_posts_id        = [];

            if ( ! empty($manually_selected_posts)) {
                $blogs_IDs = array_merge(array_keys($manually_selected_posts), (array)$blogs_IDs);
                foreach ($manually_selected_posts as $posts_item) {
                    $exclude_posts_id = array_merge($exclude_posts_id, array_values($posts_item));
                }
            }
            $args = [
                'post_type' => 'post',
                'paged'     => get_query_var('paged') ?: 1,
            ];

            if ( ! empty($blogs_IDs)) {
                $args['multisite'] = 1;
                $args['sites__in'] = $blogs_IDs;

                if ( ! empty($exclude_posts_id)) {
                    $args['post__not_in'] = $exclude_posts_id;
                }
            }

            $args['post_status'] = ['publish'];

            if (is_category() && ! empty($category)) {
                $args['category_name'] = $category->slug;
            }

            $wp_transient_query = new WP_Query($args);

            wp_cache_set($key_blog_wp_query, $wp_transient_query, OroBlogs::get_cache_group());
        }

        if ($wp_transient_query->have_posts()) { ?>
            <div class="wrapper">
                <?php
                while ($wp_transient_query->have_posts()) {
                    $wp_transient_query->the_post();
                    //$key_blog_item = 'single_post_item_' . $post->ID . '_from_blog_' . $post->site_ID;
                    //if (false === ($blog_item_html = wp_cache_get($key_blog_item, 'oro-blog-cache-group-global'))) {
                    if ( ! empty($post->site_ID)) {
                        switch_to_blog($post->site_ID);
                        // ob_start();
                        OroBlogs::loadTemplatePart('content-blog-item');
                        //  $blog_item_html = ob_get_clean();
                        restore_current_blog();
                    }
                    //  wp_cache_set($key_blog_item, $blog_item_html, 'oro-blog-cache-group-global');
                    // }
                    //  echo $blog_item_html;
                } ?>
            </div>
            <?php
            do_action('oro_wp_paginate');
        } else {
            ?>
            <div class="wrapper">
                <?php
                get_template_part('content', 'none'); ?>
            </div>
            <?php
        }

        wp_reset_postdata();
        wp_reset_query();
        ?>
    </div>
</section>

<section class="blog__join">
    <div class="container">
        <h3 class="h2"><?php
            echo $title_header; ?></h3>
        <div class="wrapper">
            <div class="text">
                <p>
                    <?php
                    $message = get_post_meta($blog_page_id, 'sub_title', true);
                    if (is_category() && category_description()) {
                        $message = category_description();
                    }
                    echo $message;
                    ?>
                </p>
            </div>
            <div class="blog__join-info">
                <?php
                dynamic_sidebar('single-blog-bottom-sidebar'); ?>
            </div>

        </div>
    </div>
</section>
<?php
get_footer(); ?>
