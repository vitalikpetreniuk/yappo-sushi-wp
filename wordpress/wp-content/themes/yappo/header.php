<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package test
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <link rel="shortcut icon" href="<?= get_theme_file_uri('assets/img/mob-logo.svg') ?>" type="image/x-icon">
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://fonts.gstatic.com" crossorigin>

    <?php
    if (strpos($_SERVER['REQUEST_URI'], '&pa_ingredients') || strpos($_SERVER['REQUEST_URI'], '&max_price') || strpos($_SERVER['REQUEST_URI'], '&min_price') || strpos($_SERVER['REQUEST_URI'], '&product_tag')) { ?>
      <link rel="canonical" href="<?php echo $_SERVER['REQUEST_URI'] ?>"/>
    <?php }
    ?>
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>
<header class="header">
  <div class="header-top">
    <div class="container-fluid">
      <div class="row justify-content-between align-items-center">

        <div class="col-lg-4 col-md-3 d-md-block d-none">
          <a class="tel-header-top" itemprop="telephone"
             href="tel:<?php the_field('phone_number', 'option') ?>"><?php the_field('phone_number', 'option') ?></a>
        </div>

        <div class="col-lg-4 col-md-5 d-none">
            <?php if (get_field('top_sticky_text', 'option')) : ?>
              <p>
                  <?php the_field('top_sticky_text', 'option') ?>
              </p>
            <?php endif; ?>
            <?php if (function_exists('yappo_get_chosen_header_adress')) : ?>
                <?= yappo_get_chosen_header_adress(); ?>
            <?php endif; ?>
        </div>

        <div class="col-xxl-3 col-lg-3 col-md-4 col-3 d-md-block p-0 header__location">
          <div class="local-wrap">
            <a class="local" href="#" target="_blank">
                <?php if (function_exists('yappo_get_chosen_header_adress')) { ?>
                  <img src="<?= get_theme_file_uri('assets/img/white-location.svg') ?>" alt="location" width="10"
                       height="20">
                <?php } ?>
                <?= yappo_get_chosen_header_adress(); ?>
            </a>

            <div class="city-list">
              <h6>
                  <?php esc_html_e('Оберіть місто', 'yappo'); ?>
              </h6>

              <div>
                <ul itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <?php
                    $cities = getCities();
                    $cityLink = '';


                    if (count($cities)) {
                        foreach ($cities as $city) {
                            if (!is_checkout()) {
                                $cityLink = rtrim(home_url(), '/') . '/' . $city->slug;
                            }
                            $sityArr = get_field('adresy', $city);
                            ?>

                            <?php foreach ($sityArr as $index => $item) { ?>
                            <li>
                              <a href="<?= $cityLink ?>"
                                 data-id="<?= $city->slug ?>"
                                 data-address="<?= $city->slug . '/' . $index ?>">
                                <div itemprop="addressLocality">
                                    <?php the_field('city', $city) ?>
                                </div>
                                <span class="adress" itemprop="streetAddress"><?= $item['item']['name'] ?></span>
                              </a>
                            </li>
                            <?php }
                        }
                    }
                    ?>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="header-center">
    <div class="container-fluid">
      <div class="row justify-content-between align-items-center align-items-md-end" itemscope
           itemtype="http://schema.org/Organization">

        <div class="col-lg-2 col-md-4 col-6 pe-0 pe-md-3 me-lg-5 me-md-0">
          <div class="logo">
            <a href="<?= rtrim(home_url(), '/') ?>/<?= yappo_get_chosen_city_slug() ?>" itemprop="url">
              <div itemprop="image">
                  <?= wp_get_attachment_image(get_field('logo', 'option'), 'full') ?>
              </div>
              <div itemprop="name" hidden> <?php bloginfo('name') ?> </div>
            </a>
          </div>
        </div>

        <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-4 ms-0 me-auto d-md-block d-none p-0">

          <div class="row align-items-center">
            <div class="col-xl-6  col-lg-5 col-md-6 d-lg-block d-none p-0">
              <div class="time-wrap d-flex align-items-center">

                <div>
                  <img width="40px" height="40px"
                       src="<?= get_theme_file_uri('assets/img/clock.svg') ?>" alt="clock">
                </div>

                <div itemscope itemtype="http://schema.org/LocalBusiness">
										<span>
											<?php the_field('working_days', 'option'); ?>
										</span>
                  <time itemprop="openingHours" datetime="<?php the_field('working_hours', 'option') ?>">
                      <?php the_field('working_hours', 'option') ?>
                  </time>
                </div>
              </div>

            </div>

            <div class="col-lg-6 col-md-9">
              <div class="social-wrap d-flex justify-content-between">
                  <?php if (get_field('instagram_link', 'option')) : ?>
                    <a href="<?php the_field('instagram_link', 'option') ?>" rel="nofollow" target="_blank">
                      <svg class="hover-effect-svg" width="23" height="24" viewBox="0 0 23 24"
                           fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                        <rect width="23" height="24" rx="5" fill="#2A1A5E"/>
                        <circle cx="11.5" cy="12.5" r="4.5" stroke="white" stroke-width="4"/>
                        <circle cx="17" cy="5" r="2" fill="white"/>
                      </svg>
                    </a>
                  <?php endif; ?>

                  <?php if (get_field('facebook_link', 'option')) : ?>
                    <a href="<?php the_field('facebook_link', 'option') ?>" rel="nofollow" target="_blank">
                      <svg class="hover-effect-svg" style="margin-top: 2px;" width="23" height="27"
                           viewBox="0 0 23 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="23" height="24" rx="5" fill="#2A1A5E"/>
                        <path
                            d="M12 24.9995V13.4994M16.5 6C11.5 6.00006 12 11.4995 12 11.4995V13.4994M12 13.4994H8.5M12 13.4994H15.5"
                            stroke="white" stroke-width="4" stroke-linecap="round"
                            stroke-linejoin="round"/>
                      </svg>
                    </a>
                  <?php endif; ?>

                  <?php if (get_field('viber_link', 'option')) : ?>
                    <a href="<?php the_field('viber_link', 'option') ?>" rel="nofollow" target="_blank">
                      <svg class="hover-effect-svg" width="38" height="33" viewBox="0 0 38 33"
                           fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.54512 23.4781L8.54508 23.4781C5.5858 23.2518 3.25635 20.8939 3.12975 17.9591C3.05615 16.2527 3 14.4602 3 12.9683C3 11.8748 3.03017 10.6186 3.0754 9.35156C3.21108 5.55025 6.13397 2.46574 9.90382 2.19547C11.5049 2.08069 13.1229 2 14.5 2C15.8771 2 17.4951 2.08069 19.0962 2.19547C22.866 2.46574 25.7889 5.55025 25.9246 9.35156C25.9698 10.6186 26 11.8748 26 12.9683C26 13.9539 25.9755 15.0722 25.9375 16.2119C25.8063 20.1455 22.6719 23.3086 18.7024 23.6122L15.9031 23.8263C14.7508 23.9144 13.6458 24.3217 12.712 25.0026L10.0954 26.9105C9.67563 27.2166 9.0961 26.8585 9.18199 26.3461L9.46562 24.654L8.47938 24.4887L9.46562 24.654C9.56389 24.0677 9.13785 23.5235 8.54512 23.4781Z"
                            fill="#2A1A5E" stroke="#2A1A5E" stroke-width="2"/>
                        <path
                            d="M9.46243 15.3745C7.46808 13.3802 7.20789 10.2363 8.84723 7.94122C9.36531 7.21591 10.4107 7.1294 11.0409 7.75966L12.2402 8.95896C12.9954 9.71417 12.9113 10.962 12.0616 11.6091C11.2119 12.2561 11.1278 13.504 11.883 14.2592L13.2421 15.6183C13.9973 16.3735 15.2451 16.2894 15.8922 15.4396C16.5392 14.5899 17.7871 14.5058 18.5423 15.261L19.7416 16.4603C20.3718 17.0906 20.2853 18.1359 19.56 18.654C17.2649 20.2933 14.121 20.0331 12.1267 18.0388L9.46243 15.3745Z"
                            fill="white"/>
                        <path
                            d="M15 10.6923V10.6923C16.2745 10.6923 17.3077 11.7255 17.3077 13V13M15 7V7C18.3137 7 21 9.68629 21 13V13"
                            stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"/>
                        <rect x="15" y="21" width="23" height="12" rx="6" fill="#2A1A5E"/>
                        <path
                            d="M20 26.5V29C20 29.5523 20.4477 30 21 30H22.25C23.2165 30 24 29.2165 24 28.25V28.25C24 27.2835 23.2165 26.5 22.25 26.5H20ZM20 26.5V25.5"
                            stroke="white"/>
                        <path
                            d="M20 26.5V25.5C20 24.6716 20.6716 24 21.5 24H21.75C22.4404 24 23 24.5596 23 25.25C23 25.9404 22.4404 26.5 21.75 26.5H20Z"
                            stroke="white"/>
                        <path
                            d="M29.5 25.5H31M32.5 25.5H31M31 25.5V28.5C31 29.3284 31.6716 30 32.5 30V30M31 25.5V24M28.5 28.5V27.5C28.5 26.6716 27.8284 26 27 26V26C26.1716 26 25.5 26.6716 25.5 27.5V28.5C25.5 29.3284 26.1716 30 27 30V30C27.8284 30 28.5 29.3284 28.5 28.5Z"
                            stroke="white" stroke-linecap="round"/>
                        <circle cx="25" cy="3" r="3" fill="#CDE211"/>
                      </svg>
                    </a>
                  <?php endif; ?>

                  <?php if (get_field('telegram_link', 'option')) : ?>
                    <a href="<?php the_field('telegram_link', 'option') ?>" rel="nofollow" target="_blank">
                      <svg class="hover-effect-svg" style="margin-top: 2px;" width="41" height="34"
                           viewBox="0 0 41 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="17" cy="12" r="12" fill="#2A1A5E"/>
                        <path
                            d="M20.6343 6.85427L9.82882 10.7563C8.41867 11.2655 8.54556 13.3002 10.008 13.6303L11.3874 13.9416C11.9075 14.059 12.4529 13.9638 12.9025 13.6773L14.8875 12.4124C15.3694 12.1053 15.8906 12.7428 15.4938 13.154C14.6123 14.0675 14.8382 15.5687 15.9495 16.1824L19.163 17.957C20.0781 18.4623 21.2166 17.9001 21.3715 16.8662L22.6272 8.48742C22.7956 7.36405 21.7027 6.46847 20.6343 6.85427Z"
                            fill="white"/>
                        <circle cx="28" cy="4" r="3" fill="#CDE211"/>
                        <rect x="17" y="20" width="23" height="12" rx="6" fill="#2A1A5E"/>
                        <path
                            d="M22 25.5V28C22 28.5523 22.4477 29 23 29H24.25C25.2165 29 26 28.2165 26 27.25V27.25C26 26.2835 25.2165 25.5 24.25 25.5H22ZM22 25.5V24.5"
                            stroke="white"/>
                        <path
                            d="M22 25.5V24.5C22 23.6716 22.6716 23 23.5 23H23.75C24.4404 23 25 23.5596 25 24.25C25 24.9404 24.4404 25.5 23.75 25.5H22Z"
                            stroke="white"/>
                        <path
                            d="M31.5 24.5H33M34.5 24.5H33M33 24.5V27.5C33 28.3284 33.6716 29 34.5 29V29M33 24.5V23M30.5 27.5V26.5C30.5 25.6716 29.8284 25 29 25V25C28.1716 25 27.5 25.6716 27.5 26.5V27.5C27.5 28.3284 28.1716 29 29 29V29C29.8284 29 30.5 28.3284 30.5 27.5Z"
                            stroke="white" stroke-linecap="round"/>
                      </svg>
                    </a>
                  <?php endif; ?>

              </div>
            </div>
          </div>

        </div>

        <div class="col-lg-auto col-md-4 col-4 ps-0 ps-md-auto mb-lg-2 mb-md-1 mb-0">
          <div class="btns-wrap btns-wrap-header d-flex align-items-center justify-content-between">

              <?php yappo_lang_opener('d-md-block d-none'); ?>

            <a href="tel:<?php the_field('phone_number', 'option') ?>"
               class="tel d-md-block d-none mt-1 ">
              <svg class="hover-effect-svg" width="29" height="29" viewBox="0 0 29 29" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.78604 16.2733C4.10831 12.5956 3.62849 6.79807 6.65157 2.56576C7.60693 1.22825 9.53463 1.06871 10.6969 2.23096L12.9085 4.44254C14.3011 5.83521 14.146 8.13635 12.5791 9.32953C11.0121 10.5227 10.857 12.8239 12.2497 14.2165L14.7559 16.7228C16.1486 18.1154 18.4497 17.9603 19.6429 16.3934C20.8361 14.8264 23.1372 14.6713 24.5299 16.064L26.7415 18.2756C27.9037 19.4378 27.7442 21.3655 26.4067 22.3209C22.1744 25.344 16.3768 24.8641 12.6991 21.1864L7.78604 16.2733Z"
                    fill="#2A1A5E"/>
              </svg>
            </a>

            <div class="cart cart-header order-md-1 order-2">
							<span class="mini-cart-count <?php if (WC()->cart->get_cart_contents_count()) {
                  echo 'mini-cart-count-active';
              } ?>"><?= WC()->cart->get_cart_contents_count() ?></span>

              <svg class="hover-effect-svg" width="24" height="25" viewBox="0 0 24 25" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0.0302734 10.8458C0.0302734 8.7218 1.75208 7 3.87602 7H19.1845C21.3085 7 23.0303 8.7218 23.0303 10.8458V10.8458C23.0303 14.2744 22.7093 17.6955 22.0716 21.0643L22.0319 21.2741C21.6228 23.4354 19.7342 25 17.5345 25H11.5303H5.52603C3.32639 25 1.43779 23.4354 1.02867 21.2741L0.988949 21.0643C0.351232 17.6955 0.0302734 14.2744 0.0302734 10.8458V10.8458Z"
                    fill="#2A1A5E"/>
                <path
                    d="M7.03027 7V6.5C7.03027 4.01472 9.04499 2 11.5303 2V2C14.0156 2 16.0303 4.01472 16.0303 6.5V7"
                    stroke="#2A1A5E" stroke-width="3"/>
              </svg>
            </div>

            <div class="form-search  order-md-2 order-1">
              <div class="form-search-btn">
                <div class="btn-open-search">
                  <svg class="hover-effect-svg" width="29" height="29" viewBox="0 0 29 29" fill="none"
                       xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.7756 17.7453C14.8993 20.6216 10.2359 20.6216 7.35964 17.7453C4.48335 14.869 4.48335 10.2056 7.35964 7.3293C10.2359 4.45301 14.8993 4.45301 17.7756 7.3293C20.6519 10.2056 20.6519 14.869 17.7756 17.7453Z"
                        stroke="#2A1A5E" stroke-width="3"/>
                    <path
                        d="M21.5368 23.6279C22.1226 24.2137 23.0723 24.2137 23.6581 23.6279C24.2439 23.0421 24.2439 22.0924 23.6581 21.5066L21.5368 23.6279ZM17.1488 19.2399L21.5368 23.6279L23.6581 21.5066L19.2701 17.1185L17.1488 19.2399Z"
                        fill="#2A1A5E"/>
                  </svg>
                </div>

                <div class="close-open-search">
                  <svg class="hover-effect-svg" width="22" height="22" viewBox="0 0 22 22" fill="none"
                       xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 2L20.5 20.5" stroke="#2A1A5E" stroke-width="3"
                          stroke-linecap="round"/>
                    <path d="M2 20.5005L20.5 2.00051" stroke="#2A1A5E" stroke-width="3"
                          stroke-linecap="round"/>
                  </svg>
                </div>
              </div>
            </div>

            <div class="burger-desck order-4">
              <span></span>
              <span></span>
              <span></span>
            </div>

            <div class="btns-wrap-header-menu">
              <ul>
                  <?php wp_nav_menu(
                      array(
                          'container' => false,
                          'theme_location' => 'primary',
                      )
                  ) ?>
              </ul>
            </div>
            <div class="burger order-3">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="header-bottom">
    <div class="container-fluid">
      <nav>
        <div class="header__category">
          <ul>
              <?php
              $terms = get_field('categories_in_header', 'option');
              $singleCategorySlug = '';
              if (is_single()) {
                  $singleCategory = get_the_terms(get_the_ID(), 'product_cat');
                  $singleCategorySlug = [];
                  foreach ($singleCategory as $item) {
                      $singleCategorySlug[] = $item->slug;
                  }

              }
              foreach ($terms as $term) {
                  $categoryUrl = rtrim(home_url(), '/') . '/product-category/' . $term->slug;
                  $categoryID = get_queried_object_id();
                  if (yappo_get_chosen_city_slug()) {
                      $categoryUrl = rtrim(home_url(), '/') . '/' . yappo_get_chosen_city_slug() . '/' . $term->slug;
                  }
                  $urlParts = explode('?', $_SERVER['REQUEST_URI']);
                  $basePart = $urlParts[0];
                  $basePartWords = explode('/', rtrim($basePart, '/'));
                  $lastWord = end($basePartWords);
                  ?>

                <li>
                  <a class="link-category
                  <?php if (($categoryID === $term->term_id || $lastWord === $term->slug) && !is_checkout()) {
                      echo 'link-category-active';
                  }
                  foreach ($singleCategorySlug as $item){
                    if($item === $term->slug) {
                        echo 'link-category-active';
                    }
                  }
                  ?>"
                     href="<?= $categoryUrl ?>">
                    <div class="cotegory_img">
                        <?php if (get_field('image', $term)) : ?>
                          <img class="image-category"
                               src="<?= wp_get_attachment_image_url(get_field('image', $term)); ?>"
                               alt="<?= $term->name ?>" loading="lazy">
                        <?php endif; ?>
                        <?php if (get_field('hover_image', $term)) : ?>
                          <img class="image-category-active"
                               src="<?= wp_get_attachment_image_url(get_field('hover_image', $term)); ?>"
                               alt="<?= $term->name ?>" loading="lazy">
                        <?php endif; ?>
                    </div>

                    <div class="сhat-bubbles-wrap">
                      <div class="сhat-bubbles">
                        <p>
                            <?= $term->name; ?>
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              <?php } ?>
          </ul>
        </div>
      </nav>
    </div>

    <!-- cart fixed -->
      <?php if (function_exists('yappo_mini_cart')) {
          yappo_mini_cart();
      } ?>
  </div>


  <div id="menu">
    <div class="menu-body">
      <div class="time-wrap d-flex align-items-center">
        <div>
          <img width="40px" height="40px" src="<?= get_theme_file_uri('assets/img/clock.svg') ?>"
               alt="clock">
        </div>
        <div>
          <span><?php the_field('working_days', 'option'); ?></span>
          <p>
              <?php the_field('working_hours', 'option') ?>
          </p>
        </div>
      </div>


      <nav class="top-nav">
          <?php wp_nav_menu(
              array(
                  'container' => false,
                  'theme_location' => 'primary',
              )
          ) ?>
      </nav>

        <?php yappo_lang_opener(); ?>


      <div class="social-wrap d-flex justify-content-center align-items-center">
          <?php if (get_field('instagram_link', 'option')) : ?>
            <a href="<?php the_field('instagram_link', 'option') ?>" rel="nofollow" target="_blank">
              <svg class="hover-effect-svg" width="23" height="24" viewBox="0 0 23 24"
                   fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <rect width="23" height="24" rx="5" fill="#2A1A5E"/>
                <circle cx="11.5" cy="12.5" r="4.5" stroke="white" stroke-width="4"/>
                <circle cx="17" cy="5" r="2" fill="white"/>
              </svg>
            </a>
          <?php endif; ?>

          <?php if (get_field('facebook_link', 'option')) : ?>
            <a href="<?php the_field('facebook_link', 'option') ?>" rel="nofollow" target="_blank">
              <svg class="hover-effect-svg" style="margin-top: 2px;" width="23" height="27"
                   viewBox="0 0 23 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="23" height="24" rx="5" fill="#2A1A5E"/>
                <path
                    d="M12 24.9995V13.4994M16.5 6C11.5 6.00006 12 11.4995 12 11.4995V13.4994M12 13.4994H8.5M12 13.4994H15.5"
                    stroke="white" stroke-width="4" stroke-linecap="round"
                    stroke-linejoin="round"/>
              </svg>
            </a>
          <?php endif; ?>

          <?php if (get_field('viber_link', 'option')) : ?>
            <a href="<?php the_field('viber_link', 'option') ?>" rel="nofollow" target="_blank">
              <svg class="hover-effect-svg" width="38" height="33" viewBox="0 0 38 33"
                   fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.54512 23.4781L8.54508 23.4781C5.5858 23.2518 3.25635 20.8939 3.12975 17.9591C3.05615 16.2527 3 14.4602 3 12.9683C3 11.8748 3.03017 10.6186 3.0754 9.35156C3.21108 5.55025 6.13397 2.46574 9.90382 2.19547C11.5049 2.08069 13.1229 2 14.5 2C15.8771 2 17.4951 2.08069 19.0962 2.19547C22.866 2.46574 25.7889 5.55025 25.9246 9.35156C25.9698 10.6186 26 11.8748 26 12.9683C26 13.9539 25.9755 15.0722 25.9375 16.2119C25.8063 20.1455 22.6719 23.3086 18.7024 23.6122L15.9031 23.8263C14.7508 23.9144 13.6458 24.3217 12.712 25.0026L10.0954 26.9105C9.67563 27.2166 9.0961 26.8585 9.18199 26.3461L9.46562 24.654L8.47938 24.4887L9.46562 24.654C9.56389 24.0677 9.13785 23.5235 8.54512 23.4781Z"
                    fill="#2A1A5E" stroke="#2A1A5E" stroke-width="2"/>
                <path
                    d="M9.46243 15.3745C7.46808 13.3802 7.20789 10.2363 8.84723 7.94122C9.36531 7.21591 10.4107 7.1294 11.0409 7.75966L12.2402 8.95896C12.9954 9.71417 12.9113 10.962 12.0616 11.6091C11.2119 12.2561 11.1278 13.504 11.883 14.2592L13.2421 15.6183C13.9973 16.3735 15.2451 16.2894 15.8922 15.4396C16.5392 14.5899 17.7871 14.5058 18.5423 15.261L19.7416 16.4603C20.3718 17.0906 20.2853 18.1359 19.56 18.654C17.2649 20.2933 14.121 20.0331 12.1267 18.0388L9.46243 15.3745Z"
                    fill="white"/>
                <path
                    d="M15 10.6923V10.6923C16.2745 10.6923 17.3077 11.7255 17.3077 13V13M15 7V7C18.3137 7 21 9.68629 21 13V13"
                    stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"/>
                <rect x="15" y="21" width="23" height="12" rx="6" fill="#2A1A5E"/>
                <path
                    d="M20 26.5V29C20 29.5523 20.4477 30 21 30H22.25C23.2165 30 24 29.2165 24 28.25V28.25C24 27.2835 23.2165 26.5 22.25 26.5H20ZM20 26.5V25.5"
                    stroke="white"/>
                <path
                    d="M20 26.5V25.5C20 24.6716 20.6716 24 21.5 24H21.75C22.4404 24 23 24.5596 23 25.25C23 25.9404 22.4404 26.5 21.75 26.5H20Z"
                    stroke="white"/>
                <path
                    d="M29.5 25.5H31M32.5 25.5H31M31 25.5V28.5C31 29.3284 31.6716 30 32.5 30V30M31 25.5V24M28.5 28.5V27.5C28.5 26.6716 27.8284 26 27 26V26C26.1716 26 25.5 26.6716 25.5 27.5V28.5C25.5 29.3284 26.1716 30 27 30V30C27.8284 30 28.5 29.3284 28.5 28.5Z"
                    stroke="white" stroke-linecap="round"/>
                <circle cx="25" cy="3" r="3" fill="#CDE211"/>
              </svg>
            </a>
          <?php endif; ?>

          <?php if (get_field('telegram_link', 'option')) : ?>
            <a href="<?php the_field('telegram_link', 'option') ?>" rel="nofollow" target="_blank">
              <svg class="hover-effect-svg" style="margin-top: 2px;" width="41" height="34"
                   viewBox="0 0 41 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="17" cy="12" r="12" fill="#2A1A5E"/>
                <path
                    d="M20.6343 6.85427L9.82882 10.7563C8.41867 11.2655 8.54556 13.3002 10.008 13.6303L11.3874 13.9416C11.9075 14.059 12.4529 13.9638 12.9025 13.6773L14.8875 12.4124C15.3694 12.1053 15.8906 12.7428 15.4938 13.154C14.6123 14.0675 14.8382 15.5687 15.9495 16.1824L19.163 17.957C20.0781 18.4623 21.2166 17.9001 21.3715 16.8662L22.6272 8.48742C22.7956 7.36405 21.7027 6.46847 20.6343 6.85427Z"
                    fill="white"/>
                <circle cx="28" cy="4" r="3" fill="#CDE211"/>
                <rect x="17" y="20" width="23" height="12" rx="6" fill="#2A1A5E"/>
                <path
                    d="M22 25.5V28C22 28.5523 22.4477 29 23 29H24.25C25.2165 29 26 28.2165 26 27.25V27.25C26 26.2835 25.2165 25.5 24.25 25.5H22ZM22 25.5V24.5"
                    stroke="white"/>
                <path
                    d="M22 25.5V24.5C22 23.6716 22.6716 23 23.5 23H23.75C24.4404 23 25 23.5596 25 24.25C25 24.9404 24.4404 25.5 23.75 25.5H22Z"
                    stroke="white"/>
                <path
                    d="M31.5 24.5H33M34.5 24.5H33M33 24.5V27.5C33 28.3284 33.6716 29 34.5 29V29M33 24.5V23M30.5 27.5V26.5C30.5 25.6716 29.8284 25 29 25V25C28.1716 25 27.5 25.6716 27.5 26.5V27.5C27.5 28.3284 28.1716 29 29 29V29C29.8284 29 30.5 28.3284 30.5 27.5Z"
                    stroke="white" stroke-linecap="round"/>
              </svg>
            </a>
          <?php endif; ?>

      </div>

      <nav class="bottom-nav">
          <?php wp_nav_menu(array(
              'theme_location' => 'primary-mob',
              'container' => false
          )) ?>
      </nav>

      <div class="img-wrap">
        <img width="72px" height="25" src="<?= get_theme_file_uri('assets/img/visa-black.svg') ?>" loading="lazy"
             alt="visa-card">
      </div>

      <p>
        © <?= date('Y') ?> <?php the_field('copyright', 'option') ?>
      </p>
    </div>
  </div>

</header>
<div class="modal-search">

  <div class="container-fluid">
    <div class="form-wrap">
        <?php echo do_shortcode('[wpdreams_ajaxsearchpro id=1]'); ?>
    </div>

      <?php echo do_shortcode('[wpdreams_asp_settings id=1 element="div"]'); ?>
  </div>

  <div class="footer-modal">
      <?= get_template_part('template-parts/content', 'footer') ?>
  </div>

</div>

<main class="main">
