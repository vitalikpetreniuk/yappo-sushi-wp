<?php
/**
 * Template Name: Test
 */

get_header();
?>

	<main>


		<?php
		$args  = array(
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug', // Or 'name' or 'term_id'
					'terms'    => array( 'additional' ),
					'operator' => 'NOT IN', // Excluded
				),
				array(
					'taxonomy' => 'product_visibility',
					'terms'    => array( 'exclude-from-catalog' ),
					'field'    => 'name',
					'operator' => 'NOT IN',
				)
			),
			'orderby'        => array(
				'date'       => 'DESC',
				'menu_order' => 'ASC',
			),
			'paged'          => 1,
			'posts_per_page' => 6,
			'post_type'      => 'product',
		);
		$query = new WP_Query( $args ); ?>
		<div class="row" id="row" data-posts='<?= json_encode( $query->query_vars ) ?>'
		     data-ajaxurl="<?= site_url() . '/wp-admin/admin-ajax.php' ?>"
		     data-current_page="<?= $query->get_query_var( 'paged' ) ? $query->get_query_var( 'paged' ) : 1 ?>"
		     data-max_page="<?= $query->max_num_pages ?>"
		>
			<?php

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					wc_get_template_part( 'content', 'product' );
				}
			} ?>

		</div>
		<?php
		// don't display the button if there are not enough posts
		if ( $query->max_num_pages > 1 ) {
			echo '<div class="misha_loadmore">More posts</div>';
		} // you can use <a> as well
		?>
		<script>
            var misha_loadmore_params = {
                'ajaxurl': '<?= site_url() . '/wp-admin/admin-ajax.php' ?>',
                'posts': '<?= json_encode( $query->query_vars ) ?>',
                'current_page': <?= $query->get_query_var( 'paged' ) ? $query->get_query_var( 'paged' ) : 1 ?>,
                'max_page': <?= $query->max_num_pages ?>
            }
		</script>

	</main><!-- #main -->

<?php
get_footer();
