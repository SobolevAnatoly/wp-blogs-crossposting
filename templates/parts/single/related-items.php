<?php $related_posts = \OroBlogs\OroBlogs::get_related_posts(); ?>
<div class="usual-block usual-dark-gray blog__related-js">
    <?php if ($related_posts && $related_posts->have_posts()): ?>
        <div class="container">
            <div class="blog__related-inner">
                <div class="blog__related-heading">
                    <div class="h2 blog__related-posts_title">
                        <?php _e('Related Posts', 'commerce'); ?>
                    </div>
                    <?php dynamic_sidebar('single-blog-bottom-sidebar'); ?>
                </div>
                <div class="blog__related-posts">
                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                        <?php \OroBlogs\OroBlogs::loadTemplatePart('single/related-item'); ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>