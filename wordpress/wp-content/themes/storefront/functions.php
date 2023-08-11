<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme = wp_get_theme('storefront');
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 980; /* pixels */
}

$storefront = (object)array(
    'version' => $storefront_version,

    /**
     * Initialize all the things.
     */
    'main' => require 'inc/class-storefront.php',
    'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if (class_exists('Jetpack')) {
    $storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if (storefront_is_woocommerce_activated()) {
    $storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';
    $storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

    require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

    require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
    require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
    require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if (is_admin()) {
    $storefront->admin = require 'inc/admin/class-storefront-admin.php';

    require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if (version_compare(get_bloginfo('version'), '4.7.3', '>=') && (is_admin() || is_customize_preview())) {
    require 'inc/nux/class-storefront-nux-admin.php';
    require 'inc/nux/class-storefront-nux-guided-tour.php';
    require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


/**
 * Load next 12 products using AJAX
 */
function ajax_next_posts()
{
    global $product;
    // Build Query
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => (int)$_POST['posts_per_page'],
        'orderby' => 'title',
        'order' => 'ASC',
        'offset' => (int)$_POST['post_offset'],
    );

    if (!empty($_POST['product_cat'])) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $_POST['product_cat'],
                'operator' => 'IN'
            ),
        );
    }

    $count_results = '0';

    $ajax_query = new WP_Query($args);

    // Results found
    if ($ajax_query->have_posts()) {

        // Start "saving" results' HTML
        $results_html = '';
        ob_start();

        while ($ajax_query->have_posts()) {

            $ajax_query->the_post();
            echo wc_get_template_part('content', 'product');

        }
        wp_reset_postdata();

        // "Save" results' HTML as variable
        $results_html = ob_get_clean();

    } else {

        // Start "saving" results' HTML
        $results_html = '';
        ob_start();

        echo "none found!";

        // "Save" results' HTML as variable
        $results_html = ob_get_clean();

    }

    // Build ajax response
    $response = array();

    // 1. value is HTML of new posts and 2. is total count of posts
    array_push($response, $results_html);
    echo json_encode($response);

    // Always use die() in the end of ajax functions
    die();
}

add_action('wp_ajax_ajax_next_posts', 'ajax_next_posts');
add_action('wp_ajax_nopriv_ajax_next_posts', 'ajax_next_posts');

function get_ecommerce_excerpt()
{
    $excerpt = get_the_excerpt();
    $excerpt = preg_replace(" ([.*?])", '', $excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, 100);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace('/s+/', ' ', $excerpt));
    if (strlen($excerpt) >= 1) {
        return '<ul> <p>Склад: ' . get_the_excerpt() . '</p></ul>';
    }
}

add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('footer-bottom', 'Меню в футері');
}

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script('swiper-scripts', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), time());
    wp_enqueue_script('n_scripts', get_template_directory_uri() . '/assets/js/n_scripts.js', array(), time());
    wp_enqueue_script('v_scripts', get_template_directory_uri() . '/assets/js/v_scripts.js', array(), time());
    wp_enqueue_script('m_scripts', get_template_directory_uri() . '/assets/js/m_scripts.js', array(), time());

    wp_enqueue_style('swiper-styles', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), time());
    wp_enqueue_style('n_styles', get_template_directory_uri() . '/assets/css/n_styles.css', array(), time());
    wp_enqueue_style('v_styles', get_template_directory_uri() . '/assets/css/v_styles.css', array(), time());
    wp_enqueue_style('m_styles', get_template_directory_uri() . '/assets/css/m_styles.css', array(), time());
});


add_filter('woocommerce_checkout_fields', 'remove_fields', 9999);

function remove_fields($checkout_fields)
{

    // she wanted me to leave these fields in checkout
    unset($checkout_fields['billing']['billing_email']);
    unset($checkout_fields['order']['order_comments']); // remove order notes

    // and to remove the billing fields below
    unset($checkout_fields['billing']['billing_company']); // remove company field
    unset($checkout_fields['billing']['billing_country']);
    unset($checkout_fields['billing']['billing_address_1']);
    unset($checkout_fields['billing']['billing_address_2']);
// 	unset( $checkout_fields[ 'billing' ][ 'billing_city' ] );
    unset($checkout_fields['billing']['billing_state']); // remove state field
    unset($checkout_fields['billing']['billing_postcode']); // remove zip code field

    unset($checkout_fields['shipping']['shipping_country']); // remove company field
    unset($checkout_fields['shipping']['shipping_address_1']);
    unset($checkout_fields['shipping']['shipping_address_2']);
    unset($checkout_fields['shipping']['shipping_city']);
    unset($checkout_fields['shipping']['shipping_state']);

    return $checkout_fields;
}

add_filter('woocommerce_shipping_calculator_enable_country', '__return_false');
add_filter('woocommerce_shipping_calculator_enable_city', '__return_false');
add_filter('woocommerce_shipping_calculator_enable_state', '__return_false');

add_action('woocommerce_before_checkout_form', 'print_webcache_notice', 10);
function print_webcache_notice()
{
    wc_print_notice(__("Інформацію про можливість здійснення безкоштовної доставки або доставки за межі міста можна отримати у оператора call-центру за телефоном, або у адміністратора при відвідуванні закладу у вашому місті", "woocommerce"), 'error');
}

function QuadLayers_change_order_status($order_id)
{
    if (!$order_id) {
        return;
    }
    $order = wc_get_order($order_id);
    if ('processing' == $order->get_status()) {
        $order->update_status('wc-on-hold');
    }
}

add_action('woocommerce_thankyou', 'QuadLayers_change_order_status');


function get_city_options()
{
    $options = array(
        // '' => __( 'Select unit type' ),    /*= this will make empty field*/
        'Буча' => __('Буча', 'Буча'),
        'Вишгород' => __('Вишгород', 'Вишгород'),
        'Черкаси' => __('Черкаси', 'Черкаси'),
        'Васильків' => __('Васильків', 'Васильків'),
        'Бровари' => __('Бровари', 'Бровари'),
        'с.Чайки' => __('с.Чайки', 'с.Чайки'),
    );
    ksort($options);

    return $options;
}

// Checkout and my account (edit) billing and shipping city
add_filter('woocommerce_default_address_fields', 'custom_override_default_city_fields');
function custom_override_default_city_fields($fields)
{
    $fields['city']['type'] = 'select';
    $fields['city']['class'] = array('my-field-class form-row-last');
    $fields['city']['input_class'] = array('state_select');
    $fields['city']['options'] = get_city_options();
    $fields['city']['priority'] = '41';

    return $fields;
}

// Admin editable single orders billing and shipping city field
add_filter('woocommerce_admin_billing_fields', 'admin_order_pages_city_fields');
add_filter('woocommerce_admin_shipping_fields', 'admin_order_pages_city_fields');
function admin_order_pages_city_fields($fields)
{
    $fields['city']['type'] = 'select';
    $fields['city']['options'] = get_city_options();
    $fields['city']['class'] = 'short'; // Or 'js_field-country select short' to enable selectWoo (select2).

    return $fields;
}

add_action('woocommerce_before_order_itemmeta', 'storage_location_of_order_items', 10, 3);
function storage_location_of_order_items($item_id, $item, $product)
{
    // Only on backend order edit pages
    if (!(is_admin() && $item->is_type('line_item'))) {
        return;
    }

    // Get your ACF product value (replace the slug by yours below)
    if ($acf_value = get_field('zaklad', $product->get_id())) {
        $acf_label = __('Stored in: ');

        // Outputing the value of the "location storage" for this product item
        echo '<div class="wc-order-item-custom"><strong>' . $acf_value . $acf_label . '</strong></div>';
    }
}

function prefix_filter_description_example($description)
{
    if (is_product_category('other')) {
        return 'Замовити (купити) суші-сети (набір) з доставкою в Броварах | Yappo';
    } else if (is_product_category('cold_rolls')) {
        return 'Замовити (купити) суші-роли з доставкою в Броварах | Yappo';
    } else if (is_product_category()) {
        $title = get_queried_object()->name;

        return 'Замовити (купити) ' . $title . ' з доставкою в Броварах | Yappo';
    }

    return $description;
}

add_filter('wpseo_title', 'prefix_filter_description_example');
/**
 * Add the custom fields or the UOM to the prodcut general tab.
 *
 * @since 3.0.0
 */
function wc_uom_product_fields()
{
    echo '<div>';
    woocommerce_wp_text_input(
        array(
            'id' => 'measure_unit',
            'label' => __('Одиниця виміру'),
            'placeholder' => '',
            'desc_tip' => 'true',
            'value' => get_post_meta(get_the_ID(), 'measure_unit', true) ?? 'г',
            'description' => __('Enter your unit of measure for this product here.'),
        )
    );
    echo '</div>';
}

function wc_uom_save_field_input($post_id)
{
    if (isset($_POST['measure_unit'])) :
        $woo_uom_input = sanitize_text_field(wp_unslash($_POST['measure_unit']));
        update_post_meta($post_id, 'measure_unit', esc_attr($woo_uom_input));
    endif;
}

add_action('woocommerce_product_options_shipping_product_data', 'wc_uom_product_fields');
add_action('woocommerce_process_product_meta', 'wc_uom_save_field_input');

function get_product_category_min_max()
{
    // Get the current product category term object
    $term = get_queried_object();

    global $wpdb;

    # Get ALL related products prices related to a specific product category
    $results = $wpdb->get_col("
        SELECT pm.meta_value
        FROM {$wpdb->prefix}term_relationships as tr
        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->prefix}terms as t ON tr.term_taxonomy_id = t.term_id
        INNER JOIN {$wpdb->prefix}postmeta as pm ON tr.object_id = pm.post_id
        WHERE tt.taxonomy LIKE 'product_cat'
        AND t.term_id = {$term->term_id}
        AND pm.meta_key = '_price'
    ");

    // Sorting prices numerically
    sort($results, SORT_NUMERIC);

    $min = current($results);
    $max = end($results);

    return ['min' => $min, 'max' => $max];
}


function load_template_part($template_name, $part_name = null, $args = null)
{
    ob_start();
    get_template_part($template_name, $part_name, $args);
    $var = ob_get_contents();
    ob_end_clean();

    return $var;
}

// e952c2d4b8239f6e760ecbe012e341d5aa018adf

// add_filter('woocommerce_payment_successful_result', 'custom_disable_checkout_redirect');

// function custom_disable_checkout_redirect($result)
// {
//     $result['redirect'] = false;
//     return $result;
// }

require_once 'inc/functions-crm.php';
