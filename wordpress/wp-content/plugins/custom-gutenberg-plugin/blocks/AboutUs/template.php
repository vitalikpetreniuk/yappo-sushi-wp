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
    /* Block Name: About us */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>
<section class="about-us">
            <div class="container">
                <div class="row align-items-start mb-md-5 mb-4">
                    <div class="col-3 pe-0">
                        <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
                            if(get_locale() == 'ru_RU') echo str_replace('Головна','Главная',yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>', false));
                            else echo yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>');
                        } ?>
                    </div>
                    <div class="col-6">
                        <h2 class="section__title">
                            <?php the_title(); ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="container">
                <?=str_replace('<p>','<p class="mb-4">',$text_1)?>
                <?=$text_2?>
                <div class="img-wrap">
                    <?php if (!empty($image)) { ?>
                        <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>">
                    <?php } ?>
                </div>
            </div>
        </section>
<?php endif; ?>
