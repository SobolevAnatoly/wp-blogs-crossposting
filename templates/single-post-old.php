<?php
get_header();
$blog_heading = __('OroCommerce Blog', 'commerce');

if(get_current_blog_id() == 1){
    $blog_heading = __('Oro Inc Blog', 'commerce');
}
if(get_current_blog_id() == 8) {
    $blog_heading = __('OroCRM Blog', 'commerce');
}
if(get_current_blog_id() == 23) {
    $blog_heading = __('OroMarketplace Blog', 'commerce');
}
?>
<div class="blog">

    <?php
    get_template_part('contact', 'banner'); ?>
    <div class="container">
        <div class="blog__navigation">
            <a class="pull-right" href="<?php
            echo esc_url(get_feed_link()); ?>" target="_blank"><i class="fa fa-feed"></i><span><?php
                    _e('RSS', 'commerce'); ?></span></a>
        </div>
        <div class="row blog__single-container">
            <?php
            $category        = get_the_category();
            $current_cat_url = '';
            if ($category) {
                $current_cat_url = get_category_link($category[0]->term_id);
            }
            ?>
            <div class="blog_wide-col col-xs-12 col-sm-4 blog__single-container_sidebar" data-current-cat="<?php
            echo $current_cat_url; ?>">
                <div class="blog__slider">
                    <div class="blog__title h1">
                        <?php echo $blog_heading; ?>
                    </div>
                    <?php get_sidebar('single-blog'); ?>
                </div>
            </div>
            <?php
            if (have_posts()) : ?>
                <?php
                while (have_posts()) : the_post(); ?>
                    <div class="blog_wide-col col-xs-12 col-sm-8 blog__single-container_thumbnail">
                        <div class="blog__single-post">
                            <article id="post-<?php
                            the_ID(); ?>" <?php
                            post_class(); ?>>
                                <div class="blog blog__single-post">
                                    <?php
                                    if (get_the_post_thumbnail()): ?>
                                        <div class="main-image post-thumbnail">
                                            <?php
                                            the_post_thumbnail('large'); ?>
                                        </div>
                                    <?php
                                    else: ?>
                                        <div class="default-image post-thumbnail">
                                            <picture>
                                                <source srcset="<?php
                                                echo get_template_directory_uri(); ?>/images/main-image-blog_lg.png"
                                                        media="(min-width: 769px)">
                                                <source srcset="<?php
                                                echo get_template_directory_uri(); ?>/images/main-image-blog_md.png"
                                                        media="(min-width: 480px)">
                                                <img srcset="<?php
                                                echo get_template_directory_uri(); ?>/images/main-image-blog_sm.png"
                                                     class="wp-post-image" alt="blog">
                                            </picture>
                                        </div>
                                    <?php
                                    endif; ?>
                                    <div class="blog__single-post_title-block">

                                        <div class="blog__feature-post_subtitle">
                                            <h1 class="blog__single-post_title-text entry-title">
                                                <?php
                                                the_title(); ?>
                                            </h1>
                                            <div class="blog__single-post_information-block">
                                                    <span class="blog__single-post_information-item information-item create-date">
                                                     <?php
                                                     $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
                                                     if (get_the_time('U') !== get_the_modified_time('U')) {
                                                         $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
                                                     }
                                                     printf(
                                                         $time_string,
                                                         esc_attr(get_the_date('c')),
                                                         get_the_date(),
                                                         esc_attr(get_the_modified_date('c')),
                                                         get_the_modified_date()
                                                     );
                                                     ?>
                                                    </span>
                                                <span class="blog__single-post_information-item information-item category">
                                                        <?php
                                                        $categories = get_the_category(); ?>
                                                        <a href="<?php
                                                        echo esc_url(get_category_link($categories[0]->term_id)); ?>"><?php
                                                            echo esc_html($categories[0]->name); ?></a>
                                                    </span>
                                                <span class="blog__feature-post_information-item information-item author vcard">
                                                        <span class="nickname fn"><?php
                                                            echo get_the_author(); ?></span>
                                                    </span>
                                            </div>

                                        </div>
                                        <div class="blog__single-post_social-icons">
                                            <a href="http://facebook.com/sharer.php?u=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;t=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-facebook"
                                               target="_blank" title="Share on Facebook">
                                                <svg class="social-icon">
                                                    <use xlink:href="<?php
                                                    echo get_template_directory_uri(
                                                    ); ?>/images/sprite-social.svg#facebook-1"></use>
                                                </svg>
                                            </a>
                                            <a href="http://twitter.com/intent/tweet?url=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;text=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-twitter"
                                               target="_blank" title="Share on Twitter">
                                                <svg class="social-icon">
                                                    <use xlink:href="<?php
                                                    echo get_template_directory_uri(
                                                    ); ?>/images/sprite-social.svg#twitter-1"></use>
                                                </svg>
                                            </a>
                                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;title=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-linkedin"
                                               target="_blank" title="Share on LinkedIn">
                                                <svg class="social-icon">
                                                    <use xlink:href="<?php
                                                    echo get_template_directory_uri(
                                                    ); ?>/images/sprite-social.svg#linkedin-1"></use>
                                                </svg>
                                            </a>
                                            <div class="blog__single-post_social-icon social-share-copylink"
                                                 title="Copy the link to clipboard">
                                                <input type="text" value="<?php
                                                echo get_permalink(); ?>" class="copylink">
                                                <svg class="social-icon">
                                                    <use xlink:href="<?php
                                                    echo get_template_directory_uri(
                                                    ); ?>/images/sprite-social.svg#copy"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-8 blog__single-container_content">
                        <article class="content entry-content">
                            <?php
                            if (get_field('field_839e0e768362')): ?>
                                <?php
                                do_action('createOROACFContent'); ?>
                            <?php
                            else: ?>
                                <?php
                                the_content(); ?>

                            <?php
                            endif; ?>
                            <?php
                            do_action('oro-comments'); ?>
                        </article>
                    </div>
                <?php
                endwhile; ?>
            <?php
            else : ?>
                <?php
                get_template_part('content', 'none'); ?>
            <?php
            endif; ?>
        </div>
    </div>
    <div class="usual-block usual-dark-gray blog__related-js">
        <?php
        if ($categories = get_the_terms($post->ID, 'category')): ?>
            <?php
            $category_ids = [];
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            $args                = [
                'category__in'        => $category_ids,
                'post__not_in'        => [$post->ID],
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => 1,
            ];
            $related_posts_query = new WP_Query($args);
            if ($related_posts_query->have_posts()):?>
                <div class="container">
                    <div class="blog__related-posts">
                        <div class="h2 text-center blog__related-posts_title"><?php
                            _e('Related Posts', 'commerce'); ?></div>
                        <?php
                        while ($related_posts_query->have_posts()) : $related_posts_query->the_post(); ?>
                            <div class="blog__related-posts_item">
                                <?php
                                if (get_the_post_thumbnail()): ?>
                                    <div class="thumbnail">
                                        <a href="<?php
                                        the_permalink() ?>" title="<?php
                                        the_title(); ?>">
                                            <?php
                                            the_post_thumbnail(); ?>
                                        </a>
                                    </div>
                                <?php
                                endif; ?>
                                <div class="blog__post_title">
                                    <a href="<?php
                                    the_permalink(); ?>" title="<?php
                                    the_title(); ?>"><?php
                                        the_title(); ?></a>
                                </div>
                                <div class="info">
                                        <span class="blog__post_information-item information-item create-date">
                                            <?php
                                            printf(
                                                '<time datetime="%3$s">%4$s</time>',
                                                esc_url(get_permalink()),
                                                esc_attr(get_the_time()),
                                                esc_attr(get_the_date('c')),
                                                esc_html(get_the_date())
                                            ); ?>
                                        </span>
                                    <span class="blog__post_information-item information-item category">
                                            <?php
                                            $categories = get_the_category(); ?>
                                            <a href="<?php
                                            echo esc_url(get_category_link($categories[0]->term_id)); ?>"><?php
                                                echo esc_html($categories[0]->name); ?></a>
                                        </span>
                                    <div class="blog__post_information-item information-item blog__post_social-block social-block_js">
                                        <i class="icon-pe-mi-share"></i>
                                        <div class="blog__post_social-icons social-icons">
                                            <a href="http://facebook.com/sharer.php?u=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;t=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-facebook"
                                               target="_blank" title="Facebook"></a>
                                            <a href="http://twitter.com/intent/tweet?url=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;text=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-twitter"
                                               target="_blank" title="Twitter"></a>
                                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php
                                            the_oro_post_shortlink(get_permalink()); ?>&amp;title=<?php
                                            echo urlencode($post->post_title) ?>"
                                               class="blog__single-post_social-icon social-share-linkedin"
                                               target="_blank" title="LinkedIn"></a>
                                            <div class="blog__single-post_social-icon social-share-copylink"
                                                 title="Copy the link to clipboard">
                                                <input type="text" value="<?php
                                                echo get_the_permalink(); ?>" class="copylink">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile; ?>
                    </div>
                </div>
            <?php
            endif;
            wp_reset_postdata(); ?>
        <?php
        endif; ?>
    </div>
</div>

<?php
get_footer(); ?>
