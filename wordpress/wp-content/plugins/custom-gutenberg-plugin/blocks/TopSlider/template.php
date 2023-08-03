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
	<section class="top-slider">
		<div class="banner-slider banner-slider_preview swiper">
			<div class="banner-slider__wrapper swiper-wrapper">

				<?php
				if ( have_rows( 'slides' ) ):
					$i = 0;
					while ( have_rows( 'slides' ) ) : the_row();
						$i ++;
						if ( $i > 4 ) {
							$i = 1;
						}
						?>
						<div class="banner-slider__slide swiper-slide slide-<?= $i ?>">
							<a href="<?php the_sub_field( 'link' ) ?>">
								<?= wp_get_attachment_image( get_sub_field( 'image' ), 'full' ) ?>
							</a>
						</div>

					<?php

					endwhile;
				endif; ?>

			</div>

			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
	</section>

<?php endif; ?>
