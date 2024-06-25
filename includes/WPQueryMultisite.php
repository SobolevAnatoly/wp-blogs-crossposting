<?php

namespace OroBlogs;

class WP_Query_Multisite
{

    private array $sites_to_query;

    function __construct()
    {
        add_filter('query_vars', [$this, 'query_vars']);
        add_action('pre_get_posts', [$this, 'pre_get_posts'], 100);
        add_filter('posts_clauses', [$this, 'posts_clauses'], 10, 2);
        add_filter('posts_request', [$this, 'posts_request'], 10, 2);
        add_action('the_post', [$this, 'the_post']);
        add_action('loop_end', [$this, 'loop_end']);
    }

    function query_vars($vars)
    {
        $vars[] = 'multisite';
        $vars[] = 'sites__not_in';
        $vars[] = 'sites__in';
        $vars[] = 'post__not_in';

        return $vars;
    }

    function pre_get_posts($query)
    {
        if ( ! $query->is_main_query() || is_admin()) {
            return;
        }
        if ($query->is_home() || $query->is_category()) {
            global $wpdb, $blog_id;

            $chosen_blog_ids = OroBlogs::getChosenBlogsIds();
            // $key             = OroBlogs::generateCacheKey(__FUNCTION__ . '_exclude_posts_id');

            // if (false === ($exclude_posts_id = wp_cache_get($key, OroBlogs::get_cache_group()))) {
            $manually_selected_posts = OroBlogs::getManuallySelectedPosts($blog_id);

            $exclude_posts_id = [];
            if ( ! empty($manually_selected_posts)) {
                $chosen_blog_ids = array_merge($chosen_blog_ids, array_keys($manually_selected_posts));
                foreach ($manually_selected_posts as $posts_item) {
                    $exclude_posts_id = array_merge($exclude_posts_id, array_values($posts_item));
                }
            }
            //   wp_cache_set($key, $exclude_posts_id, OroBlogs::get_cache_group());
            //}

            $query->set('multisite', 1);
            if ( ! empty($chosen_blog_ids)) {
                $query->set('sites__in', $chosen_blog_ids);
            }
            if ( ! empty($exclude_posts_id)) {
                $query->set('post__not_in', $exclude_posts_id);
            }

            if ($query->get('multisite')) {
                $this->blog_id = $blog_id;

                $site_IDs = $wpdb->get_col("select blog_id from $wpdb->blogs");

                if ($query->get('sites__not_in')) {
                    foreach ($site_IDs as $key => $site_ID) {
                        if (in_array($site_ID, $query->get('sites__not_in'))) {
                            unset($site_IDs[$key]);
                        }
                    }
                }

                if ($query->get('sites__in')) {
                    foreach ($site_IDs as $key => $site_ID) {
                        if ( ! in_array($site_ID, $query->get('sites__in'))) {
                            unset($site_IDs[$key]);
                        }
                    }
                }

                $site_IDs = array_values($site_IDs);

                // Changing the sequence of blogs in the order of addition
                if ( ! OroBlogs::getSortingType() && ! empty($query->get('sites__in'))) {
                    $site_IDs = $query->get('sites__in');
                }

                $this->sites_to_query = $site_IDs;
            }
        }
    }

    function posts_clauses($clauses, $query)
    {
        if ($query->get('multisite')) {
            global $wpdb, $blog_id;
            $current_object = get_queried_object();
            // Start new mysql selection to replace wp_posts on posts_request hook
            $this->ms_select = [];

            $root_site_db_prefix = $wpdb->prefix;

            $need_order = false;
            if ( ! OroBlogs::getSortingType()) {
                $need_order = true;
            }
            // $paged        = (get_query_var('paged')) ? 'pagination_page_' . get_query_var('paged') :
            //     'pagination_page_1';
            // $query_params = is_category() ?
            //     'term_id_' . $current_object->term_id . '_slug_' . $current_object->slug . '_' . $paged :
            //     'page_id_' . $current_object->ID . '_' . $paged;
            // $key          = OroBlogs::generateCacheKey(__FUNCTION__ . '_' . $query_params);

            // if (false === ($this->ms_select = wp_cache_get($key, OroBlogs::get_cache_group()))) {
            foreach ($this->sites_to_query as $site_ID) {
                switch_to_blog($site_ID);

                $ms_select = $clauses['join'] . ' WHERE 1=1 ' . $clauses['where'];

                if ($clauses['groupby']) {
                    $ms_select .= ' GROUP BY ' . $clauses['groupby'];
                }

                $order_by = $need_order ? " ORDER BY $wpdb->posts.post_date DESC LIMIT 99999999" : '';

                $ms_select = str_replace($root_site_db_prefix, $wpdb->prefix, $ms_select);
                $ms_select = "(SELECT $wpdb->posts.*, '$site_ID' AS site_ID FROM $wpdb->posts $ms_select $order_by)";

                //Change term_taxonomy_id for right id from product on multisite.
                if (is_category()) {
                    $multisite_object = get_category_by_slug($query->get('category_name'));
                    if ($multisite_object instanceof \WP_Term && $multisite_object != $current_object) {
                        $ms_select = str_replace(
                            "term_taxonomy_id IN ($current_object->term_taxonomy_id)",
                            "term_taxonomy_id IN ($multisite_object->term_taxonomy_id)",
                            $ms_select
                        );
                    }
                }
                $this->ms_select[] = $ms_select;
                restore_current_blog();
            }
            //   wp_cache_set($key, $this->ms_select, OroBlogs::get_cache_group());
            //}

            // Clear join, where and groupby to populate with parsed ms select on posts_request hook;
            $clauses['join']    = '';
            $clauses['where']   = '';
            $clauses['groupby'] = '';
            $clauses['orderby'] = str_replace($wpdb->posts, 'tables', $clauses['orderby']);

            // Orderby for tables (not wp_posts)
            // If false Changing the sequence of blogs in the order of addition
            if ( ! OroBlogs::getSortingType()) {
                $clauses['orderby'] = '';
            }
        }

        return $clauses;
    }

    function posts_request($sql, $query)
    {
        if ($query->get('multisite')) {
            global $wpdb;
            // Multisite request
            $origin = preg_replace('|[\r\n\t ]+|', ' ', $sql);
            $sql    = str_replace(
                "$wpdb->posts.* FROM $wpdb->posts",
                'tables.* FROM ( ' . implode(" UNION ", $this->ms_select) . ' ) tables',
                $origin
            );
        }

        return $sql;
    }

    function the_post($post)
    {
        global $blog_id;

        if (isset($this->loop_end) && ! $this->loop_end && $post->site_ID && $blog_id !== $post->site_ID) {
            switch_to_blog($post->site_ID);
        }
    }

    function loop_end()
    {
        restore_current_blog();
    }
}