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
    $cleanedNumber = preg_replace('/\D/', '', $phone);
    $first_name = $_POST['billing_first_name'];

    $person_count = $_POST['count_of_chopstics'];
    $shipping_method = $_POST['shipping_method'];
    $payment_method = $_POST['payment_method'];
    $billing_city = $_POST['billing_city'];
    if ($payment_method === 'cod') {
        $payment_method = "Оплата готівкою без решти";
    }

    if ($shipping_method[0] === "flat_rate:3") {
        $shipping_method = "Доставка";
    }

    if ($shipping_method[0] === "local_pickup:2") {
        $shipping_method = "Самовиніс";
    }

    // Ваші дані доступу до API Pipedrive
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    // URL Pipedrive API
    $pipedrive_url = 'https://api.pipedrive.com/v1/';

    $product_mapping = create_product_mapping();
    // Пошук контакту за номером телефону

    $contacts_endpoint = $pipedrive_url . 'persons/search?term=' . urlencode($cleanedNumber) . '&search_by_phone=1&api_token=' . $pipedrive_api_token;
    $response = wp_remote_get($contacts_endpoint);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Помилка при запиті(persons/search) пошуку контакту у Pipedrive: " . $error_message."\n", FILE_APPEND); //fixme PRINT
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
                'phone' => $cleanedNumber,
                '0fa72bbe53a809235ed169573f0696aa9cad435e' => 'Сайт'
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
                file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Помилка при запиті(persons) створенні контакту в Pipedrive: " . $error_message."\n", FILE_APPEND); //fixme PRINT
                return;
            }

            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            $contact_id = $response_body['data']['id'];
        }
        // Створення угоди в Pipedrive
        $new_deal_data = array(
            'title' => 'Сайт №' . $order_id . ', ' . $first_name,
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
            file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Помилка при запиті створення угоди в Pipedrive: " . $error_message."\n", FILE_APPEND); //fixme PRINT
            return;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $deal_id = $response_body['data']['id'];

        file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Pipedrive ID: $deal_id. Shop ID: $order_id"."\n", FILE_APPEND); //fixme PRINT

        $deal_products_endpoint = $pipedrive_url . 'deals/' . $deal_id . '/products?api_token=' . $pipedrive_api_token;


        //region Добавляємо завжди один товар "Пакування"  ID 355 PosterId 275
        // товар пакування на сайті ID 972
        $skuPackaging = '275_1';
        $productPackagingId = wc_get_product_id_by_sku($skuPackaging);
        $productPackaging = wc_get_product($productPackagingId);
        $price = 0;
        if ($productPackaging) {
            $price = $productPackaging->get_price(); // Отримання ціни товару
        }
        $request_dataPackaging = [
            'product_id' => 355,
            'quantity' => 1,
            'item_price' => (float)$price,
        ];
        $responsePackaging = wp_remote_post($deal_products_endpoint, array(
            'method' => 'POST',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($request_dataPackaging),
        ));
        if (is_wp_error($responsePackaging)) {
            $error_message = $response->get_error_message();
            file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Помилка при додаванні товару Пакування до угоди в Pipedrive: " . $error_message."\n", FILE_APPEND);
        }

        //endregion


        foreach ($items as $item_id => $item) {
            $product = $item->get_product();
            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();
            // Отримати звичайну ціну товару
            $regular_price = $product->get_regular_price();
            // Отримати ціну зі знижкою (якщо вона є)
            $sale_price = $product->get_sale_price();
            $price = (empty($sale_price)?$regular_price:$sale_price);

            $pipedrive_product_id = null;
            $shopPosterProductId = get_field('poster_product_id', $product_id);
            if(!empty($shopPosterProductId) && key_exists($shopPosterProductId, $product_mapping))
                $pipedrive_product_id = $product_mapping[$shopPosterProductId];

            if($pipedrive_product_id === null){
                $messageError = date('d.m.Y G:i:s')." Замовлення №$order_id. Проблеми з товаром $product_id, у нього не заповнений shopPosterProductId";
                file_put_contents($fileErrorLog, $messageError."\n", FILE_APPEND);
                continue;
            }
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
                file_put_contents($fileErrorLog, date('d.m.Y G:i:s')." Помилка при додаванні товару до угоди в Pipedrive: " . $error_message."\n", FILE_APPEND);
            }
        }
    }
}

function get_pipedrive_products() {
    $dirLog = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log';
    $fileErrorLog = $dirLog.'/functions_deal.log';
    // Ваші дані доступу до API Pipedrive
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    // URL Pipedrive API
    $pipedrive_url = 'https://api.pipedrive.com/v1/';


    $limit = 1000;
    // Ваші дані доступу до API Poster
    $params = ['limit' => $limit];
    $httpBuilder = http_build_query($params);
    // URL Pipedrive API для отримання списку товарів
    $api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token . '&' . $httpBuilder;

    // Виконання запиту
    $response = wp_remote_get($api_url);

    // Обробка відповіді
    if (is_wp_error($response)) {
        // Обробка помилки
        file_put_contents($fileErrorLog, date('d.m.Y G:i:s').' Помилка при отриманні товарів з Pipedrive'."\n", FILE_APPEND); //fixme PRINT
        return []; // Повернення порожнього масиву у випадку помилки
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
