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
    <?php
    $category = get_field('category') ?? get_field('category', $post_id);
    $args = array(
        'tax_query' => array(
            'relation' => 'AND',
            [
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => [$category]
            ],
        ),
        'posts_per_page' => PHP_INT_MAX,
        'fields' => 'ids',
    );

    switch ($_GET['orderby']) {
        case 'popularity':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'total_sales';
            break;
        case 'low_to_high':
            $meta_key = '_price';
            $order = 'asc';
            $orderby = 'meta_value_num';
            break;
        case 'high_to_low':
            $meta_key = '_price';
            $order = 'desc';
            $orderby = 'meta_value_num';
            break;
        case 'newness':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'date';
            break;
        case 'rating':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'rating';
            break;
        default:
            $meta_key = '';
            $order = 'asc';
            $orderby = 'menu_order title';
            break;
    }

    $args['order'] = $order;
    $args['orderby'] = $orderby;
    $args['meta_key'] = $meta_key;

    if (isset($_GET['pa_ingredients'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'pa_ingredients',
            'field' => 'slug',
            'terms' => $_GET['pa_ingredients'],
        ];
    }

    if (isset($_GET['product_tag'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $_GET['product_tag'],
        ];
    }

    if (isset($_GET['min_price'], $_GET['max_price'])) {
        $args['meta_query'] = array(
            'relation' => 'AND',
            'min_price' => [
                'key' => '_price',
                'compare' => '>=',
                'type' => 'numeric',
                'value' => $_GET['min_price']
            ],
            'max_price' => [
                'key' => '_price',
                'compare' => '<=',
                'type' => 'numeric',
                'value' => $_GET['max_price']
            ]
        );
    }

    $minmax = product_category_min_max($category);
    $min = $minmax['min'];
    $max = $minmax['max'];
    wp_reset_postdata();
    ?>
    <section class="category-page">

        <div class="container">
            <div class="row align-items-center mb-md-5 mb-4">

                <div class="col-3 pe-0">
                    <?php if (function_exists('yoast_breadcrumb')) {
                        if (get_locale() == 'ru_RU') echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>', false));
                        else echo yoast_breadcrumb('<ul class="breadcrumbs">', '</ul>');
                    } ?>

                </div>
                <?php
                $rolls_id = apply_filters('wpml_object_id', 72, 'product_tag');
                $title_class = '';
                if (get_field('category', $post_id)->term_taxonomy_id == $rolls_id || get_field('category', $post_id)->parent == $rolls_id) {
                    $title_class = 'title-bg-two';
                }
                ?>
                <div class="col-6">
                    <h2 class="section__title ">
                        <?php the_title(); ?>
                    </h2>
                </div>

            </div>

        </div>
        <div class="container-fluid">
            <div class="filter-wrap filter-wrap-active">


                <div class="filter-btn-wrap">
                    <button class="filter-btn-open">
                        <img src="<?php bloginfo('template_url'); ?>/assets/img/filter.svg" alt="filter">

                        <?php esc_html_e('ФІЛЬТР', 'yappo'); ?>
                    </button>

                    <hr class="line">
                </div>

                <div class="filter-options filter-options-active">

                    <button class="close-filter">
                        <svg class="hover-effect-svg" width="22" height="22" viewBox="0 0 22 22" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2L20.5 20.5" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
                                  stroke-linecap="round"></path>
                            <path d="M2 20.5005L20.5 2.00051" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
                                  stroke-linecap="round"></path>
                        </svg>
                    </button>

                    <form action="<?php the_permalink(); ?>" method="GET" class="filter__slider-form">
                        <div class="row">

                            <div class="col-xl-3 col-lg-4  col-md-5 col-12">
                                <div class="column-one">

                                    <h5 class="filter__title">
                                        <?php esc_html_e('Ціна', 'yappo') ?>
                                    </h5>


                                    <div class="filter__range-slider">

                                        <div class="pc-range-slider">

                                            <div class="pc-range-slider__wrapper">

                                                <div class="pc-range-slider__control ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">

                                                    <div class="ui-slider-range ui-widget-header ui-corner-all"
                                                         style="left: 0%; width: 50.5443%;">
                                                    </div>

                                                    <span class="ui-slider-handle ui-state-default ui-corner-all"
                                                          tabindex="0" style="left: 0%;">
                                                        </span>

                                                    <span class="ui-slider-handle ui-state-default ui-corner-all"
                                                          tabindex="0" style="left: 50.5443%;">
                                                        </span>

                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="filter__slider-control-group">

                                        <div class="filter__slider-control-column">

                                            <input class="filter__slider-control inp-regulation" type="number"
                                                   name="min_price" value="<?= $_GET['min_price'] ?: $min ?>">
                                        </div>

                                        <div class="filter__slider-control-column">

                                            <input class="filter__slider-control inp-regulation" type="number"
                                                   name="max_price" value="<?= $_GET['max_price'] ?: $max ?>">
                                        </div>

                                    </div>


                                    <div class="radio">

                                        <label class="radio-button-container">
                                            <?php esc_html_e('Спочатку дешевше', 'yappo'); ?>
                                            <input type="radio"
                                                   name="orderby" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'low_to_high') echo 'checked'; ?>
                                                   id="low_to_high"
                                                   value="low_to_high">
                                            <span class="checkmark"></span>
                                        </label>

                                        <label class="radio-button-container">
                                            <?php esc_html_e('Спочатку дорожче', 'yappo'); ?>
                                            <input type="radio"
                                                   name="orderby" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'high_to_low') echo 'checked'; ?>
                                                   id="high_to_low"
                                                   value="high_to_low">
                                            <span class="checkmark"></span>
                                        </label>

                                    </div>


                                    <button class="btn-blue d-md-block d-none">
                                        <?php esc_html_e('ПОШУК', 'yappo'); ?>
                                    </button>


                                </div>
                            </div>


                            <div class="col-xl-9 col-lg-8  col-md-7 col-12">
                                <div class="col-two">

                                    <?php
                                    $terms = get_terms(array(
                                        'taxonomy' => 'pa_ingredients'
                                    ));
                                    if (count($terms)) : ?>
                                        <div class="filter__item filter__item--type-checkbox">

                                            <h5 class="filter__title">
                                                <?php esc_html_e('Інгредієнти', 'yappo'); ?>
                                            </h5>

                                            <div class="filter__inner  ">

                                                <div class="filter__properties-list">
                                                    <?php
                                                    foreach ($terms as $term) :
                                                        $checked = false;
                                                        if (in_array($term->slug, $_GET['pa_ingredients'] ?: [])) $checked = true;
                                                        ?>
                                                        <div class="filter__properties-item ">
                                                            <div class="filter__checkgroup">
                                                                <div class="filter__checkgroup-body">
                                                                    <div class="filter__checkgroup-link ">

                                                                        <label class="filter__checkgroup-title radio-button-container <?php if ($checked) echo 'label-active'; ?>"
                                                                               for="<?= $term->slug ?>">
                                                                            <input type="checkbox"
                                                                                   name="pa_ingredients[]"
                                                                                <?php if ($checked) echo 'checked' ?>
                                                                                   id="<?= $term->slug ?>"
                                                                                   value="<?= $term->slug ?>">
                                                                            <!-- <span class="checkmark"></span>     -->

                                                                            <?= $term->name ?>

                                                                            <div class="img-wrap">
                                                                                <?= wp_get_attachment_image(get_field('image', $term)) ?>
                                                                            </div>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    $terms = get_terms(array(
                                        'taxonomy' => 'product_tag'
                                    ));
                                    if (count($terms)) :
                                        ?>
                                        <div class="filter__item filter__item--type-checkbox category-offers">


                                            <h5 class="filter__title">
                                                <?php esc_html_e('Категорії пропозицій', 'yappo'); ?>
                                            </h5>

                                            <div class="filter__inner  category-wrap-filter">

                                                <div class="filter__properties-list">

                                                    <?php foreach ($terms as $term) :
                                                        $checked = false;
                                                        $checked = in_array($term->slug, $_GET['product_tag'] ?: []);
                                                        ?>
                                                        <div class="filter__properties-item ">
                                                            <div class="filter__checkgroup"
                                                                 data-filter-control-checkgroup="">
                                                                <div class="filter__checkgroup-body">
                                                                    <div class="filter__checkgroup-link ">

                                                                        <label class="filter__checkgroup-title radio-button-container <?php if ($checked) echo 'label-active' ?>"
                                                                               for="<?= $term->slug ?>">
                                                                            <input type="checkbox"
                                                                                <?php if ($checked) echo 'checked' ?>
                                                                                   name="product_tag[]"
                                                                                   value="<?= $term->slug ?>"
                                                                                   id="<?= $term->slug ?>">
                                                                            <!-- <span class="checkmark"></span>     -->

                                                                            <?= $term->name ?>

                                                                            <div class="img-wrap">
                                                                                <?= wp_get_attachment_image(get_field('image', $term), 'full') ?>
                                                                            </div>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <button class="btn-blue d-md-none d-block">
                                        <?php esc_html_e('ПОШУК', 'yappo'); ?>
                                    </button>


                                </div>
                            </div>


                        </div>
                    </form>

                    <hr class="line mb-md-3 mb-0">


                </div>

            </div>
        </div>

        <?php if (!empty($_GET)) : ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="cheked-wrap">
                            <?php if (isset($_GET['orderby'])) : ?>
                                <div class="chaked-box" data-sortby="<?= $_GET['orderby'] ?>">
                                    <?php switch ($_GET['orderby']) {
                                        case 'low_to_high' :
                                            echo __('Спочатку дешевше', 'yappo');
                                            break;
                                        case 'high_to_low':
                                            echo __('Спочатку дорожче', 'yappo');
                                            break;
                                    } ?>


                                    <div class="close-btn-wrap">

                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($_GET['product_tag'] as $tag) :
                                $term = get_term_by('slug', $tag, 'product_tag');
                                if (!$term) return;
                                switch ($term->term_id) {
                                    case $term->term_id === apply_filters('wpml_object_id', 61, 'product_tag') :
                                        $class = 'chaked-box-discount';
                                        break;
                                    case $term->term_id === apply_filters('wpml_object_id', 63, 'product_tag') :
                                        $class = 'chaked-box-vegaterian';
                                        break;
                                    default:
                                        $class = '';
                                }
                                ?>
                                <div class="chaked-box <?= $class ?>" data-slug="<?= $term->slug ?>"
                                     data-tax="product_tag">
                                    <?= $term->name ?>

                                    <div class="close-btn-wrap">

                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php foreach ($_GET['pa_ingredients'] as $tag) :
                                $term = get_term_by('slug', $tag, 'pa_ingredients');
                                if (!$term) return;
                                ?>
                                <div class="chaked-box" data-slug="<?= $term->slug ?>"
                                     data-tax="pa_ingredients">
                                    <?= $term->name ?>

                                    <div class="close-btn-wrap">

                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="container-fluid">
            <?php
            $loop = new WP_Query($args);
            woocommerce_product_loop_start();
            ?>
            <?php if (isset($loop) && $loop->have_posts()) {
                $i = 0;
                while ($loop->have_posts()) {
                    $loop->the_post();
                    $i++;
                    $product = wc_get_product(get_the_ID());
                    $seo[] = load_template_part('template-parts/seo/product', 'item', [
                        'i' => $i,
                        'quantity' => 1,
                        'id' => get_the_ID(),
                        'price' => $product->get_price(),
                        'list_name' => 'Product category'
                    ]);
                    ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                    <?php
                }
            }
            //				var_dump($loop->request);
            ?>
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
            <?php
            woocommerce_product_loop_end();
            ?>
        </div>
    </section>
    <?php
    wp_reset_query();
endif; ?>
