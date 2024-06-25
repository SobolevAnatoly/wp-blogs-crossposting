<?php

global $post;
?>
<div id="post-<?= $post->ID; ?>" <?php post_class('blog__article-item'); ?>>
    <a href="<?php the_permalink($post); ?>" class="blog__article-link"
       title="<?= esc_attr(sprintf(__('Permalink to %s', 'orocrm'), the_title_attribute('echo=0'))); ?>"
       rel="bookmark">
        <div class="blog__article-image <?php the_field('select_image_decor'); ?>">
            <?php the_post_thumbnail(); ?>
        </div>
        <div class="blog__article-content">
            <p class="description">
                <?php
                if (is_category()) {
                    single_cat_title('', true);
                } else {
                    $categories = get_the_category();
                    if($categories){
                        echo esc_html($categories[0]->name);
                    }
                } ?>
            </p>
            <h5>
                <?php the_title(); ?>
            </h5>
            <p class="date">
                <?php
                printf(
                    '<time datetime="%3$s">%4$s</time>',
                    esc_url(get_permalink()),
                    esc_attr(get_the_time()),
                    esc_attr(get_the_date('c')),
                    esc_html(get_the_date())
                ); ?>
            </p>
            <p class="text">
                <?= get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true); ?>
            </p>
        </div>
    </a>
</div>
