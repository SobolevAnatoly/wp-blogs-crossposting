<?php

namespace OroBlogs;

defined('ABSPATH') || exit;

if ( ! class_exists('OroBlogs')) {
    class OroBlogs
    {
        private static string $cache_group;
        private static array $cur_lang_blogs;
        private string $style_path;
        private string $style_url;


        public function __construct()
        {
            self::$cache_group = 'oro-blog-cache-group-id-' . get_current_blog_id();
            $this->set_cur_lang_blogs(get_current_blog_id());

            add_action('init', [$this, 'loadBlogsACFCustomizations']);

            add_filter('template_include', [$this, 'setBlogTemplate']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
            add_filter('pre_get_posts', [$this, 'pre_get_posts']);

            $this->loadQueryMultisite();

            $this->load_widgets();
            add_action('init', [$this, 'load_widgets_acf']);

            add_action('publish_post', [$this, 'flushBlogAndPostCache'], 10, 2);
            add_action('save_post', [$this, 'flushBlogAndPostCache'], 10, 2);

            remove_action('do_feed_rss2', 'do_feed_rss2');
            add_action('do_feed_rss2', [$this, 'customRss2Template']);
        }

        public function customRss2Template($for_comments)
        {
            if ( $for_comments ) {
                load_template( ABSPATH . WPINC . '/feed-rss2-comments.php' );
            } else {
                require_once self::getPath('/templates/') . 'feed-rss2.php';
            }
        }

        /**
         * Generate unique cache key. $prefix must be set in the calling function. for example __FUNCTION__
         *
         * @param string $prefix
         *
         * @return string
         */
        static function generateCacheKey(string $prefix): string
        {
            $prefix    = strtolower($prefix) . '_';
            $cache_key = $prefix . 'blog_' . get_current_blog_id() . '_posts_page_' . self::getPageForPostsId();

            return $cache_key;
        }

        /**
         * @return string
         */
        static function get_cache_group(): string
        {
            return self::$cache_group;
        }

        public static function get_multisite_post_meta($from_blog = null, $post_id = null, $key = null)
        {
            global $wpdb;

            if (empty($key)) {
                return null;
            }

            if (empty($from_blog)) {
                $from_blog = $wpdb->blogid;
            }
            $blog_id = absint($from_blog);

            if (empty($post_id)) {
                global $post;
                $post_id = absint($post->ID);
            }

            if (defined('MULTISITE') && (0 == $blog_id || 1 == $blog_id)) {
                $table_from = $wpdb->base_prefix . "postmeta";
            } else {
                $table_from = $wpdb->base_prefix . $blog_id . "_postmeta";
            }
            $sql_postmeta = $wpdb->prepare(
                "SELECT meta_value FROM $table_from WHERE post_id = %d AND meta_key LIKE %s",
                $post_id,
                $key
            );
            $result       = $wpdb->get_row($sql_postmeta);

            return ! empty($result) ? $result->meta_value : null;
        }

        public function flushBlogAndPostCache($post_id, $post)
        {
            $curr_blog_id  = get_current_blog_id();
            $posts_page_id = self::getPageForPostsId();
            //$cache_group   = self::get_cache_group();

            $key_blog_item = 'single_post_item_' . $post_id . '_from_blog_' . $curr_blog_id;
            if (false !== (wp_cache_get($key_blog_item, 'oro-blog-cache-group-global'))) {
                wp_cache_delete($key_blog_item, 'oro-blog-cache-group-global');
            }

            // $key_pre_get = 'pre_get_posts_exclude_posts_id_blog_' . $curr_blog_id . '_posts_page_' . $posts_page_id;
            // if (false !== (wp_cache_get($key_pre_get, $cache_group))) {
            //     wp_cache_delete($key_pre_get, $cache_group);
            // }

            clean_post_cache($post);
            clean_post_cache($posts_page_id);

            if (function_exists('w3tc_flush_post')) {
                w3tc_flush_post($post_id);
                w3tc_flush_post($posts_page_id);
            }
        }

        function pre_get_posts($query)
        {
            if ($query->is_admin()) {
                return $query;
            }

            if ($query->is_main_query() && $query->is_home() && ! $query->is_fron_page()) {
                $query->set('post_status', 'publish');
            }

            return $query;
        }


        /**
         * Get page for posts ID
         *
         * @return int
         */
        static function getPageForPostsId(): int
        {
            return absint(get_option('page_for_posts'));
        }

        /**
         * Checking is this the static page assigned to the blog posts index (posts page)
         *
         * @return bool
         */
        private function isBlogPage(): bool
        {
            if ( ! is_front_page() && is_home()) {
                return true;
            }

            return false;
        }


        /**
         * Enqueue widgets
         *
         * @return void
         */
        public function load_widgets(): void
        {
            require 'widgets/widget_categories_with_tags.php';
            require 'widgets/widget_contribute_to_blog.php';
            require 'widgets/widget_posts_banner_acf.php';
        }


        /**
         * Enqueue ACF settings for single post banner
         *
         * @return void
         */
        public function load_widgets_acf(): void
        {
            require_once 'widgets/single-post-banner/acf-configurations.php';
        }

        /**
         * Include a new template for Blog page
         *
         * @param $template
         *
         * @return mixed|string
         */
        public function setBlogTemplate($template)
        {
            if ($this->isBlogPage() || is_category()) {
                $template = self::getPath('/templates/') . 'blog-page.php';
            }
            if (is_single() && 'post' == get_post_type()) {
                $template = self::getPath('/templates/') . 'single-post.php';
            }

            return $template;
        }


        /**
         * @param $name
         *
         * @return void
         */
        public static function loadTemplatePart($name): void
        {
            $template_part = self::getPath('/templates/parts/') . $name . '.php';

            if (is_file($template_part)) {
                require $template_part;
            }
        }


        /**
         * Get blogs id's to be shown at current blog selected at blog settings page
         *
         * @return array
         */
        static function getChosenBlogsIds(): array
        {
            $key = self::generateCacheKey(__FUNCTION__);

            if (false === ($blogs_ids = wp_cache_get($key, self::$cache_group))) {
                $blogs_ids = [get_current_blog_id()];
                if (class_exists('ACF')) {
                    $values = get_field_object('field_select_blogs_to_show_posts_from', self::getPageForPostsId());
                    if ( ! empty($values['value'])) {
                        $blogs_ids = $values['value'];
                    }
                }

                wp_cache_set($key, $blogs_ids, self::$cache_group);
            }

            return $blogs_ids;
        }

        /**
         * @param $blog_id
         *
         * @return array|bool|mixed|string|void
         */
        static function getManuallySelectedPosts($blog_id = false)
        {
            if ( ! $blog_id) {
                $blog_id = get_current_blog_id();
            }
            $cache_key = self::generateCacheKey(__FUNCTION__ . '_for_blog_' . $blog_id);

            if (false === ($posts_not_in = wp_cache_get($cache_key, self::$cache_group))) {
                $chosen_blog_ids = self::getChosenBlogsIds();
                $posts_not_in    = [];
                $manual_args     = [
                    'posts_per_page' => -1,
                    'meta_query'     => [
                        [
                            'key'     => 'select_blogs_to_show_post',
                            'value'   => [''],
                            'compare' => 'NOT IN'
                        ]
                    ],
                ];
                $args            = [
                    'fields'         => 'ids',
                    'posts_per_page' => -1,

                ];

                foreach (self::$cur_lang_blogs as $single_blog_id => $value) {
                    switch_to_blog($single_blog_id);
                    $all_posts    = get_posts($args);
                    $manual_posts = get_posts($manual_args);
                    foreach ($manual_posts as $post) {
                        $for_blogs = get_post_meta($post->ID, 'select_blogs_to_show_post', true);
                        if (in_array($blog_id, (array)$for_blogs) && ! in_array($single_blog_id, $chosen_blog_ids)
                            && $single_blog_id != $blog_id) {
                            if (($key = array_search($post->ID, $all_posts)) !== false) {
                                unset($all_posts[$key]);
                            }
                            $posts_not_in[$single_blog_id] = $all_posts;
                        }
                    }
                }
                if ( ! empty($GLOBALS['_wp_switched_stack']) || $GLOBALS['switched']) {
                    switch_to_blog($blog_id);
                    $GLOBALS['_wp_switched_stack'] = [];
                    $GLOBALS['switched']           = false;
                }
                wp_cache_set($cache_key, $posts_not_in, self::$cache_group);
            }

            return $posts_not_in;
        }

        static function getSortingType()
        {
            if ( ! class_exists('ACF')) {
                return;
            }

            $values = get_field_object('field_sorting_type_key', self::getPageForPostsId());

            return $values['value'];
        }

        /**
         * Used for acf-configuration settings
         * @return array
         */
        public function get_cur_lang_blogs(): array
        {
            return self::$cur_lang_blogs;
        }

        /**
         * @param int $cur_blog
         *
         * @return void
         */
        private function set_cur_lang_blogs(int $cur_blog): void
        {
            global $wpdb;

            $blog_data = $wpdb->get_row("SELECT path, domain FROM $wpdb->blogs WHERE blog_id = '$cur_blog'");
            $cur_lang  = $this->siteLang($blog_data->path);
            $key       = self::generateCacheKey(__FUNCTION__ . '_to_' . $cur_lang);

            if (false === ($sites_for_cur_lang = wp_cache_get($key, self::$cache_group))) {
                $sites_for_cur_lang = $this->curLangSites($cur_lang, $blog_data->domain);
                wp_cache_set($key, $sites_for_cur_lang, self::$cache_group);
            }

            self::$cur_lang_blogs = $sites_for_cur_lang;
        }

        private function curLangSites($cur_lang, $cur_domain): array
        {
            global $wpdb;
            $sites     = get_sites([
                'domain' => $cur_domain,
            ]);
            $all_sites = [];
            foreach ($sites as $site) {
                $site_lang = $this->siteLang($site->path);
                if ($site_lang == $cur_lang) {
                    $select_from               = $site->blog_id > 1 ? 'wp_' . $site->blog_id . '_options' : 'wp_options';
                    $blog                      = $wpdb->get_row(
                        "SELECT option_value FROM $select_from WHERE option_name = 'blogname' LIMIT 1"
                    );
                    $all_sites[$site->blog_id] = $blog->option_value;
                }
            }

            return $all_sites;
        }

        private function siteLang($site_path): string
        {
            $cur_lang = explode('/', trim($site_path, '/'));

            return (is_array($cur_lang) && strlen($cur_lang[0]) == 2) ? $cur_lang[0] : 'global';
        }

        /**
         * @return WP_Query_Multisite
         */
        private function loadQueryMultisite(): WP_Query_Multisite
        {
            require_once 'includes/WPQueryMultisite.php';

            return new WP_Query_Multisite();
        }

        public function loadBlogsACFCustomizations()
        {
            if (file_exists(self::getPath('/acf-configuration/'))) {
                $files = new \FilesystemIterator(
                    self::getPath('/acf-configuration/'),
                    \FilesystemIterator::CURRENT_AS_PATHNAME
                );
                foreach ($files as $file) {
                    require $file;
                }
            }
        }

        public function enqueueScripts()
        {
            $this->style_url  = PUBLIC_REDESIGN_CSS_URL;
            $this->style_path = PUBLIC_REDESIGN_CSS_PATH;

            if ($this->isBlogPage() || is_category() || is_singular('post')) {
                $assets_path       = $this->style_path . '/' . get_template(
                    ) . '/plugins/oro-themes-features/modules/general/oro-blogs/less/blocks_layout.css';
                $public_assets_url = $this->style_url . '/' . get_template(
                    ) . '/plugins/oro-themes-features/modules/general/oro-blogs/less/blocks_layout.css';

                wp_enqueue_style(
                    'oro-blogs-blocks-layout',
                    $public_assets_url,
                    false,
                    filemtime($assets_path)
                );

                wp_enqueue_script('oro-blog');
            }
        }

        /**
         * @param $path
         *
         * @return string
         */
        static function getPath($path = '/'): string
        {
            return dirname(__FILE__) . $path;
        }

        /**
         * @param $path
         *
         * @return string
         */
        static function getUrl($path = '/'): string
        {
            return plugin_dir_url(__FILE__) . $path;
        }

        /**
         * @param $path
         *
         * @return string
         */
        static function getImage($image = ''): string
        {
            return plugin_dir_url(__FILE__) . 'images/' . $image;
        }

        /**
         * load sidebar template from the plugin dir
         *
         * @param $name
         *
         * @return void
         */
        static function oroBlogSidebar($name = '')
        {
            if ( ! $name) {
                return;
            }
            do_action('get_sidebar', $name);

            $name = "sidebar-$name";

            self::loadTemplatePart($name);
        }

        static function get_related_posts()
        {
            $categories = get_the_terms(get_the_ID(), 'category');

            if ( ! $categories) {
                return false;
            }
            $category_ids = [];
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            $args = [
                'category__in'        => $category_ids,
                'post__not_in'        => [get_the_ID()],
                'posts_per_page'      => 4,
                'ignore_sticky_posts' => 1,
            ];

            return new \WP_Query($args);
        }

    }
}
new OroBlogs();
