<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="facebook-domain-verification" content="2mkhcyecabhxzw0peutwhkk86b0u92"/>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  <link rel="shortcut icon" type="image/jpg" href="<?php echo get_template_directory_uri() ?>/favicon.ico"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link
      href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700&family=Press+Start+2P&display=swap"
      rel="stylesheet"
  />
  <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"
  />

  <!-- Google Tag Manager -->
  <script>(function (w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({
              'gtm.start':
                  new Date().getTime(), event: 'gtm.js'
          });
          var f = d.getElementsByTagName(s)[0],
              j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src =
              'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', 'GTM-5GVTP5D');</script>
  <!-- End Google Tag Manager -->
  <!-- Meta Pixel Code -->
  <script>
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
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php if (is_product_category()) echo ' itemscope itemtype="http://schema.org/Product"'; ?>>

<?php wp_body_open(); ?>
<?php do_action('storefront_before_site'); ?>

<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>

  <header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

      <?php
      /**
       * Functions hooked into storefront_header action
       *
       * @hooked storefront_header_container                 - 0
       * @hooked storefront_skip_links                       - 5
       * @hooked storefront_social_icons                     - 10
       * @hooked storefront_site_branding                    - 20
       * @hooked storefront_secondary_navigation             - 30
       * @hooked storefront_product_search                   - 40
       * @hooked storefront_header_container_close           - 41
       * @hooked storefront_primary_navigation_wrapper       - 42
       * @hooked storefront_primary_navigation               - 50
       * @hooked storefront_header_cart                      - 60
       * @hooked storefront_primary_navigation_wrapper_close - 68
       */
      //		do_action( 'storefront_header' );
      get_header('main');
      ?>

  </header><!-- #masthead -->

    <?php
    /**
     * Functions hooked in to storefront_before_content
     *
     * @hooked storefront_header_widget_region - 10
     * @hooked woocommerce_breadcrumb - 10
     */
    do_action('storefront_before_content');
    ?>

  <div id="content" class="site-content" tabindex="-1">
    <div class="col-full">

<?php
do_action('storefront_content_top');
