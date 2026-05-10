<?php
/**
 * WooCommerce wrapper template.
 *
 * @package Hostinger_Woo_Starter
 */

get_header();
?>
<div class="container shop-layout">
	<?php woocommerce_content(); ?>
</div>
<?php
get_footer();
