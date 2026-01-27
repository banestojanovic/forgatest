<?php
/**
 * The main template file
 * @package ForgaTest
 * @subpackage ForgaCommerce
 * @since ForgaCommerce 1.0
 */
?>
<?php get_header(); ?>

<main>
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
        endwhile;
    endif;
    ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
