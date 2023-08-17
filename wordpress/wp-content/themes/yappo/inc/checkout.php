<?php
add_filter('woocommerce_billing_fields', function ($fields) {
    $chosen_methods_pickup = WC()->session->get('chosen_shipping_methods');
    $chosen_shipping_pickup = $chosen_methods_pickup[0];

    unset($fields['billing_last_name']);
    unset($fields['billing_company']);
//	unset( $fields['billing_country'] );
    unset($fields['billing_postcode']);
//	unset( $fields['billing_city'] );
    unset($fields['billing_state']);
    unset($fields['billing_email']);

    $fields['billing_first_name']['class'][] = 'col-md-6';
    $fields['billing_phone']['class'][] = 'col-md-6';
//	$fields['billing_address_1']['placeholder'] = __( 'Вулиця', 'yappo' );
//	$fields['billing_address_2']['placeholder'] = __( 'Під\'їзд', 'yappo' );
    $fields['billing_address_2']['class'][] = 'col-md-6';
    $fields['billing_phone']['priority'] = 15;

    foreach ($fields as &$field) {
        $field['placeholder'] = $field['label'];
        unset($field['label']);
    }

    unset($fields['billing_address_1']);
    unset($fields['billing_address_2']);

//	$fields['billing_address_3'] = array(
//		'placeholder'  => __( 'Квартира', 'yappo' ),
//		'class'        => array( 'col-md-6' ),
//		'autocomplete' => 'address-line3',
//		'priority'     => 65,
//	);

//	if ( ( isset( $_POST['shipping_method'] ) && strpos( $_POST['shipping_method'][0], 'local_pickup:2' ) !== false ) ||
//	     strpos( $chosen_shipping_pickup, 'local_pickup:2' ) !== false ) {
//		unset( $fields['billing_address_1'] );
//		unset( $fields['billing_address_2'] );
//		unset( $fields['billing_address_3'] );
//	}

    return $fields;
});

add_filter('woocommerce_checkout_show_terms', '__return_false');

add_action('woocommerce_checkout_order_processed', 'yappo_add_products', 10, 3);

/**
 * @param int $order_id
 * @param $posted_data
 * @param WC_Order $order
 *
 * @return void
 */
function yappo_add_products($order_id, $posted_data, $order)
{
    $chopstics = wc_get_product(375);

    $count_of_chopstics = absint($_POST["count_of_chopstics"]);
    $number_of_people = $_POST['number_of_people'];

    if ($count_of_chopstics) {
        $order->add_product($chopstics, $count_of_chopstics);
    }
    if ($number_of_people) {
        $order->add_meta_data('number_of_people', $number_of_people, true);
    }

    $order = wc_get_order($order_id);
    //імбир 373
    //васабі 374
    $products_id = array(
        apply_filters('wpml_object_id', 373, 'product'),
        apply_filters('wpml_object_id', 374, 'product')
    );

    $quantity = $number_of_people;

    foreach ($products_id as $product_id) {
        $order->add_product(wc_get_product($product_id), 1);
    }

    if ($order->get_fees()) {
        $fees = $order->get_items('fee');
        foreach ($fees as $key => $fee) {
            if ($fee['name'] == __('Пакування', 'yappo')) {
                $order->remove_item($key);
                $order->add_product(wc_get_product(972), 1);
            }
//			if ( $fee['name'] == __( 'Вартість доставки', 'yappo' ) ) {
//				$order->remove_item( $key );
//				$order->add_product( wc_get_product( 973 ), 1 );
//			}
        }
    }

    $order->calculate_totals();
    $order->save();
}

add_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

add_filter('woocommerce_cart_item_removed_notice_type', '__return_null');

add_filter('woocommerce_init', 'change_default_checkout_city');

function get_choosed_city_data()
{
    return get_terms(array(
            'taxonomy' => (get_locale() == 'uk') ? 'cities' : 'cities_ru',
            'hide_empty' => false,
            'slug' => $_COOKIE['choosedcity'],
        ))[0] ?? false;
}

function change_default_checkout_city()
{
    if (is_admin() || is_ajax()) {
        return;
    }

    if (!WC()->customer) {
        return;
    }

    $city = $_COOKIE['choosedcity'] ?: false;

    if (!$city) {
        return;
    }

    $cityname = get_field('city', get_term_by('slug', $city, 'cities'));

    WC()->customer->set_billing_city($cityname);

//	WC()->customer->set_billing_address( get_field( 'adress', get_term_by( 'slug', $city, 'cities' ) ) );

    WC()->customer->save_data();

    return $city;
}

add_filter('woocommerce_init', 'change_default_checkout_state');

function change_default_checkout_state()
{
    if (is_admin() || is_ajax()) {
        return;
    }
    if (!WC()->customer) {
        return;
    }

    $arr = get_choosed_city_data();

    if (!$arr) {
        return;
    }

    $region = get_field('region', $arr);

    WC()->customer->set_billing_state($region);

    WC()->customer->save_data();

    return $region;
}

function yappo_get_chosen_city()
{
    $city = $_COOKIE['choosedcity'];

    if (!$city) {
        return;
    }

    $cityname = get_field('city', get_term_by('slug', $city, 'cities'));

    if ($cityname) {
        return $cityname;
    }

    return '';
}

function yappo_get_chosen_city_slug()
{
    $city = $_COOKIE['choosedcity'];

    if (!$city) {
        return;
    }

    return $city;
}

function yappo_get_chosen_region()
{
    if (WC()->customer->get_billing_state()) {
        return WC()->customer->get_billing_state();
    }

    return '';
}

function yappo_get_chosen_adress()
{
    $address = $_COOKIE['choosedaddress'];
    if (!$address) {
        return;
    }
    $addressArr = explode("/", $address);
    $addressId= get_field('adresy', get_term_by('id', $addressArr[0], 'cities'));
    return $addressId[$addressArr[1]]['item']['name'];
}

function yappo_get_chosen_header_adress()
{
    if (yappo_get_chosen_region() && yappo_get_chosen_city()) {
        return '<span>' . (get_locale() == 'uk' ? yappo_get_chosen_city() : get_ru_city_name()) . ',</span>' . yappo_get_chosen_adress();
    }
    $text = __('Оберіть Місто', 'yappo');

    return "<span>$text</span>";
}

function yappo_get_chosen_billing_adress()
{
    if (yappo_get_chosen_region() && yappo_get_chosen_city()) {
        ?>
      <p class="choose-city">
          <?php esc_html_e('Ваше місто', 'yappo'); ?>
      </p>

      <div class="select-dropdown">
        <div role="button" class="select-dropdown__button">
							<span
                  class="city"><?= get_locale() == 'uk' ? checkout_get_billing_city() : get_ru_city_name() ?><?php if (function_exists('yappo_get_chosen_adress')) echo "(" . yappo_get_chosen_adress() . ")" ?></span>
          <span class="region">
                  <?= WC()->countries->get_states()['UA'][WC()->customer->get_billing_state()]; ?>
              </span>
        </div>
      </div>
    <?php } else {
        $text = __('Оберіть Місто', 'yappo');

        return "<span>$text</span>";
    }
}

function yappo_remove_shipping_on_hours($rates, $package)
{

    $hour_start_shipping = get_field('hour_start_shipping', 'option');
    $hour_end_shipping = get_field('hour_end_shipping', 'option');

    $time = (new DateTime('now', new DateTimeZone('Europe/Kiev')))->format('G.i');

    if (!($hour_start_shipping <= $time && $time <= $hour_end_shipping)) {
        unset($rates['flat_rate:3']);
    }

//	if ( ( (float) WC()->cart->get_cart_contents_total() > (float) get_field( 'sum_of_free_shipping', 'option' ) ) && isset( $rates['flat_rate:3'] ) ) {
//		$rates['flat_rate:3']->cost = 0;
//	}

    return $rates;
}

add_filter('woocommerce_package_rates', 'yappo_remove_shipping_on_hours', 10, 2);

/**
 * Склонение существительных после числительных.
 *
 * @param float $points
 * @param bool $show Включает значение $value в результирующею строку
 *
 * @return string
 * @version 2.10.1
 */
function pointsLabel(float $points): string
{

    $label_point = __('товар', 'yappo');
    $label_point_two = __('товари', 'yappo');
    $label_points = __('товарів', 'yappo');

    $words = array($label_point, $label_point_two, $label_points);

    $num = $points % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    switch ($num) {
        case 1:
            $out = $words[0];
            break;
        case 2:
        case 3:
        case 4:
            $out = $words[1];
            break;
        default:
            $out = $words[2];
            break;
    }

    return $out;
}

function wc_custom_thank_you_page($order_id)
{
    wp_redirect(home_url('?wc_order_id=' . $order_id));
    exit;
}

add_action('woocommerce_thankyou', 'wc_custom_thank_you_page');

//add_filter( 'woocommerce_available_payment_gateways', 'filter_gateways', 1 );
//
//function filter_gateways( $gateways ) {
//	echo '<pre>';
//	var_dump( $gateways );
//echo '</pre>';
//	return $gateways;
//}


add_action('woocommerce_after_checkout_validation', 'bogika_validate_phone_number_length', 10, 2);

function bogika_validate_phone_number_length($fields, $errors)
{

    $fee_label = '';
    if (get_locale() == 'uk') {
        $fee_label = esc_html__('Мінімальна довжина мобільного номеру - 10 символів.', 'yappo');
    } elseif (get_locale() == 'ru_RU') {
        $fee_label = esc_html__('Минимальная длина мобильного номера - 10 символов.', 'yappo');
    }

    if (mb_strlen($fields['billing_phone']) < 18) {
        $errors->add('validation', sprintf(__('%s', 'yappo'), $fee_label));
    }
}

///**
// * Add a 1% surcharge to your cart / checkout
// * change the $percentage to set the surcharge to a value to suit
// */
add_action('woocommerce_cart_calculate_fees', 'woocommerce_custom_surcharge');
function woocommerce_custom_surcharge()
{
    global $woocommerce;

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $discount = 5;
    // $woocommerce->cart->add_fee( __( 'Пакування', 'yappo' ), $discount, true, '' );

    $fee_label = '';
    if (get_locale() == 'uk') {
        $fee_label = esc_html__('Пакування', 'yappo');
    } elseif (get_locale() == 'ru_RU') {
        $fee_label = esc_html__('Упаковка', 'yappo');
    }

    $woocommerce->cart->add_fee($fee_label, $discount, true, '');

//	$chosen_methods_pickup = WC()->session->get( 'chosen_shipping_methods' );
//	if (isset($chosen_methods_pickup) && $chosen_methods_pickup[0] === 'flat_rate:3') {
//		if ( (float) WC()->cart->get_cart_contents_total() < (float) get_field( 'sum_of_free_shipping', 'option' ) ) {
//			$woocommerce->cart->add_fee( __( 'Вартість доставки', 'yappo' ), 50, true, '' );
//		}
//	}
}

function checkout_get_billing_city()
{
    return WC()->customer->get_billing_city();
}

function get_ru_city_name()
{
    $city = checkout_get_billing_city();
    $terms = get_terms(
        ['taxonomy' => 'cities',
            'hide_empty' => false]
    );
    foreach ($terms as $term)
        if (get_field('city', $term) == $city) $slug = $term->slug;
    $terms = get_terms(
        ['taxonomy' => 'cities_ru',
            'hide_empty' => false]
    );
    foreach ($terms as $term)
        if ($term->slug == $slug) $name = get_field('city', $term);
    return $name;
}
