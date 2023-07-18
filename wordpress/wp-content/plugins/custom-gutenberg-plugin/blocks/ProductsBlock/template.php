<?php
/**
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if ( @$block['data']['preview_image_help'] ) : ?>
	<img src="<?= plugin_dir_url( __FILE__ ) ?>/screenshot.png" alt="">
<?php else:
	// Your block html goes here
	?>
	<section class="product-section">
		<div class="container-fluid">

			<?php if ( get_field( 'title' ) ) : ?>
				<h2 class="section__title">
					<?php the_field( 'title' ) ?>
				</h2>
			<?php endif; ?>


			<div class="row">
				<?php
				if ( get_field( 'product_group' ) == 'new' ) {

					$funcname = 'yappo_query_for_new';

					if ( function_exists( $funcname ) ) {
						$args = yappo_query_for_new();
					}

					$list_name = 'New';

				} elseif ( get_field( 'product_group' ) == 'popular' ) {
					$funcname = 'yappo_query_for_popular';

					if ( function_exists( $funcname ) ) {
						$args = yappo_query_for_popular();
					}

					$list_name = 'Popular';
				} elseif ( get_field( 'product_group' ) == 'category' ) {
					$funcname = 'yappo_query_for_category';
					if ( function_exists( $funcname ) ) {
						$args = yappo_query_for_category( get_field( 'category' ) );
					}

					$list_name = get_term_by( 'id', get_field( 'category' ), 'product_cat' )->name;
				}

				if ( ! $args ) {
					$args = [
						'post_type'      => 'product',
						'posts_per_page' => 6,
                        'post_status' => 'publish'
					];
				}

				$loop = new WP_Query( $args );
				?>
				<?php if ( isset( $loop ) && $loop->have_posts() ) {
					$i = 0;
					while ( $loop->have_posts() ) {
						$loop->the_post();
						$i ++;
						$product = wc_get_product( get_the_ID() );
						$seo[]   = load_template_part( 'template-parts/seo/product', 'item', [
							'i'         => $i,
							'quantity'  => 1,
							'id'        => get_the_ID(),
							'price'     => $product->get_price(),
							'list_name' => $list_name
						] );
						?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
						<?php
					}
				}
				//				var_dump($loop->request);
				?>
				<script>
                    // Measure product views / impressions
                    window.dataLayer = window.dataLayer || [];
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
			</div>

			<?php if ( $loop->found_posts > $args['posts_per_page'] ) : ?>
				<div class="btn__wrapper">
					<button data-paged="1" data-max="<?= $loop->max_num_pages ?>" data-func="<?= $funcname ?>"
						<?php if ( get_field( 'category' ) ) {
							echo 'data-category="' . get_field( 'category' ) . '"';
						} ?>
						    class="btn-blue product-section-ajax-btn"><?php esc_html_e( 'ПОКАЗАТИ БІЛЬШЕ', 'yappo' ); ?></button>
				</div>
			<?php endif; ?>

		</div>
	</section>

	<?php
	wp_reset_query();
endif; ?>
