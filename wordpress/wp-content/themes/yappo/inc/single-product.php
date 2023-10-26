<?php
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs' );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 2;

	return $args;
}

function yappo_product_params( $product ) {
	$flag = false;
	$terms = ( wp_get_post_terms( $product->get_id(), 'product_cat' ) );
	foreach ( $terms as $term ) {
		if ( in_array( $term->term_id, [
			apply_filters( 'wpml_object_id', 102, 'product_cat' ),
			apply_filters( 'wpml_object_id', 101, 'product_cat' )
		] ) ) // Напої
		{
			$flag = true;
		}
	}
	if ( $product->get_weight() ) {
		if ( $flag ) {
			echo str_replace( 'г', 'мл', wc_format_weight( $product->get_weight() ) );
		} else {
			echo wc_format_weight( $product->get_weight() );
		}
	}
}
