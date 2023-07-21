<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$seo = load_template_part( 'template-parts/seo/product', 'item-json', [
	'i'         => '1',
	'quantity'  => 1,
	'list_name' => 'Single item',
	'price'     => $product->get_price(),
] );
echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<button data-quantity="%s" data-name="%s" data-index="%s" data-seo=\'%s\' class="btn btn-primary %s" %s><span>%s</span><svg width="25" height="28" viewBox="0 0 25 28" fill="none"
					 xmlns="http://www.w3.org/2000/svg">
					<path
						d="M0 10.8458C0 8.7218 1.7218 7 3.84575 7H19.1542C21.2782 7 23 8.7218 23 10.8458C23 14.2744 22.679 17.6955 22.0413 21.0643L22.0016 21.2741C21.5925 23.4354 19.7039 25 17.5042 25H11.5H5.49576C3.29612 25 1.40752 23.4354 0.998393 21.2741L0.958675 21.0643C0.320958 17.6955 0 14.2744 0 10.8458Z"
						fill="white"/>
					<circle cx="20" cy="23" r="5" fill="#2A1A5E"/>
					<path d="M17 23H23M20 20V26" stroke="white" stroke-width="2"/>
					<path d="M7 7V6.5C7 4.01472 9.01472 2 11.5 2V2C13.9853 2 16 4.01472 16 6.5V7"
						  stroke="white" stroke-width="3"/>
				</svg></button>',
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		$product->get_title(),
		$args['i'],
		$seo,
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	),
	$product,
	$args
);
