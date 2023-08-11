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
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<div class="widget_shopping_cart_content">
    <?php if (!WC()->cart->is_empty()) : ?>

      <ul class="woocommerce-mini-cart cart_list product_list_widget cart-list <?php echo esc_attr($args['list_class']); ?>">
          <?php
          do_action('woocommerce_before_mini_cart_contents');
          $i = 0;
          foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
              $i++;
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

              if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                  $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                  $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                  $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                  $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

                  $seo = load_template_part('template-parts/seo/product', 'item-json', [
                      'i' => $args['i'] ?? $i,
                      'quantity' => $cart_item['quantity'],
                      'list_name' => 'Mini cart',
                      'id' => $product_id,
                      'price' => $_product->get_price(),
                  ]);
                  ?>
                <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
                  <div class="cart__preview-item">
                      <?php
                      if (function_exists('yappo_product_badges')) {
                          yappo_product_badges($product_id);
                      }
                      ?>

                    <a href="<?php echo esc_url($product_permalink); ?>" class="cart__img">
                        <?php echo $thumbnail // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>

                    <div class="cart__detail">

                      <div class="cart__detail__header">

                        <h5><?= $product_name ?></h5>

                          <?php
                          $napoi = [261, 262, 263, 264, 265, 276, 249, 199, 200, 142];
                          if (in_array($product_id, $napoi)) { ?>
                              <?= $_product->get_weight() ?> мл
                              <?php

                          } else if ($_product->get_weight()) : ?>
                            <p class="weight">
                                <?= wc_format_weight($_product->get_weight()); ?>
                            </p>
                          <?php endif; ?>

                          <?php
                          echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                              'woocommerce_cart_item_remove_link',
                              sprintf(
                                  '<a href="%s" class="remove remove-button remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-seo=\'%s\'>	<svg class="hover-effect-svg" width="12" height="12" viewBox="0 0 22 22" fill="none"
										 xmlns="http://www.w3.org/2000/svg">
										<path d="M2 2L20.5 20.5" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
											  stroke-linecap="round"/>
										<path d="M2 20.5005L20.5 2.00051" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
											  stroke-linecap="round"/>
									</svg></a>',
                                  esc_url(wc_get_cart_remove_url($cart_item_key)),
                                  esc_attr__('Remove this item', 'woocommerce'),
                                  esc_attr($product_id),
                                  esc_attr($cart_item_key),
                                  esc_attr($_product->get_sku()),
                                  $seo
                              ),
                              $cart_item_key
                          );
                          ?>
                      </div>

                      <div class="product__cart">
                          <?php
                          $input_args = array(
                              'input_name' => "cart[{$cart_item_key}][qty]",
                              'input_value' => $cart_item['quantity'],
                              'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                              'min_value' => '1',
                              'readonly' => true,
                              'id' => $product_id,
                              'price' => $_product->get_price(),
                          );

                          $product_quantity = woocommerce_quantity_input($input_args, $_product, false);
                          echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                          ?>

                        <ul class="product__cart__info <?php if ($_product->is_on_sale()) {
                            echo 'product__cart__info-discount';
                        } ?>">
                          <li class="price"><?= $product_price ?></li>
                        </ul>

                      </div>

                    </div>

                    <hr class="line">
                  </div>
                </li>
                  <?php
              }
          }

          do_action('woocommerce_mini_cart_contents');
          ?>
      </ul>

        <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

      <div class="resaul-sum-wrap">
        <div class="sum-wrap">
          <span style="font-weight: 700;"><?php esc_html_e('Разом', 'yappo'); ?></span>

          <span><?= WC()->cart->get_cart_total(); ?></span>
        </div>
      </div>

      <a href="<?= wc_get_checkout_url() ?>" class="btn-blue orange-btn">
          <?php esc_html_e('ОФОРМИТИ ДОСТАВКУ', 'yappo'); ?>
      </a>

        <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

    <?php else : ?>

      <div class="cart-empty">
        <div>
          <h5>
              <?php esc_html_e('Ваш кошик порожній', 'yappo'); ?>
          </h5>

          <p>
              <?php _e('Хутчіш обирай свій сет і клич друзів <br>на суші!', 'yappo'); ?>

          </p>
        </div>

        <img src="<?= get_theme_file_uri('assets/img/cart-img.png') ?>" alt="fish" loading="lazy" width="100%" height="auto">
      </div>

    <?php endif; ?>

    <?php do_action('woocommerce_after_mini_cart'); ?>
</div>
