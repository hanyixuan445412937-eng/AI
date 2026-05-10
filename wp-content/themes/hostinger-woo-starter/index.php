<?php
/**
 * Main template.
 *
 * @package Hostinger_Woo_Starter
 */

get_header();
?>
<section class="hero">
	<div class="container hero__grid">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Hostinger-ready WooCommerce website', 'hostinger-woo-starter' ); ?></p>
			<h1><?php bloginfo( 'name' ); ?></h1>
			<p><?php esc_html_e( 'A fast, responsive WordPress starter built for product catalogs, landing pages, and easy Hostinger deployment.', 'hostinger-woo-starter' ); ?></p>
			<div class="hero__actions">
				<a class="button button--primary" href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'Shop now', 'hostinger-woo-starter' ); ?></a>
				<a class="button button--secondary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'hostinger-woo-starter' ); ?></a>
			</div>
		</div>
		<div class="hero__card">
			<strong><?php esc_html_e( 'Deployment checklist', 'hostinger-woo-starter' ); ?></strong>
			<ul>
				<li><?php esc_html_e( 'Upload theme ZIP in WordPress admin.', 'hostinger-woo-starter' ); ?></li>
				<li><?php esc_html_e( 'Activate WooCommerce.', 'hostinger-woo-starter' ); ?></li>
				<li><?php esc_html_e( 'Set PHP 8.1+ in Hostinger hPanel.', 'hostinger-woo-starter' ); ?></li>
			</ul>
		</div>
	</div>
</section>
<div class="container content-grid">
	<section>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'card' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<article class="card"><h2><?php esc_html_e( 'Ready to build', 'hostinger-woo-starter' ); ?></h2><p><?php esc_html_e( 'Create pages or import demo content to replace this starter layout.', 'hostinger-woo-starter' ); ?></p></article>
		<?php endif; ?>
	</section>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<aside class="card shop-summary">
			<h2><?php esc_html_e( 'Store tools', 'hostinger-woo-starter' ); ?></h2>
			<p><?php esc_html_e( 'WooCommerce is active. Add products and configure payment/shipping from the WordPress dashboard.', 'hostinger-woo-starter' ); ?></p>
		</aside>
	<?php endif; ?>
</div>
<?php
get_footer();
