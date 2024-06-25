<div class="blog__related-posts_item">
    <?php if (get_the_post_thumbnail()): ?>
        <div class="thumbnail">
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail(); ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="info">
        <span class="blog__post_information-item information-item category">
            <?php $categories = get_the_category(); ?>
            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>">
                <?php echo esc_html($categories[0]->name); ?>
            </a>
        </span>
    </div>
    <div class="blog__post_title h5">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
        </a>
    </div>
    <div class="info">
        <span class="blog__post_information-item information-item create-date">
            <?php printf(
                '<time datetime="%3$s">%4$s</time>',
                esc_url(get_permalink()),
                esc_attr(get_the_time()),
                esc_attr(get_the_date('c')),
                esc_html(get_the_date())
            ); ?>
        </span>
    </div>
</div>