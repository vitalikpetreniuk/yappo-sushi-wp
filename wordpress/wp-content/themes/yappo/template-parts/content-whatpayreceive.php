<section class="section-pay">
	<h2 class="section__title">
		<?php the_field( 'whatpayreceive_title', 'option' ); ?>
	</h2>

	<div class="row">
		<div class="col-md-6 p-0">
			<div class="img-wrap">
				<h2 class="section__title">
					<?php the_field( 'whatpayreceive_title1', 'option' ) ?>
				</h2>

				<?= wp_get_attachment_image( get_field( 'whatpayreceive_image1', 'option' ), 'full' ); ?>
			</div>
		</div>
		<div class="col-md-6 p-0">
			<div class="img-wrap img-wrap-card">
				<h2 class="section__title">
					<?php the_field( 'whatpayreceive_title2', 'option' ) ?>
				</h2>

				<?= wp_get_attachment_image( get_field( 'whatpayreceive_image2', 'option' ), 'full' ); ?>
			</div>
		</div>
	</div>
</section>
