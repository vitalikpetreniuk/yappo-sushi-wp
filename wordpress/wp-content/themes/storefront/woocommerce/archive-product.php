<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

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
global $wp_query;
$cate       = get_queried_object();
$cateID     = $cate->term_id;
$parentcats = get_ancestors( $cateID, 'product_cat' );
$min_max    = get_product_category_min_max(); ?>
    <span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
        <meta content="<?= $cate->count ?>" itemprop="offerCount">
        <meta content="<?= $min_max['min'] ?>" itemprop="lowPrice">
        <meta content="<?= $min_max['max'] ?>" itemprop="highPrice">
        <meta content="UAH" itemprop="priceCurrency">
    </span>
<?php
if ( $cateID != 23 ) { ?>
    <header class="woocommerce-products-header">
        <div class="container">
            <div class="section__title">
                <h1><?php woocommerce_page_title(); ?></h1>
            </div>
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>
        </div>
    </header>
    <section>
        <div class="container">
            <!--            --><?php
			//            if ($parentcats[0] == 23) {
			//                $args  = array(
			//                    'taxonomy' => 'product_cat'
			//                );
			//                $terms = wp_get_post_terms($parentcats[0], 'product_cat', $args);
			//                foreach ($terms as $term) {
			//                    echo ' <span class="category_description">'. $term->description .'</span>';
			//                }
			//            }
			//
			?>
            <div class="product__flex">
				<?php
				if ( woocommerce_product_loop() ) {

					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked woocommerce_output_all_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
//                    do_action( 'woocommerce_before_shop_loop' );

//                    woocommerce_product_loop_start();

					if ( wc_get_loop_prop( 'total' ) ) {
						if ( $parentcats[0] == 23 ) {
							$thumbnail_id = get_woocommerce_term_meta( $cateID, 'thumbnail_id', true );
							$image        = wp_get_attachment_url( $thumbnail_id );
							echo '<div class="sale-category-banner"><img src="' . $image . '"></div>';
						}
						$seo = [];
						$i   = 0;
						while ( have_posts() ) {
							the_post();
							global $product;
							$seo[] = load_template_part( 'template-parts/seo/product', 'item', [
								'i'         => $i,
								'quantity'  => 1,
								'list_name' => 'Product list',
								'price'     => $product->get_price()
							] );
							/**
							 * Hook: woocommerce_shop_loop.
							 */
							do_action( 'woocommerce_shop_loop' );

							get_template_part( 'template-parts/product', 'home-item', [ 'i' => $i ] );
							$i ++;
						};
					}

//                    woocommerce_product_loop_end();

					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
//                    do_action( 'woocommerce_after_shop_loop' );
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				}

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
				do_action( 'woocommerce_sidebar' ); ?>
            </div>
        </div>
    </section>
	<?php if ( count( $seo ) ) : ?>
        <script>
            window.dataLayer = window.dataLayer || [];
            // Measure product views / impressions
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "view_item_list",
                ecommerce: {
                    items: [
						<?= implode( ',', $seo ) ?>
                    ]
                }
            });
        </script>
	<?php endif; ?>
	<?php
	if ( $parentcats[0] == 23 ) {
		echo '<div class="btn__wrapper sale-wrapper">
        <a href="' . get_term_link( $parentcats[0], 'product_cat' ) . '" class="btn">УСІ АКЦІЇ</a>
    </div>';
	}
	?>
	<?php
} else {
	get_template_part( "inc/theme/sale-categories", null, [ 'id' => $cateID ] );


}

get_footer( 'shop' );
