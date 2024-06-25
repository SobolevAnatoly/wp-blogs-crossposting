<?php

use OroBlogs\OroBlogs;

get_header(); ?>
    <div class="blog">
        <?php OroBlogs::loadTemplatePart('top-contact-banner'); ?>

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post();
                $category = get_the_category();
                $current_cat_url = '';
                if ($category) {
                    $current_cat_url = get_category_link($category[0]->term_id);
                } ?>

                <?php OroBlogs::loadTemplatePart('single/hero'); ?>

                <div class="container blog__wrapper">
                    <div class=" blog__single-container">
                        <div class="blog_wide-col  blog__single-container_sidebar"
                             data-current-cat="<?php echo $current_cat_url; ?>">
                            <div class="blog__slider">
                                <?php get_sidebar('single-blog'); ?>
                            </div>
                        </div>
                        <div class="blog__single-container_content">
                            <article class="content entry-content">
                                <div></div>
                                <?php if (get_field('field_839e0e768362')):
                                    do_action('createOROACFContent');
                                else:
                                    the_content();
                                endif;
                                do_action('oro-comments'); ?>
                            </article>
                            <?php OroBlogs::loadTemplatePart('single/social'); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php OroBlogs::loadTemplatePart('single/none'); ?>
        <?php endif; ?>

        <?php OroBlogs::loadTemplatePart('single/related-items'); ?>

        <?php OroBlogs::loadTemplatePart('single/banner'); ?>
    </div>
<?php get_footer();
