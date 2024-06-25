<?php

class WP_Widget_OROSingleBlogPostBanner extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = [
//            'classname'                   => '',
            'description'                 => __('Show banner on a single blog post'),
//            'customize_selective_refresh' => true,
        ];

        parent::__construct('oro_single_blog_post_banner',__('Single Blog Page Banner'), $widget_ops);
    }

    /**
     * Outputs the content for the current Custom HTML widget instance.
     *
     * @param  array  $args  Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param  array  $instance  Settings for the current Custom HTML widget instance.
     *
     * @since 4.8.1
     *
     * @global WP_Post $post
     */
    public function widget($args, $instance)
    {
        global $post;

        if (is_single()) {
            // Make sure post is always the queried object on singular queries (not from another sub-query that failed to clean up the global $post).
            $post = get_queried_object();
        } else {
            // Nullify the $post global during widget rendering to prevent shortcodes from running with the unexpected context on archive queries.
            $post = null;

            return;
        }

        $content_post = [
            'image' => get_field('banner_link_image', $post->ID),
            'url'   => get_field('banner_link_url', $post->ID),
            'title' => get_field('banner_link_title', $post->ID),
            'label' => get_field('banner_link_whitepaper_label', $post->ID),
        ];

        $content_post_filled = ! empty($content_post['image']) && ! empty($content_post['title']);

        $content_widget = [
            'image' => get_field('banner_link_image', 'widget_' . $args['widget_id']),
            'url'   => get_field('banner_link_url', 'widget_' . $args['widget_id']),
            'title' => get_field('banner_link_title', 'widget_' . $args['widget_id']),
            'label' => get_field('banner_link_whitepaper_label', 'widget_' . $args['widget_id']),
        ];

        $content = $content_post_filled ? $content_post : $content_widget;

        echo $args['before_widget'];

        echo '<div class="promo-banner ">';

        if ($content['url']) {
            echo '<a href="' . esc_url($content['url']) . '" target="_blank">';
        }

        // Image
        echo '<div class="promo-banner__image">';
        echo wp_get_attachment_image($content['image'], 'full');
        echo '</div>';

        echo '<div class="promo-banner__text">';


        // title
        echo '<div class="h5 promo-banner__title">' . $content['title'] . '</div>';

        // Label
        if ($content['label']) {
            echo '<p class="promo-banner__subtitle learn-more color-theme">' . $content['label'] . '</p>';
        }
        echo '</div>';



        if ($content['url']) {
            echo '</a>';
        }
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Outputs the Custom HTML widget settings form.
     *
     * @param array $instance Current instance.
     *
     * @returns void
     * @since 4.9.0 The form contains only hidden sync inputs. For the control UI, see `WP_Widget_Custom_HTML::render_control_template_scripts()`.
     *
     * @see WP_Widget_Custom_HTML::render_control_template_scripts()
     * @since 4.8.1
     */
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

    /**
     * Handles updating settings for the current Custom HTML widget instance.
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     *
     * @return void|array Settings to save or bool false to cancel saving.
     * @since 4.8.1
     *
     */
    public function update($new_instance, $old_instance)
    {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);

        return $instance;
    }
}

function load_WP_Widget_OROSingleBlogPostBanner_widget()
{
    register_widget('WP_Widget_OROSingleBlogPostBanner');
}

add_action('widgets_init', 'load_WP_Widget_OROSingleBlogPostBanner_widget');