<?php

global $post;
?>

<section class="blog__hero">

    <div class="container">
        <a href="<?php the_permalink(); ?>" class="intro-box hero-link">
            <div class="blog__hero-image <?php the_field('select_image_decor'); ?>">
                <?php
                the_post_thumbnail('large'); ?>
            </div>
            <div class="blog__hero-content">
                <div class="content-wrapper">
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
                    <h3>
                        <?php the_title(); ?>
                    </h3>
                    <p class="date">
                        <?php
                        printf(
                        '<time datetime="%3$s">%4$s</time>',
                        esc_url(get_permalink()),
                        esc_attr(get_the_time()),
                        esc_attr(get_the_date('c')),
                        esc_html(get_the_date())
                        );
                        echo ' | ' . get_the_author() ?>
                    </p>
                </div>
            </div>
        </a>
    </div>
</section>
