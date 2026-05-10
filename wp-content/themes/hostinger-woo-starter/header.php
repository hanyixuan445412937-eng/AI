<?php
/**
 * Header template.
 *
 * @package Hostinger_Woo_Starter
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'hostinger-woo-starter' ); ?></a>
<header class="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>
		<button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu"><?php esc_html_e( 'Menu', 'hostinger-woo-starter' ); ?></button>
		<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'hostinger-woo-starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
<main id="content" class="site-main">
