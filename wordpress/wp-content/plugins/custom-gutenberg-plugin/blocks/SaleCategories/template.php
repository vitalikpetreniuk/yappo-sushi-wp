<?php
/**
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if ( @$block['data']['preview_image_help'] ) : ?>
	<img src="<?= plugin_dir_url( __FILE__ ) ?>/screenshot.png" alt="">
<?php else:
    // Your block html goes here
    ?>
  <section class="discount-sale-page">

    <div class="container-fluid">
      <div class="row justify-content-xxl-center justify-content-lg-between justify-content-center">
          <?php
          $args = array(
              'hierarchical' => 1,
              'show_option_none' => '',
              'hide_empty' => 0, // Set to 0 to show empty categories and 1 to hide them
              'parent' => get_field('category', $post_id)->term_id,
              'taxonomy' => 'product_cat'
          );
          $subcategories = get_categories($args);
          $i = 0;
          foreach ($subcategories as $subcategory) {
              $i++;
              $link = get_term_link($subcategory->slug, $subcategory->taxonomy);
              $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
              $image = wp_get_attachment_url($thumbnail_id);

              ?>
            <div class="col-xxl-5 col-lg-6  col-11 px-xxl-5 px-lg-4">
              <div class="banner-slider__slide swiper-slide slide-<?= $i ?>">
                <a href="<?= rtrim(home_url(), '/') ?>/<?= yappo_get_chosen_city_slug() ?>/<?= get_field('category', $post_id)->slug ?>/<?= $subcategory->slug ?>">
                  <img src="<?= wp_get_attachment_image_url($thumbnail_id, 'full') ?>"
                       alt="<?= $subcategory->name ?>">
                </a>
              </div>
            </div>
          <?php } ?> 
      </div>
    </div>

  </section>

<?php endif; ?>
