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
<script>

</script>
<button onclick="topScroll()" id="scrollTop" title="Go to top">
  <svg width="29" height="14" viewBox="0 0 29 14" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path id="Vector 58" d="M13.8415 0.576191L0.502945 12.2474C-0.191958 12.8555 0.238085 14 1.16145 14H27.8386C28.7619 14 29.192 12.8555 28.4971 12.2474L15.1585 0.576192C14.7815 0.246294 14.2185 0.246293 13.8415 0.576191Z" fill="#2A1A5E"/>
  </svg>
</button>

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
            <img src="<?= get_theme_file_uri('assets/img/we-got.png') ?>" alt="we-got" loading="lazy">
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
        <?php if (isset($_GET['wc_order_id'])) { ?>
          <script>
              dataLayer.push({
                  'event': 'purchase_ads',
                  'value': <?= $order->get_order_number() ?>,
                  'items': [
                      {
                          'id': <?= $order->get_shipping_total(); ?>,
                          'google_business_vertical': 'retail'
                      },
                  ]
              });
          </script>
        <?php } ?>
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
                      foreach ($sityArr as $index=>$item) { ?>
                        <li>
                          <a href="#" data-id="<?= $city->slug ?>" data-address="<?= $city->slug . '/' . $index  ?>"
                             class="active">
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
      <script defer>
          $('body').on('init_checkout updated_checkout', function () {
              $('#billing_city').val('<?= get_field('city', $arr) ?>');
              $('#billing_address_1').val('<?= get_field('adress', $arr) ?>');
          })
      </script>
    <?php endif; ?>
<?php endif; ?>
<script defer>
    document.oncopy = function () {
        let bodyElement = document.body;
        let selection = getSelection();
        let href = document.location.href;
        let copyright = "<br><br>Источник: <a href='" + href + "'>" + href + "</a><br>© YappoSushi";
        let text = selection + copyright;
        let divElement = document.createElement('div');
        divElement.style.position = 'absolute';
        divElement.style.left = '-99999px';
        divElement.innerHTML = text;
        bodyElement.appendChild(divElement);
        selection.selectAllChildren(divElement);
        setTimeout(function () {
            bodyElement.removeChild(divElement);
        }, 0);
    };
</script>


<!-- Meta Pixel Code -->
<script defer type="c731a9971cd8d55081298037-text/javascript">
		!function (f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function () {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '488055693044741');
		fbq('track', 'PageView');
  </script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=488055693044741&ev=PageView&noscript=1"
  /></noscript>
<!-- End Meta Pixel Code -->

<!-- Google Tag Manager (noscript) -->
<noscript>
  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5GVTP5D"
          height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>

<script>
    const breadcrumbsItems = document.querySelectorAll('.breadcrumbs li a');
    if (breadcrumbsItems.length > 0) {
        breadcrumbsItems.forEach((elem, index) => {
            elem.querySelector('meta').setAttribute('content', index);
        })
    }
</script>
<!-- End Google Tag Manager (noscript) -->
<?php wp_footer(); ?>
</body>
</html>
