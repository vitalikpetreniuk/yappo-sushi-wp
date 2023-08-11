<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */
/**
 * Template Name: Product list
 */

defined('ABSPATH') || exit;
get_header('shop');
$cate = get_queried_object();
$cateID = $cate->term_id;
$parentcats = get_ancestors($cateID, 'product_cat');
$min_max = get_product_category_min_max();
if ($cateID != 23) { ?>
  <span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
        <meta content="<?= $cate->count ?>" itemprop="offerCount">
        <meta content="<?= $min_max['min'] ?>" itemprop="lowPrice">
        <meta content="<?= $min_max['max'] ?>" itemprop="highPrice">
        <meta content="UAH" itemprop="priceCurrency">
    </span>
<?php } ?>
  <section class="category-page">
      <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
        <div class="container">
          <div class="row align-items-center mb-md-5 mb-4">

            <div class="col-3 pe-0">
                <?php if (function_exists('yoast_breadcrumb')) {
                    if (get_locale() == 'ru_RU') echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>', false));
                    else echo yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>');
                }
                $rolls_id = apply_filters('wpml_object_id', 72, 'product_tag');
                $title_class = '';
                if (get_queried_object()->term_taxonomy_id == $rolls_id || get_queried_object()->parent == $rolls_id) {
                    $title_class = 'title-bg-two';
                }
                ?>
            </div>

            <div class="col-6">
              <h1 class="section__title <?= $title_class ?>">
                  <?php
                  woocommerce_page_title($cateID);
                  ?>
              </h1>
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
