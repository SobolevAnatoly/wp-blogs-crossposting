<div class="contribute">
    <?php
    if (have_rows('field_contribute_key', $blog_page_id)):
        while (have_rows('field_contribute_key', $blog_page_id)) : the_row(); ?>
            <h5><?= get_sub_field('field_contribute_text_key', $blog_page_id) ?></h5>
            <?php
            $contribute_page_id = get_sub_field('field_contribute_link_key', $blog_page_id); ?>
            <a href="<?php
            the_permalink($contribute_page_id); ?>" title="<?php
            the_title_attribute(['post' => $contribute_page_id]); ?>"
               class="learn-more color-theme"><?php
                _e('Learn more', 'oro-themes-features') ?></a>
        <?php
        endwhile;
    endif;
    ?>
</div>