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
	<section class="section-delivery">
		<div class="container-fluid">
			<div class="row  justify-content-between align-items-center">
				<div class="col-xxl-5 col-md-7">
					<h3><?php the_field( 'title' ); ?></h3>

					<a href="<?php the_field( 'btn_link' ) ?>" onclick=""
					   class="btn-blue"><?php the_field( 'btn_text' ) ?></a>
				</div>
				<div class="col-lg-2 col-md-4">
					<div class="img-wrap">
						<?= wp_get_attachment_image( get_field( 'image' ), 'full' ) ?>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
