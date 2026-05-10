<?php
/**
 * Single post template.
 *
 * @package Hostinger_Woo_Starter
 */

get_header();
?>
<div class="container narrow page-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'card' ); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
		<?php comments_template(); ?>
	<?php endwhile; ?>
</div>
<?php
get_footer();
