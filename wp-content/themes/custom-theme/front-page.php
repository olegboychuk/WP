<?php /* Template Name: Home-template */ ?>
<?php get_header();
global $post;
$fields = get_fields($post->ID);
?>


<!-- <pre><?php print_r($fields); ?></pre> -->
<?php get_footer(); ?>
