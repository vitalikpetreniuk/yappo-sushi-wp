<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
} ?>
<?php

get_header( 'shop' ); ?>
<?php

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>

<?php
/**
 * Hook: woocommerce_archive_description.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
do_action( 'woocommerce_archive_description' );
?>

	<section class="discount-sale-page">

		<div class="container-fluid">

			<div class="row justify-content-xxl-center justify-content-lg-between justify-content-center">
				<?php
				$args          = array(
					'hierarchical'     => 1,
					'show_option_none' => '',
					'hide_empty'       => 0, // Set to 0 to show empty categories and 1 to hide them
					'parent'           => get_queried_object()->term_id,
					'taxonomy'         => 'product_cat'
				);
				$subcategories = get_categories( $args );
				$i             = 0;
				foreach ( $subcategories as $subcategory ) {
					$i ++;
					$link         = get_term_link( $subcategory->slug, $subcategory->taxonomy );
					$thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );
					$image        = wp_get_attachment_url( $thumbnail_id );
					?>
					<div class="col-xxl-5 col-lg-6  col-11 px-xxl-5 px-lg-4">
						<div class="banner-slider__slide swiper-slide slide-<?= $i ?>">
							<a href="work <?= $link ?>">
								<img src="<?= wp_get_attachment_image_url( $thumbnail_id, 'full' ) ?>"
								     alt="<?= $subcategory->name ?>">
							</a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
//		do_action( 'woocommerce_sidebar' );
?>

	<section class="accordion-section">
		<div class="container-fluid">

			<div class="accordeon">
				<?php
				if ( have_rows( 'questions', get_queried_object() ) ):
					while ( have_rows( 'questions', get_queried_object() ) ) : the_row();
						?>
						<div class="slide-wrap">
							<div class="slide-header">
								<span class="glyphicon glyphicon-chevron-down"></span>
								<h4>
									<?php the_sub_field( 'question' ) ?>
								</h4>

								<span class="span-plus"></span>
							</div>
							<div class="slide-content">
								<?php the_sub_field( 'answer' ); ?>
							</div>
						</div>
					<?php
					endwhile;
				endif; ?>
			</div>
		</div>
	</section>
<?php get_template_part( 'template-parts/content', 'whatpayreceive' ); ?>
<?php

get_footer( 'shop' );
