<?php if (have_rows('sp_post_bottom_banner', get_option('page_for_posts'))): ?>
    <?php while (have_rows('sp_post_bottom_banner', get_option('page_for_posts'))): the_row(); ?>
        <div class="blog__single-banner  ptb-small mtb-0 ">
            <div class="container">
                <div class="banner custom-grid">
                    <div class="banner__item">
                        <h2 class="banner__title"><?php the_sub_field('text'); ?></h2>
                        <?php $link = get_sub_field('select_page'); ?>
                        <?php if ($link): ?>
                            <a href="<?php echo $link['url']; ?>" class="banner__btn btn-theme btn-black w300">
                                <?php echo $link['title'] ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="banner__photo">
                        <?php echo wp_get_attachment_image(get_sub_field('image'), 'full'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
