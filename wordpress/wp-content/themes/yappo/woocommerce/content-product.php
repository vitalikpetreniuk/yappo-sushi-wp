<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<div <?php wc_product_class('col-xl-4 col-lg-6 col-md-6', $product); ?>>
    <div class="product__item">
        <?php
        if (!($product->is_purchasable() && $product->is_in_stock())) { ?>
            <div class="no-product-wrap">
                <div class="no-product">
                    <?php esc_html_e('На жаль, товар зараз недоступний', 'yappo'); ?>
                </div>
            </div>
        <?php } ?>
        <?php if (function_exists('yappo_product_badges')) {
            yappo_product_badges();
        } ?>

        <script>
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                'event': 'view_item_list_ads',
                'value': <?= $product->get_price() ?>,
                'items': [
                    {
                        'id': <?= $product->get_id() ?>,
                        'google_business_vertical': 'retail'
                    },
                ]
            });
        </script>

        <div class="product__image class">
            <a href="<?php the_permalink(); ?>">
                <?php
                $image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
                if ($image_attributes) : ?>
                    <img src="<?php echo $image_attributes[0]; ?>" alt="<?php the_title(); ?>"
                         width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>"/>
                <?php endif; ?>
            </a>
        </div>

        <div class="product__detail">

            <a href="<?php the_permalink(); ?>">
                <h3 title="<?php the_title(); ?>"><?php the_title(); ?></h3>
            </a>

            <div class="cart__detail" title="<?= $product->get_short_description() ?>">
                <?= wpautop($product->get_short_description()); ?>
            </div>
        </div>

        <div class="product__cart">

            <?php do_action('woocommerce_after_shop_loop_item_title'); ?>

            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>
</div>
