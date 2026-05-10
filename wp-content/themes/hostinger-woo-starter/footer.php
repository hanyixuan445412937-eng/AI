<?php
/**
 * Footer template.
 *
 * @package Hostinger_Woo_Starter
 */
?>
</main>
<footer class="site-footer">
	<div class="container site-footer__inner">
		<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
			<div class="footer-widgets"><?php dynamic_sidebar( 'footer-widgets' ); ?></div>
		<?php endif; ?>
		<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer navigation', 'hostinger-woo-starter' ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'fallback_cb' => false ) ); ?>
		</nav>
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
