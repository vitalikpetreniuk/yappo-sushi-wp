<div class="container-fluid">
	<div class="row align-items-center justify-content-between">
		<div class="col-lg-2  col-md-12 p-0 order-lg-1 order-2">
			<div class="social-wrap d-flex justify-content-between">
				<?php if ( get_field( 'instagram_link', 'option' ) ) : ?>
					<a href="<?php the_field( 'instagram_link', 'option' ) ?>" rel="nofollow" target="_blank">
						<svg class="hover-effect-svg" width="23" height="24" viewBox="0 0 23 24"
						     fill="none"
						     xmlns="http://www.w3.org/2000/svg">
							<rect width="23" height="24" rx="5" fill="white"/>
							<circle cx="11.5" cy="12.5" r="4.5" stroke="#2A1A5E" stroke-width="4"/>
							<circle cx="17" cy="5" r="2" fill="#2A1A5E"/>
						</svg>
					</a>
				<?php endif; ?>

				<?php if ( get_field( 'facebook_link', 'option' ) ) : ?>
					<a href="<?php the_field( 'facebook_link', 'option' ) ?>" rel="nofollow" target="_blank">
						<svg class="hover-effect-svg" style="margin-top: 2px;" width="23" height="27"
						     viewBox="0 0 23 27" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect width="23" height="24" rx="5" fill="white"/>
							<path
								d="M12 24.9995V13.4994M16.5 6C11.5 6.00006 12 11.4995 12 11.4995V13.4994M12 13.4994H8.5M12 13.4994H15.5"
								stroke="#2A1A5E" stroke-width="4" stroke-linecap="round"
								stroke-linejoin="round"/>
						</svg>
					</a>
				<?php endif; ?>

				<?php if ( get_field( 'viber_link', 'option' ) ) : ?>
					<a href="<?php the_field( 'viber_link', 'option' ) ?>" rel="nofollow" target="_blank">
						<svg class="hover-effect-svg" width="38" height="33" viewBox="0 0 38 33"
						     fill="none"
						     xmlns="http://www.w3.org/2000/svg">
							<path
								d="M8.54512 23.4781L8.54508 23.4781C5.5858 23.2518 3.25635 20.8939 3.12975 17.9591C3.05615 16.2527 3 14.4602 3 12.9683C3 11.8748 3.03017 10.6186 3.0754 9.35156C3.21108 5.55025 6.13397 2.46574 9.90382 2.19547C11.5049 2.08069 13.1229 2 14.5 2C15.8771 2 17.4951 2.08069 19.0962 2.19547C22.866 2.46574 25.7889 5.55025 25.9246 9.35156C25.9698 10.6186 26 11.8748 26 12.9683C26 13.9539 25.9755 15.0722 25.9375 16.2119C25.8063 20.1455 22.6719 23.3086 18.7024 23.6122L15.9031 23.8263C14.7508 23.9144 13.6458 24.3217 12.712 25.0026L10.0954 26.9105C9.67563 27.2166 9.0961 26.8585 9.18199 26.3461L9.46562 24.654L8.47938 24.4887L9.46562 24.654C9.56389 24.0677 9.13785 23.5235 8.54512 23.4781Z"
								fill="white" stroke="white" stroke-width="2"/>
							<path
								d="M9.46243 15.3745C7.46808 13.3802 7.20789 10.2363 8.84723 7.94122C9.36531 7.21591 10.4107 7.1294 11.0409 7.75966L12.2402 8.95896C12.9954 9.71417 12.9113 10.962 12.0616 11.6091C11.2119 12.2561 11.1278 13.504 11.883 14.2592L13.2421 15.6183C13.9973 16.3735 15.2451 16.2894 15.8922 15.4396C16.5392 14.5899 17.7871 14.5058 18.5423 15.261L19.7416 16.4603C20.3718 17.0906 20.2853 18.1359 19.56 18.654C17.2649 20.2933 14.121 20.0331 12.1267 18.0388L9.46243 15.3745Z"
								fill="#2A1A5E"/>
							<path
								d="M15 10.6923V10.6923C16.2745 10.6923 17.3077 11.7255 17.3077 13V13M15 7V7C18.3137 7 21 9.68629 21 13V13"
								stroke="#2A1A5E" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round"/>
							<rect x="15" y="21" width="23" height="12" rx="6" fill="white"/>
							<path
								d="M20 26.5V29C20 29.5523 20.4477 30 21 30H22.25C23.2165 30 24 29.2165 24 28.25V28.25C24 27.2835 23.2165 26.5 22.25 26.5H20ZM20 26.5V25.5"
								stroke="#2A1A5E"/>
							<path
								d="M20 26.5V25.5C20 24.6716 20.6716 24 21.5 24H21.75C22.4404 24 23 24.5596 23 25.25C23 25.9404 22.4404 26.5 21.75 26.5H20Z"
								stroke="#2A1A5E"/>
							<path
								d="M29.5 25.5H31M32.5 25.5H31M31 25.5V28.5C31 29.3284 31.6716 30 32.5 30V30M31 25.5V24M28.5 28.5V27.5C28.5 26.6716 27.8284 26 27 26V26C26.1716 26 25.5 26.6716 25.5 27.5V28.5C25.5 29.3284 26.1716 30 27 30V30C27.8284 30 28.5 29.3284 28.5 28.5Z"
								stroke="#2A1A5E" stroke-linecap="round"/>
							<circle cx="25" cy="3" r="3" fill="#CDE211"/>
						</svg>
					</a>
				<?php endif; ?>

				<?php if ( get_field( 'telegram_link', 'option' ) ) : ?>
					<a href="<?php the_field( 'telegram_link', 'option' ) ?>" rel="nofollow" target="_blank">
						<svg class="hover-effect-svg" width="41" height="34"
						     viewBox="0 0 41 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="17" cy="12" r="12" fill="white"/>
							<path
								d="M20.6343 6.85427L9.82882 10.7563C8.41867 11.2655 8.54556 13.3002 10.008 13.6303L11.3874 13.9416C11.9075 14.059 12.4529 13.9638 12.9025 13.6773L14.8875 12.4124C15.3694 12.1053 15.8906 12.7428 15.4938 13.154C14.6123 14.0675 14.8382 15.5687 15.9495 16.1824L19.163 17.957C20.0781 18.4623 21.2166 17.9001 21.3715 16.8662L22.6272 8.48742C22.7956 7.36405 21.7027 6.46847 20.6343 6.85427Z"
								fill="#2A1A5E"/>
							<circle cx="28" cy="4" r="3" fill="#CDE211"/>
							<rect x="17" y="20" width="23" height="12" rx="6" fill="white"/>
							<path
								d="M22 25.5V28C22 28.5523 22.4477 29 23 29H24.25C25.2165 29 26 28.2165 26 27.25V27.25C26 26.2835 25.2165 25.5 24.25 25.5H22ZM22 25.5V24.5"
								stroke="#2A1A5E"/>
							<path
								d="M22 25.5V24.5C22 23.6716 22.6716 23 23.5 23H23.75C24.4404 23 25 23.5596 25 24.25C25 24.9404 24.4404 25.5 23.75 25.5H22Z"
								stroke="#2A1A5E"/>
							<path
								d="M31.5 24.5H33M34.5 24.5H33M33 24.5V27.5C33 28.3284 33.6716 29 34.5 29V29M33 24.5V23M30.5 27.5V26.5C30.5 25.6716 29.8284 25 29 25V25C28.1716 25 27.5 25.6716 27.5 26.5V27.5C27.5 28.3284 28.1716 29 29 29V29C29.8284 29 30.5 28.3284 30.5 27.5Z"
								stroke="#2A1A5E" stroke-linecap="round"/>
						</svg>
					</a>
				<?php endif; ?>

			</div>
		</div>


		<div class="col-lg-2  col-12 p-0  order-lg-3 order-1">
			<div class="logo">
				<a href="<?= home_url(); ?>">
					<?= wp_get_attachment_image( get_field( 'footer_logo', 'option' ), 'full' ) ?>
				</a>
			</div>
			<p class="mt-4">

				© <?= date( 'Y' ) ?> <?php the_field( 'copyright', 'option' ) ?>

			</p>
		</div>

		<div class="col-lg-2 col-12 p-0  order-lg-5 d-none d-lg-block">
			<a href="https://ninesquares.studio/ua" style='    display: block;width: 7rem;margin-right: 0;margin-right: 0;margin-left: auto;margin-bottom: 0.2rem;' rel="nofollow" target="_blank">
				<img width="103px" height="24px" src="<?= get_theme_file_uri( 'assets/img/Ninesquares.svg' ) ?>"
				     alt="Ninesquares">
			</a>
		</div>
	</div>

	<div class="row mt-lg-0 mt-4 order-lg-6">

		<div class="col-lg-2 col-4 mx-auto my-3 d-block d-lg-none">
			<a class="copmpany" href="https://ninesquares.studio/ua" rel="nofollow" target="_blank">
				<img width="103px" height="24px" src="<?= get_theme_file_uri( 'assets/img/Ninesquares.svg' ) ?>"
				     alt="Ninesquares">
			</a>
		</div>
	</div>
</div>
