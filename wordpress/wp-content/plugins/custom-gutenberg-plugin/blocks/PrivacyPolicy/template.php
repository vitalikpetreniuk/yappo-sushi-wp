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
    /* Block Name: Privacy policy */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>
<section class="privacy-policy">
            <div class="container">
                <div class="row align-items-cenetr mb-md-5 mb-4">
                    <div class="col-3 pe-0 d-none d-lg-block">
                        <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
                            if(get_locale() == 'ru_RU') echo str_replace('Головна','Главная',yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>', false));
                            else echo yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>');
                        } ?>
                    </div>
                    <div class="col-12 col-md-6 m-auto m-lg-0">
                        <h1 class="section__title">
                            <?php echo str_replace(' ','<br>', get_the_title()); ?>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="content">
                    <?=get_field('content')?>
                </div>
            </div>
        </section>
<?php endif; ?>
