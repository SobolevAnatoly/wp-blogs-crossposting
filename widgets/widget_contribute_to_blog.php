<?php
if (!class_exists('WP_Widget_OROContributeToBlog')):
    class WP_Widget_OROContributeToBlog extends WP_Widget
    {
        public function __construct()
        {
            parent::__construct(
                'oro_contribute_to_blog', // Base ID
                __('Contribute to Blog widget', 'text_domain'), // Name
                array('description' => __(''))
            );
        }

        /**
         * Outputs the content for the current Custom HTML widget instance.
         *
         * @param array $args Display arguments including 'before_title', 'after_title',
         *                        'before_widget', and 'after_widget'.
         * @param array $instance Settings for the current Custom HTML widget instance.
         *
         * @since 4.8.1
         *
         * @global WP_Post $post
         */
        public function widget($args, $instance)
        {
            $link = get_field('widget_contribute_link', 'widget_' . $args['widget_id']);

            echo $args['before_widget'];

            $title = apply_filters( 'widget_title', $instance['title'] );

            echo '<div class="contribute">';
            echo '<h5>';
            echo $title;
            echo '</h5>';
            if ($link && isset($link['url'])) {
                $target = (isset($link['target']) && $link['target'] != '') ? 'target="' . $link['target'] . '"' : '';

                echo '<a href="' . $link['url'] . '" ' . $target . ' class="learn-more color-theme">'.$link['title'].'</a>';
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

    function load_WP_Widget_OROContributeToBlog_widget()
    {
        register_widget('WP_Widget_OROContributeToBlog');
    }

    add_action('widgets_init', 'load_WP_Widget_OROContributeToBlog_widget');
endif;
