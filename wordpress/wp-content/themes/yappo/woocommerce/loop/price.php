<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<div class="product__cart-info-block <?php if ( $product->is_on_sale() ) {
		echo 'product__cart__info-discount';
	} ?>">
		<ul class="product__cart__info">
			<?php if ( ! $product->is_on_sale() ) : ?>
			<li class="price">
				<?php endif; ?>
				<?php echo $price_html; ?>
				<?php if ( ! $product->is_on_sale() ) : ?>
			</li>
		<?php endif; ?>
		</ul>
		<?php
		if (preg_match('/\d+л/', $product->name)) {
			?>
			<span class="weight"><?= $product->get_weight() ?> мл</span>
			<?php
		}else if ( $product->get_weight() ) : ?>
			<span class="weight"><?= wc_format_weight( $product->get_weight() ); ?></span>
		<?php endif; ?>
	</div>
<?php endif; ?>
