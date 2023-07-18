<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
			<div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
		</div>
	<?php endforeach; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
	<div class="mt-3">
	<span class="text-span">
		<span
			class="product-quantity"><?= WC()->cart->get_cart_contents_count() ?></span>&nbsp;<?php if (function_exists('pointsLabel')) echo pointsLabel( WC()->cart->get_cart_contents_count() ) ?>&nbsp;<?php esc_html_e( 'на суму', 'yappo' ); ?></span>

		<span class="product-total"><?= WC()->cart->get_cart_subtotal(); ?></span>
	</div>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	<?php
	$chosen_methods_pickup  = WC()->session->get( 'chosen_shipping_methods' );
	$shipping_price = false;
	if ( $chosen_methods_pickup[0] == 'flat_rate:3' ) {
		$shipping_price = true;
	}
	if ( $shipping_price ) : ?>
		<div class="mt-2">
		<span class="text-span">
	          <?php esc_html_e( 'Вартість доставки', 'yappo' ); ?>
          </span>

			<span class="text-span">
				<?php
				 
				 	if(get_locale() == 'uk') esc_html_e( 'Розраховується оператором', 'yappo' );
					if(get_locale() == 'ru_RU') esc_html_e( 'Рассчитывается оператором', 'yappo' );
				 ?>
			</span>
		</div>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="fee mt-2">
			<span class="text-span"><?php echo esc_html( $fee->name ); ?></span>
			<span class="price-delivery"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>
	<hr>
	<div>
		<span><?php esc_html_e( 'До сплати', 'yappo' ); ?></span>
		<span class="order-total"><?php wc_cart_totals_order_total_html(); ?></span>
	</div>
	<hr>
	<?php do_action( 'woocommerce_review_order_before_submit' );
	$order_button_text = __( 'Підтвердити замовлення', 'yappo' );
	?>

	<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt btn-blue orange-btn ' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

	<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

</div>


