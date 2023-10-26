<?php
/**
 * Plugin Name:   Timer custom wp
 * Plugin URI:    https://google.com
 * Description:   Customize WordPress with powerful, professional and intuitive fields.
 * Version:       1.1.0
 * Author:        Doleinik
 * Author URI:    https://google.com
 * Update URI:    https://google.com
 * Text Domain:   timer-custom-wp
 */

global $wpdb;
$table_name = $wpdb->prefix . 'timer_product';

$sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_product VARCHAR(255) NOT NULL
)";

$wpdb->query($sql);


function add_product_timer_fields()
{
    woocommerce_wp_checkbox(
        array(
            'id' => 'product_start_time',
            'label' => __('Start Time', 'woocommerce'),
            'desc_tip' => 'true',
//            'description' => __('Enter the start time for the timer in YYYY-MM-DD HH:MM:SS format.', 'woocommerce'),
            'wrapper_class' => 'show_if_simple',
        )
    );
}

add_action('woocommerce_product_options_general_product_data', 'add_product_timer_fields');

// Save custom fields
function save_product_timer_fields($product_id)
{

    $start_time = isset($_POST['product_start_time']) ? sanitize_text_field($_POST['product_start_time']) : '';
    global $wpdb;
    if($start_time === 'yes') {
        update_post_meta($product_id, 'product_start_time', $start_time);

        $wpdb->insert(
            $wpdb->prefix . 'timer_product',
            array(
                'id_product' => $product_id,
            )
        );
    } else {
        $wpdb->delete($wpdb->prefix . 'timer_product', array('id_product' => $product_id));
    }
}

add_action('woocommerce_process_product_meta', 'save_product_timer_fields');

function change_product_availability_to_available()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'timer_product';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    $products = [];
    if (!empty($results)) {
        foreach ($results as $row) {
            $products[] = [
                'id' => $row->id_product,
            ];
        }
    }

    foreach ($products as $product_id) {
        change_product_availability_by_id($product_id['id'], 'instock');
    }
}

add_action('change_product_availability_to_available_event', 'change_product_availability_to_available');

function change_product_availability_to_unavailable()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'timer_product';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    $products = [];
    if (!empty($results)) {
        foreach ($results as $row) {
            $products[] = [
                'id' => $row->id_product,
            ];
        }
    }
    foreach ($products as $product_id) {
        change_product_availability_by_id($product_id['id'], 'outofstock');
    }
}

add_action('change_product_availability_to_unavailable_event', 'change_product_availability_to_unavailable');

function schedule_product_availability_change()
{
    if (!wp_next_scheduled('change_product_availability_to_available_event')) {
        $start_time = strtotime('08:00:00');

        while (date('N', $start_time) >= 6) {
            $start_time += 86400; // Додаємо одну добу (86400 секунд) до часу початку події.
        }
        wp_schedule_event($start_time, 'daily', 'change_product_availability_to_available_event');
    }

    if (!wp_next_scheduled('change_product_availability_to_unavailable_event')) {
        wp_schedule_event(strtotime('13:00:00'), 'daily', 'change_product_availability_to_unavailable_event');
    }
}

add_action('wp', 'schedule_product_availability_change');