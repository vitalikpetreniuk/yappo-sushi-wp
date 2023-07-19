<?php

add_action('woocommerce_checkout_update_order_meta', 'send_order_to_pipedrive');

// Функція, яка викликається при створенні нового замовлення
function send_order_to_pipedrive($order_id) {
    $dirLog = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log';
    $fileErrorLog = $dirLog.'/functions_deal.log';

    // Отримання даних з WooCommerce
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    $phone = $_POST['billing_phone'];
    $first_name = $_POST['billing_first_name'];

    $person_count = $_POST['billing_person_count'];
    $shipping_method = $_POST['shipping_method'];
    $payment_method = $_POST['payment_method'];
    $billing_city = $_POST['billing_city'];
    if ($payment_method === 'cod') {
        $payment_method = "Оплата при отриманні";
    }

    if ($shipping_method[0] === "flat_rate:3") {
        $shipping_method = "Доставка кур'єром";
    }

    if ($shipping_method[0] === "local_pickup:2") {
        $shipping_method = "Самовивіз";
    }

    // Ваші дані доступу до API Pipedrive
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    // URL Pipedrive API
    $pipedrive_url = 'https://api.pipedrive.com/v1/';

    $product_mapping = create_product_mapping();
    // Пошук контакту за номером телефону
    $contacts_endpoint = $pipedrive_url . 'persons/search?term=' . urlencode($phone) . '&search_by_phone=1&api_token=' . $pipedrive_api_token;
    $response = wp_remote_get($contacts_endpoint);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        file_put_contents($fileErrorLog, '-->'.print_r("Помилка при з'єднанні з Pipedrive API: " . $error_message,1).'<--'."\n", FILE_APPEND); //fixme PRINT
        error_log("Помилка при з'єднанні з Pipedrive API: " . $error_message);
        return;
    } else {
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $contact_id = null;
        if ($response_body['data'] && !empty($response_body['data']['items'][0])) {
            // Контакт знайдений за номером телефону
            $itemPerson = current($response_body['data']['items']);
            $contact_id = $itemPerson['item']['id'];
        } else {
            // Контакт не знайдений, створення нового контакту в Pipedrive
            $new_contact_data = array(
                'name' => $first_name,
                'phone' => $phone,
                // Додайте інші необхідні поля для контакту тут
            );

            $new_contact_endpoint = $pipedrive_url . 'persons?api_token=' . $pipedrive_api_token;
            $response = wp_remote_post($new_contact_endpoint, array(
                'method' => 'POST',
                'headers' => array('Content-Type' => 'application/json'),
                'body' => json_encode($new_contact_data),
            ));
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                file_put_contents($fileErrorLog, '-->'.print_r("Помилка при створенні контакту в Pipedrive: " . $error_message,1).'<--'."\n", FILE_APPEND); //fixme PRINT
                error_log("Помилка при створенні контакту в Pipedrive: " . $error_message);
                return;
            }

            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $contact_id = $response_body['data']['id'];
        }
        // Створення угоди в Pipedrive
        $new_deal_data = array(
            'title' => '!TEST! Замовлення №' . $order_id . ', ' . $first_name,
            'value' => $order->get_total(),
            'currency' => $order->get_currency(),
            'person_id' => $contact_id,
            '37b3e9059434b03c78e453864a723e0ed8117242' => $person_count,
            '94ebd4ecea470e6c08206f1a4a16fa3c8e26c3a5' => $shipping_method, // old 6658944630026abfc0b89d6fe33881a7ef148b0a
            'f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d' => $payment_method,
            '5d235e313b0ce48b9f65bf088067d92171707346' => $billing_city,
            'fbe86ec9ff0fbaaeada151555b6a8508f89baa1a' => 'Сайт',
            // Додайте інші необхідні поля для угоди тут
        );
        $new_deal_endpoint = $pipedrive_url . 'deals?api_token=' . $pipedrive_api_token;
        $response = wp_remote_post($new_deal_endpoint, array(
            'method' => 'POST',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($new_deal_data),
        ));

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            file_put_contents($fileErrorLog, '-->'.print_r("Помилка при створенні угоди в Pipedrive: " . $error_message,1).'<--'."\n", FILE_APPEND); //fixme PRINT
            error_log("Помилка при створенні угоди в Pipedrive: " . $error_message);
            return;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $deal_id = $response_body['data']['id'];

        file_put_contents($fileErrorLog, '-->'.print_r("Угода створена в Pipedrive з ID: " . $deal_id,1).'<--'."\n", FILE_APPEND); //fixme PRINT
        error_log("Угода створена в Pipedrive з ID: " . $deal_id);

        $deal_products_endpoint = $pipedrive_url . 'deals/' . $deal_id . '/products?api_token=' . $pipedrive_api_token;

        foreach ($items as $item_id => $item) {
            $product = $item->get_product();
            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();
            $price = $product->get_regular_price();
            $shopPosterProductId = get_field('poster_product_id', $product_id);
            $pipedrive_product_id = isset($product_mapping[$shopPosterProductId])??$product_mapping[$shopPosterProductId];
            $request_data = [
                'product_id' => (int) $pipedrive_product_id,
                'quantity' => (int) $quantity,
                'item_price' => (float) $price,
                // Додайте інші необхідні поля для товару тут
            ];

            $response = wp_remote_post($deal_products_endpoint, array(
                'method' => 'POST',
                'headers' => array('Content-Type' => 'application/json'),
                'body' => json_encode($request_data),
            ));
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                file_put_contents($fileErrorLog, '-->'.print_r("Помилка при додаванні товару до угоди в Pipedrive: " . $error_message,1).'<--'."\n", FILE_APPEND); //fixme PRINT
                error_log("Помилка при додаванні товару до угоди в Pipedrive: " . $error_message);
            }
        }
    }
}

function get_pipedrive_products() {
    // Ваші дані доступу до API Pipedrive
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    // URL Pipedrive API
    $pipedrive_url = 'https://api.pipedrive.com/v1/';


    // URL Pipedrive API для отримання списку товарів
    $api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token;

    // Виконання запиту
    $response = wp_remote_get($api_url);

    // Обробка відповіді
    if (is_wp_error($response)) {
        // Обробка помилки
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/error.php', '-->'.print_r('Помилка при отриманні товарів з Pipedrive: ',1).'<--'."\n", FILE_APPEND); //fixme PRINT
        error_log('Помилка при отриманні товарів з Pipedrive: ' . $response->get_error_message());
        return array(); // Повернення порожнього масиву у випадку помилки
    }

    // Розпакування та обробка відповіді API Pipedrive
    $response_body = wp_remote_retrieve_body($response);
    $pipedrive_products = json_decode($response_body, true);

    return $pipedrive_products['data'];
}

function get_woocommerce_products() {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    );

    $products = get_posts($args);

    $woocommerce_products = array();

    foreach ($products as $product) {
        $product_data = wc_get_product($product->ID);

        $woocommerce_product = array(
            'id'    => $product_data->get_id(),
            'name'  => $product_data->get_name(),
            'price' => $product_data->get_price(),
            // Додайте інші потрібні дані товару тут
        );

        $woocommerce_products[] = $woocommerce_product;
    }

    return $woocommerce_products;
}

function create_product_mapping() {
    // Отримання товарів з CRM Pipedrive
    $pipedrive_products = get_pipedrive_products();
    $product_mapping = [];
    // Ітеруємося по товарам з CRM Pipedrive
    foreach ($pipedrive_products as $pipedrive_product) {
        // 375301cd5d99cfacd197fced35c0bc29f2242ec5 - POSTER_ID  $pipedrive_product['375301cd5d99cfacd197fced35c0bc29f2242ec5']
        // Отримуємо ID товару з CRM Pipedrive
        $pipedrive_product_id = $pipedrive_product['id'];
        $product_mapping[$pipedrive_product['375301cd5d99cfacd197fced35c0bc29f2242ec5']] = $pipedrive_product_id;
    }

    return $product_mapping;
}
