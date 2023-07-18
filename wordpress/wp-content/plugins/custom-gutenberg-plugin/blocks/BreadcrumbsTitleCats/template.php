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
	<div class="container">
		<div class="row align-items-center mb-md-5 mb-4">

			<div class="col-3 pe-0">
                <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
                    if(get_locale() == 'ru_RU') echo str_replace('Головна','Главная',yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>', false));
                    else echo yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>');
                } ?>

            </div>
			<?php
			$rolls_id =  apply_filters( 'wpml_object_id', 72, 'product_tag' );
			$title_class = '';
			if ( get_field( 'category', $post_id )->term_taxonomy_id == $rolls_id || get_field( 'category', $post_id )->parent == $rolls_id ) {
				$title_class = 'title-bg-two';
			}
			?>
			<div class="col-6">
				<h2 class="section__title ">
					<?php the_title(); ?>
				</h2>
			</div>

		</div>

	</div>
<?php endif; ?>
