<?php
/**
 * The main template file
 * @package ForgaTest
 * @subpackage ForgaCommerce
 * @since ForgaCommerce 1.0
 */
?>
<?php get_header(); ?>

<main class="container mx-auto flex justify-between py-6 max-xl:px-4">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>
            <?php the_title(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
