<?php
/**
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if (@$block['data']['preview_image_help']) : ?>
  <img src="<?= plugin_dir_url(__FILE__) ?>/screenshot.png" alt="">
<?php else:
    // Your block html goes here
    ?>
  <section class="menu-page">

    <div class="container">
      <div class="row align-items-start mb-md-5 mb-4">

        <div class="col-3 pe-0">
            <?php if (function_exists('yoast_breadcrumb')) {
                if (get_locale() == 'ru_RU') echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>', false));
                else echo yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>');
            } ?>
        </div>

        <div class="col-6">
          <h2 class="section__title">
              <?php echo str_replace(' ', '<br>', get_the_title()); ?>
          </h2>
        </div>

      </div>

    </div>

    <div class="container-fluid">
      <nav>
        <div class="row">
            <?php
            // Check rows existexists.
            if (have_rows('categories')):
                while (have_rows('categories')) : the_row();
                    $term = get_sub_field('category');
                    $categoryUrl = rtrim(home_url(), '/') . '/product-category/' . $term->slug;
                    $categoryID = get_queried_object_id();
                    if (yappo_get_chosen_city_slug()) {
                        $categoryUrl = rtrim(home_url(), '/') . '/' . yappo_get_chosen_city_slug() . '/' . $term->slug;
                    }
                    $size = '';
                    ?>
                    <?php if (get_sub_field('size') == 'third') {
                        $size = 'col-md-4 pe-md-0 pe-3';
                    } elseif (get_sub_field('size') == 'half') {
                        $size = 'col-md-6  pe-md-0 pe-3';
                    }
                    ?>
                  <div class="<?= $size ?>">
                    <a href="<?= $categoryUrl ?>"
                       class="menu-item">

                        <?= get_sub_field('category')->name ?>

                      <div class="img-wrap">
                          <?php the_sub_field('image'); ?>
                      </div>
                    </a>
                  </div>
                <?php
                endwhile;
            endif; ?>
        </div>
      </nav>
    </div>


  </section>

<?php endif; ?>
