<?php
/**
 *
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if ( @$block['data']['preview_image_help'] ) : ?>
    <img src="<?= plugin_dir_url(__FILE__) ?>/screenshot.png" alt="">
    <?php
else:
    /* Block Name: Business lanch banner */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>

  <div class="container-fluid">
    <div class="row">
        <?php global $wp_query;
        $query = new WP_Query( array(
            'post__in'       => [ $wp_query->posts[0]->ID ],
            'post_type'      => 'product',
            'posts_per_page' => 1,
        ) );
        ?>
        <?php if ( $query->have_posts() ) :
            while ( $query->have_posts() ) :
                $query->the_post();

                wc_get_template_part( 'content', 'product' );
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
                  if(get_locale() == 'uk') _e( 'Зверни увагу! <br> Бізнес-ланчі доступні по буднях з 11:00 <br> по 16:00', 'yappo' );
                  if(get_locale() == 'ru_RU') _e( 'Обрати внимание! <br> Бизнес-ланчи доступны по будням с 11:00 <br>по 16:00', 'yappo' );
                  ?>
              </h3>
            </div>

            <div class="col-lg-5 col-md-4">
              <div class="img-wrap">
                <img src="<?= get_theme_file_uri( 'assets/img/card-info.png' ) ?>" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
