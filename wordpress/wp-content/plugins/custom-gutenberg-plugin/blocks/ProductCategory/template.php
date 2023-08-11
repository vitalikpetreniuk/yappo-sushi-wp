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
        'yappo_filter' => 1,
    );

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

        <?php get_template_part('template-parts/content', 'filter', array(
            'min' => $min,
            'max' => $max,
        )) ?>

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
        } else {
            ?>
            <?php esc_html_e('Постів не знайдено', 'yappo'); ?>
            <?php
        }
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
    </section>
    <?php
    wp_reset_query();
endif; ?>
