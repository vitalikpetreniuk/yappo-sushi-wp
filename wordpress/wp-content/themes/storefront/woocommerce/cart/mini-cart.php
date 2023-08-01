<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>
<div class="mini-cart <?php if ( WC()->cart->is_empty() ) {
	echo 'empty';
} ?>">
	<?php if ( ! WC()->cart->is_empty() ) : ?>
    <h3>Ваше замовлення:</h3>
    <div class="shop_table cart">
        <form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

            <ul class="woocommerce-mini-cart cart_list product_list_widget cart__preview-flex <?php echo esc_attr( $args['list_class'] ); ?>">
				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );
				$i = 0;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$image             = wp_get_attachment_image_src( get_post_thumbnail_id( $cart_item['product_id'] ), 'single-post-thumbnail' );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$weight            = $_product->get_attribute( 'weight' );
						?>
                        <div class="cart__preview-item mini-cart">
                            <div class="cart__img">
                                <img src="<?php echo $image[0]; ?>" alt=""/>
                            </div>
                            <div class="cart__detail">
                                <div class="cart__detail__header">
                                    <h2><?php echo $product_name; ?></h2>
									<?php
									$seo = load_template_part( 'template-parts/seo/product', 'item-json', [
										'i'         => $args['i'],
										'quantity'  => $cart_item['quantity'],
										'list_name' => 'Mini cart',
										'id'        => $product_id,
										'price'     => $_product->get_price(),
									] );
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a class="remove-button remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"
data-seo=\'%s\'>',
										__( 'Remove this item', 'oceanwp' ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() ),
										$seo
									),
										$cart_item_key
									);
									?>
                                    <!--                    <button>-->
                                    <svg
                                            width="17"
                                            height="17"
                                            viewBox="0 0 17 17"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <rect
                                                width="2.16412"
                                                height="21.6412"
                                                transform="matrix(0.700123 0.714022 -0.700123 0.714022 15.1514 0)"
                                                fill="#1226AA"
                                        />
                                        <rect
                                                width="2.16412"
                                                height="21.6412"
                                                transform="matrix(0.700123 -0.714022 0.700123 0.714022 0 1.54785)"
                                                fill="#1226AA"
                                        />
                                    </svg>
                                    </a>
                                </div>
								<?php
								$price         = $_product->price;
								$regular_price = $_product->regular_price;
								$isSale        = ( $price != $regular_price );
								$weight_unit   = $_product->get_meta( 'measure_unit' ) ?: 'г';
								?>
                                <div class="product__cart <?php if ( $isSale ) {
									echo 'sale';
								} ?>">
                                    <div class="quantity-product">
                                        <?php
                                        $input_args = array(
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                            'min_value'   => '1',
                                            'readonly'    => true
                                        );

                                        $product_quantity = woocommerce_quantity_input( $input_args, $_product, false );
                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                        ?>
                                    </div>
                                    <ul class="product__cart__info">
                                        <li><?php echo $weight; ?><?= $weight_unit ?></li>
										<?php if ( $isSale ) {
											echo '<li class="regular-price">' . floatval( $regular_price ) . 'грн</li>';
										} ?>
                                        <li class="price"><?php echo floatval( $price ); ?>грн</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_wp_http_referer" value="<?php echo wc_get_cart_url(); ?>">
                        <!--                <li class="woocommerce-mini-cart-item --><?php //echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?><!--">-->
                        <!--                    <div class="owp-grid-wrap">-->
                        <!--                        <div class="owp-grid thumbnail">-->
                        <!--                            --><?php //if ( ! $_product->is_visible() ) : ?>
                        <!--                                --><?php //echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                        <!--                            --><?php //else : ?>
                        <!--                                <a href="--><?php //echo esc_url( $product_permalink ); ?><!--">-->
                        <!--                                    --><?php //echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                        <!--                                </a>-->
                        <!--                            --><?php //endif; ?>
                        <!--                        </div>-->
                        <!---->
                        <!--                        <div class="owp-grid content">-->
                        <!--                            <div>-->
                        <!--                                --><?php //if ( empty( $product_permalink ) ) : ?>
                        <!--                                    <h3>-->
                        <!--                                        --><?php //echo $product_name; ?>
                        <!--                                    </h3>-->
                        <!--                                --><?php //else : ?>
                        <!--                                    <h3>-->
                        <!--                                        <a href="--><?php //echo esc_url( $product_permalink ); ?><!--">-->
                        <!--                                            --><?php //echo $product_name; ?>
                        <!--                                        </a>-->
                        <!--                                    </h3>-->
                        <!--                                --><?php //endif; ?>
                        <!---->
                        <!--                                --><?php //echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        <!---->
                        <!--                                --><?php //echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                        <!--                                --><?php
//                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
//                                    '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
//                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
//                                    __( 'Remove this item', 'oceanwp' ),
//                                    esc_attr( $product_id ),
//                                    esc_attr( $cart_item_key ),
//                                    esc_attr( $_product->get_sku() )
//                                ),
//                                    $cart_item_key
//                                );
//                                ?>
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </li>-->
						<?php
					}
					$i ++;
				}

				do_action( 'woocommerce_mini_cart_contents' );
				?>
            </ul>
        </form>

        <p class="woocommerce-mini-cart__total total">
			<?php
			/**
			 * Woocommerce_widget_shopping_cart_total hook.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action( 'woocommerce_widget_shopping_cart_total2' );
			?>
        </p>
        <a href="<?php echo wc_get_cart_url(); ?>" class="btn btn-primary">ОФОРМИТИ ЗАМОВЛЕННЯ</a>

        <!--    --><?php //do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

        <!--    <p class="woocommerce-mini-cart__buttons buttons">-->
		<?php //do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?><!--</p>-->

        <!--    --><?php //do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

		<?php else : ?>

            <div class="empty-cart">
        <span class="empty-cart__image">
            <img src="<?php echo get_template_directory_uri() ?>/assets/images/main/yoy.svg" alt="Ой">
        </span>
                <div class="empty-cart-description">
                    <h3 class="empty-cart-description__title">Ваш кошик порожній!</h3>
                    <p class="empty-cart-description__text">Виберіть позиції з меню на сайті і натисніть "YА БЕРУ", щоб
                        оформити замовлення</p>
                </div>
            </div>

		<?php endif; ?>
    </div>
    <span class="spinner">
        <img src="<?php echo get_template_directory_uri() ?>/assets/images/main/Spinner.svg" alt="Загрузка"></span>
</div>
<?php //do_action( 'woocommerce_after_mini_cart' ); ?>
