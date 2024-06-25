<section class="block_0043 ui-dark <?= trim(get_blog_details()->path, "/") ?>">
    <div class="container">
        <div class="block0043__content">
            <?php
            if (have_rows('field_contact_us_group_key', get_option('page_for_posts'))):
                while (have_rows('field_contact_us_group_key', get_option('page_for_posts'))) : the_row(); ?>
                    <p><?= get_sub_field('field_contact_us_text_key', get_option('page_for_posts')); ?></p>
                    <?php
                    $contact_page_id = get_sub_field('select_page', get_option('page_for_posts')); ?>
                    <a href="<?php the_permalink($contact_page_id); ?>" title="<?php the_title_attribute(['post' => $contact_page_id]); ?>" class="learn-more">
                        <?php echo get_sub_field('link_title', get_option('page_for_posts')); ?></a>
                <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>
</section>