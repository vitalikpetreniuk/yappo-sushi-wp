<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<section class="cart-page">
	<div class="container-fluid">
		<div class="row align-items-center mb-md-5 mb-4 justify-content-center">

			<div class="col-lg-7 col-md-8 d-flex align-items-center section__title-wrap">
				<h1 class="section__title">
					<?php esc_html_e( 'ОФОРМЛЕННЯ ЗАМОВЛЕННЯ', 'yappo' ); ?>
				</h1>
			</div>
			<div class="col-4 d-md-block d-none"></div>
		</div>

		<?php
		do_action( 'woocommerce_before_checkout_form', $checkout );

		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );

			return;
		}

		$i = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$seo[]    = load_template_part( 'template-parts/seo/product', 'item', [
				'i'         => $i,
				'quantity'  => $cart_item['quantity'],
				'list_name' => 'Checkout',
				'id'        => $_product->get_id(),
				'title'     => $_product->get_name(),
				'price'     => $_product->get_price()
			] );
		}
		?>

		<script>
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "begin_checkout",
                ecommerce: {
                    items: [<?= implode( ', ', $seo ) ?>]
                }
            });
		</script>

		<form name="checkout" method="post" class="checkout woocommerce-checkout"
		      action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<div class="row justify-content-center">
				<div class="col-xxl-7 col-xl-7 col-lg-11 col-md-11 col-11">
					<?php if ( $checkout->get_checkout_fields() ) : ?>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="col2-set" id="customer_details">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>

							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="title-step">
                                        <span>
                                            2
                                        </span>
									<h3><?php esc_html_e( 'Ваше замовлення', 'yappo' ); ?></h3>
								</div>
							</div>

							<div class="col-12 p-0">
								<div class="product-list-cart">
									<ul>
										<li class="first-li">
											<div class="cart__preview-item">

												<div class="cart__img">

												</div>

												<div class="cart__detail">

													<div class="name-wrap">
														<h5 class="product-name"></h5>

														<p class="weight">

														</p>
													</div>

													<ul class="product__cart__info product__cart__info-discount">
                                                            <span class="text-span text-span-price ms-2">
                                                                <?php esc_html_e( 'Ціна', 'yappo' ); ?>
                                                            </span>
													</ul>

													<div class="quantity product-quantity">

														<span
															class="text-span ms-md-3 ms-0"><?php esc_html_e( 'К-ть', 'yappo' ); ?></span>

													</div>

													<div class="product-subtotal"
													     data-title="<?php esc_html_e( 'Всього', 'yappo' ); ?>">
														<span
															class="text-span m-0"><?php esc_html_e( 'Всього', 'yappo' ); ?></span>
													</div>

												</div>

											</div>
										</li>

										<?php
										$i = 0;
										foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
											$i ++;
											/* @var WC_Product $_product */
											$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
											$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

											if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
												$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
												$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
												$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
												?>

												<li>
													<div class="cart__preview-item">

														<?php yappo_product_badges( $product_id ); ?>

														<div class="cart__img">
															<?= $_product->get_image( 'full' ) ?>
														</div>

														<div class="cart__detail">

															<div class="name-wrap">
																<h5 class="product-name"><?= $product_name ?></h5>

																<?php
																$napoi = [
																	261,
																	262,
																	263,
																	264,
																	265,
																	276,
																	249,
																	199,
																	200,
																	142
																];
																if ( in_array( $product_id, $napoi ) ) { ?>
																	<p class="weight">
																		<?= $_product->get_weight() ?> мл
																	</p>
																	<?php

																} else if ( $_product->get_weight() ) : ?>
																	<p class="weight">
																		<?= wc_format_weight( $_product->get_weight() ); ?>
																	</p>
																<?php endif; ?>
															</div>

															<ul class="product__cart__info <?php if ( $_product->is_on_sale() ) {
																echo 'product__cart__info-discount';
															} ?>">
																<?= $_product->get_price_html(); ?>
															</ul>

															<div class="quantity product-quantity">

																<input type="number"
																       class="input-text qty text quantity-input"
																       name="quantity"
																       value="<?= $cart_item['quantity'] ?>"
																       title="К-ть" size="4"
																       min="1"
																       max="" step="1" placeholder=""
																       inputmode="numeric"
																       autocomplete="off" readonly="">

															</div>

															<div class="product-subtotal" data-title="Вартість">
																<?= wc_price( $_product->get_price() * $cart_item['quantity'] ) ?>
															</div>


															<?php
															$seo = load_template_part( 'template-parts/seo/product', 'item-json', [
																'i'         => $i,
																'quantity'  => $cart_item['quantity'],
																'list_name' => 'Mini cart',
																'id'        => $product_id,
																'price'     => $_product->get_price(),
															] );
															echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																'woocommerce_cart_item_remove_link',
																sprintf(
																	'<a href="%s" class="remove remove-button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-seo=\'%s\'>	<svg class="hover-effect-svg" width="12" height="12" viewBox="0 0 22 22" fill="none"
										 xmlns="http://www.w3.org/2000/svg">
										<path d="M2 2L20.5 20.5" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
											  stroke-linecap="round"/>
										<path d="M2 20.5005L20.5 2.00051" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
											  stroke-linecap="round"/>
									</svg></a>',
																	esc_url( wp_nonce_url( add_query_arg( 'remove_item', $cart_item_key, wc_get_checkout_url() ), 'woocommerce-cart' ) ),
																	esc_attr__( 'Remove this item', 'woocommerce' ),
																	esc_attr( $product_id ),
																	esc_attr( $cart_item_key ),
																	esc_attr( $_product->get_sku() ),
																	$seo
																),
																$cart_item_key
															);
															?>
														</div>

														<hr class="line">
													</div>
												</li>
											<?php }
										} ?>
									</ul>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="title-step">
									<span>3</span>
									<h3><?php esc_html_e( 'Оплата', 'yappo' ); ?></h3>
								</div>
							</div>
							<?php
							woocommerce_checkout_payment(); ?>
						</div>
						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

					<?php endif; ?>
				</div>
				<div class="col-xxl-4 col-xl-4 col-lg-6 col-md-8  col-12 mt-5 mt-xl-0">
					<div class="resault-block" style="height: auto;">
						<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

						<h4 id="order_review_heading"><?php esc_html_e( 'Разом', 'yappo' ); ?></h4>

						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

						<?php do_action( 'woocommerce_checkout_order_review' ); ?>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

						<div class="local-wrap">
								<a class="local" target="_blank">

									<img width="10" src="<?= get_theme_file_uri( 'assets/img/gray-location.svg' ) ?>" alt="location">

									<?php if ( function_exists( 'yappo_get_chosen_header_adress' ) ) : ?>
										<?= yappo_get_chosen_header_adress(); ?>
									<?php endif; ?>
								</a>
						</div>
					</div>
				</div>
			</div>
		</form>

		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</section>
