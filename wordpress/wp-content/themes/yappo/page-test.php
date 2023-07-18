<?php
/* Template Name: Test */

get_header();
$terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => false
	)
);
//foreach ( $terms as $term ) {
//	$id = wp_insert_post(
//		array(
//			'post_title' => $term->name,
//			'post_name' => $term->slug,
//			'post_content' => ''
//		)
//	);
//}

var_dump( $terms );
get_footer();
?>
