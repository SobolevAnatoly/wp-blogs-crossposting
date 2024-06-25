<?php

use OroBlogs\OroBlogs;

if ( ! class_exists('WP_Widget_Categories_With_Tags')):
    class WP_Widget_Categories_With_Tags extends WP_Widget
    {
        public function __construct()
        {
            $widget_ops = [
                'classname'                   => '',
                'description'                 => __('A list or dropdown of categories with tags.'),
                'customize_selective_refresh' => true,
            ];
            parent::__construct('categories_oroinc', __('Categories OroINC'), $widget_ops);
        }

        /**
         * @return array|false|mixed|void
         */
        public function get_blogs_ids()
        {
            return OroBlogs::getChosenBlogsIds();
        }

        public function widget($args, $instance)
        {
            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = apply_filters(
                'widget_title',
                empty($instance['title']) ? __('Categories') : $instance['title'],
                $instance,
                $this->id_base
            );

            echo $args['before_widget'];
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            $products_categories = [];

            if(is_array($this->get_blogs_ids())){
                foreach ($this->get_blogs_ids() as $product_id) {
                    switch_to_blog($product_id);
                    $product_categories = get_categories([
                        'hide_empty' => true,
                        'taxonomy'   => 'category',
                    ]);

                    foreach ($product_categories as $product_category) {
                        $products_categories[$product_category->term_id] = $product_category->slug;
                    }
                    restore_current_blog();
                }
            }

            $products_categories = array_unique($products_categories);
            $categories_slugs    = array_values($products_categories);
            $categories_show     = [];

            foreach ($categories_slugs as $category_slug) {
                $idObj = get_category_by_slug($category_slug);

                if ($idObj instanceof WP_Term) {
                    $categories_show[] = $idObj->term_id;
                }
            }

            $cat_args = [
                'orderby'      => 'name',
                'show_count'   => 0,
                'hierarchical' => 0,
            ];

            if ( ! empty($categories_show)) {
                $cat_args['hide_empty'] = 0;
                $cat_args['include']    = $categories_show;
            }

            add_filter('get_terms', [$this, 'remove_empty'], 10, 4);
            ?>
            <ul class="blog__categories">
                <?php
                $cat_args['title_li'] = '';
                wp_list_categories(apply_filters('widget_categories_args', $cat_args));
                ?>
            </ul>
            <?php
            remove_filter('get_terms', [$this, 'remove_empty'], 10, 4);
            echo $args['after_widget'];
        }

        public function remove_empty($terms, $taxonomy_var, $query_vars, $term_query)
        {
            $current_tag = [];
            if (is_single()) {
                if (has_tag('OroCommerce')) {
                    array_push($current_tag, 'orocommerce');
                }
                if (has_tag('OroCRM')) {
                    array_push($current_tag, 'orocrm');
                }
            } else {
                $tag = get_query_var('tag', '');
                if ( ! empty($tag)) {
                    array_push($current_tag, $tag);
                }
            }

            if ( ! empty($current_tag) && ! empty($terms)) {
                foreach ($terms as $index => $term) {
                    $args  = [
                        'post_type'      => 'post',
                        'tax_query'      => [
                            'relation' => 'AND',
                            [
                                'taxonomy' => 'category',
                                'field'    => 'slug',
                                'terms'    => [$term->slug],
                            ],
                            [
                                'taxonomy' => 'post_tag',
                                'field'    => 'slug',
                                'terms'    => $current_tag,
                            ],
                        ],
                        'fields'         => 'ids',
                        'posts_per_page' => 1
                    ];
                    $query = new WP_Query($args);
                    if ($query->found_posts < 1) {
                        unset($terms[$index]);
                    }
                }
            }

            return $terms;
        }

        public function update($new_instance, $old_instance)
        {
            $instance          = $old_instance;
            $instance['title'] = sanitize_text_field($new_instance['title']);

            return $instance;
        }

        public function form($instance)
        {
            $instance = wp_parse_args((array)$instance, ['title' => '']);
            $title    = sanitize_text_field($instance['title']);
            ?>
            <p><label for="<?php
                echo $this->get_field_id('title'); ?>"><?php
                    _e('Title:'); ?></label>
                <input class="widefat" id="<?php
                echo $this->get_field_id('title'); ?>" name="<?php
                echo $this->get_field_name('title'); ?>" type="text" value="<?php
                echo esc_attr($title); ?>"/>
            </p>
            <?php
        }

    }

    function wpb_load_widget()
    {
        register_widget('WP_Widget_Categories_With_Tags');
    }

    add_action('widgets_init', 'wpb_load_widget');

endif;
