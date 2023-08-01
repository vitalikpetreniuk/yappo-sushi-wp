<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.

    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

  <div class="summary entry-summary product-top">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xxl-10 col-xl-11 col-lg-12">

            <?php if (function_exists('yoast_breadcrumb')) {
                if (get_locale() == 'ru_RU') {
                    echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>', false));
                } else {
                    echo yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>');
                }
            } ?>
        </div>
      </div>

      <div class="card-produc-block">
        <div class="row align-items-center justify-content-center">
          <div class="col-xxl-5 col-xl-5 col-lg-5  col-md-12">
            <div class="product-img-wrap" data-columns="4">

                <?php yappo_product_badges(); ?>

              <a href="<?php the_permalink(); ?>" class="">
                  <?php the_post_thumbnail('full'); ?>
              </a>
            </div>
          </div>

          <div class="col-xxl-7 col-xl-7 col-lg-7 col-md-12">

            <div class="column-right">

                <?php woocommerce_template_single_title(); ?>

              <div class="description">
                <p><?php woocommerce_template_single_excerpt(); ?></p>
              </div>

              <div class="inform">
                <p>
                    <?php yappo_product_params($product); ?>
                </p>
              </div>

              <div class="row">
                <div class="col-md-7">
                    <?php
                    woocommerce_template_single_add_to_cart(); ?>
                </div>
              </div>


            </div>
          </div>

        </div>
      </div>
    </div>
    <script>
        dataLayer.push({
            'event': 'view_item_ads',
            'value': <?= $product->get_price(); ?>,
            'items': [
                {
                    'id': <?= $product->get_id(); ?>,
                    'google_business_vertical': 'retail'
                },
            ]
        });
    </script>

  </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>
  <section class="product-section news-product">
    <div class="container-fluid">
      <h2 class="section__title">
          <?php _e('НАПОЇ', 'yappo') ?>
      </h2>
    </div>


      <?php $query = new WP_Query(array(
          'post_type' => 'product',
          'posts_per_page' => 6,
          'tax_query' => [
              [
                  'taxonomy' => 'product_cat',
                  'field' => 'id',
                  'terms' => [
                      apply_filters('wpml_object_id', 101, 'product_cat'),
                      apply_filters('wpml_object_id', 102, 'product_cat')
                  ]
              ]
          ]
      ));
      ?>

      <?php woocommerce_product_loop_start(); ?>
      <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
          <?php wc_get_template_part('content', 'product'); ?>
      <?php
      endwhile; endif; ?>

      <?php woocommerce_product_loop_end(); ?>
  </section>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
