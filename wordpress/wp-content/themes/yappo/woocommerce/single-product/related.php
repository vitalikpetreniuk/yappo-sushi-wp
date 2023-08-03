<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
?>

    <section class="related products prosuct-like">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
		?>
        <div class="container-fluid">
            <h2 class="section__title title-bg-two">
				<?php
                if(get_locale() == 'uk') _e( 'ВАМ <span>ТАКОЖ </span> МОЖЕ СПОДОБАТИСЬ', 'yappo' );
                if(get_locale() == 'ru_RU') _e( 'ВАМ <span>ТАКЖЕ </span> МОЖЕТ ПОНРАВИТЬСЯ', 'yappo' );
				?>
            </h2>
        </div>

		<?php woocommerce_product_loop_start(); ?>

		<?php
		if ( $upsells ) {
			$related_products = $upsells;
		}
		foreach ( $related_products as $related_product ) : ?>

			<?php
			$post_object = get_post( $related_product->get_id() );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

			wc_get_template_part( 'content', 'product' );
			?>

		<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>
    </section>
<?php
wp_reset_postdata();
