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
    /* Block Name: News blogs */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>
<section class="news-blogs">
            <div class="container">
                <div class="row align-items-start mb-md-5 mb-3">
                    <div class="col-3 pe-0  d-none d-lg-block">
                        <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
                            if(get_locale() == 'ru_RU') echo str_replace('Головна','Главная',yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>', false));
                            else echo yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>');
                        } ?>
                    </div>
                    <div class="col-12 col-md-6 m-auto m-lg-0">
                        <h2 class="section__title">
                            <?php the_title(); ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="grid-container">
                    <?php
$args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'order' => 'DESC',
    'orderby' => 'id'
];
$i = 0;
$query = new WP_Query($args);
if($query->have_posts())
    while($query->have_posts()) {
        $query->the_post();
        global $post;
        $i++;
        if($i == 10) { ?>
        </div>
        <div class="grid-container">
        <?php $i = 1; } ?>

        <a href="<?php the_permalink(); ?>" class="item item<?=$i?>">
                        <div class="info-wrap">
                            <div class="data-span">
                                <?=date('d/m/Y', strtotime($post->post_date))?>
                            </div>
                            <h4>
                                <?php the_title(); ?>
                            </h4>
                        </div>
                        <div class="img-wrap">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                    </a>
    <?php  } ?>
                </div>
            </div>
        </section>
<?php endif; ?>
