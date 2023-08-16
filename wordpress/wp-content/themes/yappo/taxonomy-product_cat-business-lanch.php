<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
} ?>
<?php

get_header('shop'); ?>
  <section class="categories-business-lunch">
      <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
        <div class="container">
          <div class="row align-items-center mb-md-5 mb-4">

            <div class="col-3 pe-0">
                <?php if (function_exists('yoast_breadcrumb')) {
                    if (get_locale() == 'ru_RU') echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>', false));
                    else echo yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>');
                } ?>
            </div>

            <div class="col-6">
              <h2 class="section__title">
                  <?php woocommerce_page_title(); ?>
              </h2>
            </div>

          </div>

        </div>

      <?php endif; ?>
      <?php

      /**
       * Hook: woocommerce_before_main_content.
       *
       * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
       * @hooked woocommerce_breadcrumb - 20
       * @hooked WC_Structured_Data::generate_website_data() - 30
       */
      do_action('woocommerce_before_main_content');

      ?>

      <?php
      /**
       * Hook: woocommerce_archive_description.
       *
       * @hooked woocommerce_taxonomy_archive_description - 10
       * @hooked woocommerce_product_archive_description - 10
       */
      do_action('woocommerce_archive_description');
      ?>

    <div class="container-fluid">
      <div class="row">
          <?php global $wp_query;
          $query = new WP_Query(array(
              'post__in' => [$wp_query->posts[0]->ID],
              'post_type' => 'product',
              'posts_per_page' => 1,
          ));
          ?>
          <?php if ($query->have_posts()) :
              while ($query->have_posts()) :
                  $query->the_post();

                  wc_get_template_part('content', 'product');
              endwhile;
          endif;
          wp_reset_query();
          ?>
        <div class="col-xl-8 col-lg-12 order-0 order-xl-auto">
          <div class="card-info">
            <div class="row justify-content-between">
              <div class="col-lg-7 col-md-8">
                <h3>
                    <?php
                    if (get_locale() == 'uk') _e('Зверни увагу! <br> Бізнес-ланчі доступні по буднях з 11:00 <br> по 16:00', 'yappo');
                    if (get_locale() == 'ru_RU') _e('Обрати внимание! <br> Бизнес-ланчи доступны по будням с 11:00 <br>по 16:00', 'yappo');
                    ?>
                </h3>
              </div>

              <div class="col-lg-5 col-md-4">
                <div class="img-wrap">
                  <img src="<?= get_theme_file_uri('assets/img/card-info.png') ?>" alt="card-info" loading="lazy">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

      <?php
      if (woocommerce_product_loop()) {

          /**
           * Hook: woocommerce_before_shop_loop.
           *
           * @hooked woocommerce_output_all_notices - 10
           * @hooked woocommerce_result_count - 20
           * @hooked woocommerce_catalog_ordering - 30
           */
//			do_action( 'woocommerce_before_shop_loop' );

          woocommerce_product_loop_start();

          if (wc_get_loop_prop('total')) {
              $i = 0;
              while (have_posts()) {
                  the_post();
                  $i++;
                  $product = wc_get_product(get_the_ID());
                  $seo[] = load_template_part('template-parts/seo/product', 'item', [
                      'i' => $i,
                      'quantity' => 1,
                      'list_name' => 'Product list',
                      'price' => $product->get_price()
                  ]);
                  /**
                   * Hook: woocommerce_shop_loop.
                   */
                  do_action('woocommerce_shop_loop');

                  wc_get_template_part('content', 'product');
              }
          }

          woocommerce_product_loop_end();

          /**
           * Hook: woocommerce_after_shop_loop.
           *
           * @hooked woocommerce_pagination - 10
           */

          do_action('woocommerce_after_shop_loop');
      } else {
          /**
           * Hook: woocommerce_no_products_found.
           *
           * @hooked wc_no_products_found - 10
           */
          do_action('woocommerce_no_products_found');
      }

      /**
       * Hook: woocommerce_after_main_content.
       *
       * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
       */
      do_action('woocommerce_after_main_content');
      /**
       * Hook: woocommerce_sidebar.
       *
       * @hooked woocommerce_get_sidebar - 10
       */
      //		do_action( 'woocommerce_sidebar' );
      ?>
  </section>
  <section class="accordion-section">
    <div class="container-fluid">

      <div class="accordeon">
          <?php
          if (have_rows('questions', get_queried_object())):
              while (have_rows('questions', get_queried_object())) : the_row();
                  ?>
                <div class="slide-wrap">
                  <div class="slide-header">
                    <span class="glyphicon glyphicon-chevron-down"></span>
                    <h4>
                        <?php the_sub_field('question') ?>
                    </h4>

                    <span class="span-plus"></span>
                  </div>
                  <div class="slide-content">
                      <?php the_sub_field('answer'); ?>
                  </div>
                </div>
              <?php
              endwhile;
          endif; ?>
      </div>
    </div>
  </section>
<?php if (count($seo)) : ?>
  <script>
      // Measure product views / impressions
      window.dataLayer = window.dataLayer || [];
      dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
      dataLayer.push({
          event: "view_item_list",
          ecommerce: {
              items: [
                  <?= implode(',', $seo) ?>
              ]
          }
      });
  </script>
<?php endif; ?>
<?php get_template_part('template-parts/content', 'whatpayreceive'); ?>
<?php

get_footer('shop');
