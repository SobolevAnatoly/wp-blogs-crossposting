<?php use OroBlogs\OroBlogs;

$category = get_the_category();
?>
<section class="block_0044"
         style="background-color: rgb(247, 249, 249); background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>')">
    <div class="container">
        <div class="intro">
            <div class="intro-content">
                <?php if ($category): ?>
                    <p class="description"><?php echo $category[0]->name; ?></p>
                <?php endif; ?>
                <h1 class="h2"><?php the_title(); ?></h1>
                <p class="date"><?php the_date(); ?> | <?php the_author(); ?></p>
            </div>
        </div>
    </div>
</section>