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
	<section class="accordion-section">
		<div class="container-fluid">

			<div class="accordeon">
				<?php
				if ( have_rows( 'questions' ) ):
					while ( have_rows( 'questions' ) ) : the_row();
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

<?php endif; ?>
