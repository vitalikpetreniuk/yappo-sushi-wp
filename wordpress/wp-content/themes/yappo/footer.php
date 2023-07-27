<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package test
 */

?>
</main>
<footer class="footer"><?= get_template_part('template-parts/content', 'footer') ?></footer>

<div class="cart-modal">

  <div class="close-cart">
    <svg class="hover-effect-svg" width="18" height="18" viewBox="0 0 22 22" fill="none"
         xmlns="http://www.w3.org/2000/svg">
      <path d="M2 2L20.5 20.5" stroke="rgba(0,0,0, 0.25)" stroke-width="3" stroke-linecap="round"/>
      <path d="M2 20.5005L20.5 2.00051" stroke="rgba(0,0,0, 0.25)" stroke-width="3" stroke-linecap="round"/>
    </svg>
  </div>

  <h4 class="cart-title">
      <?php esc_html_e('Ваш кошик', 'yappo'); ?>
  </h4>

  <form class="widget_shopping_cart_content">
      <?php woocommerce_mini_cart(); ?>
  </form>

</div>

<div class="added-success">
  <p>
    <span id="added_product_name"></span>

      <?php esc_html_e('успішно додано в Ваш кошик', 'yappo'); ?>
  </p>

  <button class="btn-blue orange-btn">
      <?php esc_html_e('ПЕРЕЙТИ В КОШИК', 'yappo'); ?>
  </button>
</div>

<div class="we-got-success <?php if (isset($_GET['wc_order_id'])) {
    echo 'we-got-success-active';
} ?>">
  <div class="we-got-success-block">
    <div class="row">
      <div class="col-md-6 p-0 order-md-1 order-2">
        <div class="left-col">
          <h3>
              <?php
              if (get_locale() == 'uk') esc_html_e('Ми отримали Ваше замовлення!', 'yappo');
              if (get_locale() == 'ru_RU') esc_html_e('Мы получили Ваш заказ!', 'yappo');
              ?>
          </h3>

          <p>
              <?php
              if (get_locale() == 'uk') _e('Очікуйте дзвінка від нашого <br> менеджера для підтвердження <br> Вашого замовлення.', 'yappo');
              if (get_locale() == 'ru_RU') _e('Ожидайте звонка от нашего <br>менеджера для подтверждения <br>Вашего заказа.', 'yappo');
              ?>

          </p>

          <div class="img-wrap">
            <img src="<?= get_theme_file_uri('assets/img/we-got.png') ?>" alt="we-got">
          </div>
        </div>
      </div>
        <?php
        $order = false;
        if (isset($_GET['wc_order_id'])) {
            $order = wc_get_order((int)$_GET['wc_order_id']);
            $seo = [];
            $i = 0;
            foreach ($order->get_items() as $item) {
                $product = $item->get_product();
                $id = $product->get_id();
                $seo[] = load_template_part('template-parts/seo/product', 'item', [
                    'i' => $i,
                    'title' => $item->get_name(),
                    'id' => $id,
                    'quantity' => $item->get_quantity(),
                    'list_name' => 'Thank you item',
                    'price' => $product->get_price()
                ]);
                $i++;
            }
            ?>
          <script>
              window.dataLayer = window.dataLayer || [];
              dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
              dataLayer.push({
                  event: "purchase",
                  ecommerce: {
                      transaction_id: "T<?= $order->get_order_number() ?>",
                      affiliation: "Online Store",
                      value: "<?= $order->get_total() ?>",
                      tax: "0.00",
                      shipping: "<?= $order->get_shipping_total(); ?>",
                      currency: "UAH",
                      items: [<?= implode(',', $seo) ?>]
                  }
              });
          </script>
            <?php
        }
        if (isset($order) && $order instanceof WC_Order) :?>
          <div class="col-md-6 p-0 order-md-1  order-1">
            <div class="right-col">
              <p class="chek-list">
                <span>№</span>
                <span class="number"><?= $order->get_order_number() ?></span>
              </p>


              <span class="data">
						<?php
            $date_paid = $order->get_date_created();

            ?>
            <?= $date_paid->date("d.m.Y") ?>
                    </span>

              <span class="tel">
                       <?= $order->get_billing_phone() ?>
                    </span>

              <span class="pay">
                       <?= $order->get_payment_method_title() ?>
                    </span>

              <span class="all-price-text">
                        <?php esc_html_e('Всього', 'yappo'); ?>
                    </span>

              <span class="all-price">
                        <?= $order->get_formatted_order_total() ?>
                    </span>
            </div>
          </div>
        <?php endif; ?>
    </div>
  </div>
</div>

<div class="modal-city-wrap modal-city-wrap-none">
  <div class="modal-city">
    <h6>
        <?php esc_html_e('Оберіть Ваше місто', 'yappo'); ?>
    </h6>

    <div id="city-chooser">
      <ul>
          <?php
          $cities = getCities();
          if (count($cities)) {
              foreach ($cities as $city) {
                  $sityArr = get_field('adresy', $city);
                  if (isset($sityArr)) {
                      foreach ($sityArr as $item) { ?>
                        <li>
                          <a href="#" data-id="<?= $city->slug ?>" data-address="<?= $item['item']['name'] ?>" class="active">
                              <?php the_field('city', $city) ?>&nbsp;
                            <span class="adress"> <?= $item['item']['name'] ?>  </span>
                          </a>
                        </li>
                      <?php }
                  } else { ?>
                    <li>
                      <a href="#" data-id="<?= $city->slug ?>" data-address="<?= $city->slug ?>" class="active">
                          <?php the_field('city', $city) ?>&nbsp;<span class="adress">
                           <?php the_field('adress', $city); ?>
                        </span>
                      </a>
                    </li>
                  <?php }
              }
          }
          ?>

      </ul>

      <button type="button" disabled class="btn-blue orange-btn">
          <?php esc_html_e('ПІДТВЕРДИТИ', 'yappo'); ?>
      </button>
    </div>


  </div>
</div>
<?php if (is_checkout()) :
    if (function_exists('get_choosed_city_data')) :
        $arr = get_choosed_city_data();
        ?>
      <script>
          $('body').on('init_checkout updated_checkout', function () {
              $('#billing_city').val('<?= get_field('city', $arr) ?>');
              $('#billing_address_1').val('<?= get_field('adress', $arr) ?>');
          })
      </script>
    <?php endif; ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
