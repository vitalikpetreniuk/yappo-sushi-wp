<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/pipedrive/system_errors.log');

/** Set up WordPress environment */
require_once __DIR__ . '/wp-load.php';


try {

    $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive/handler.txt';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        header('Access-Control-Allow-Origin: *');
        function dd( $v ) { var_dump($v); exit();}

        function getPipeProducts($arIdsPipeProductInDeal){
            // Ваші дані доступу до API Pipedrive
            $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

            // URL Pipedrive API
            $pipedrive_url = 'https://api.pipedrive.com/v1/';

            $httpFilter = http_build_query(['ids'=>implode(',',$arIdsPipeProductInDeal)]);
            // URL Pipedrive API для отримання списку товарів
            $api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token."&".$httpFilter;


            // Виконання запиту
            $response = wp_remote_get($api_url);

            // Обробка відповіді
            if (is_wp_error($response)) {
                // Обробка помилки
                error_log('Помилка при отриманні товарів з Pipedrive: ' . $response->get_error_message());
                return array(); // Повернення порожнього масиву у випадку помилки
            }

            // Розпакування та обробка відповіді API Pipedrive
            $response_body = wp_remote_retrieve_body($response);
            $pipedrive_products = json_decode($response_body, true);

            $newData = [];
            foreach ($pipedrive_products['data'] as $datum) {
                $newData[$datum['id']] = $datum;
            }

            return $newData;
        }


        $data = file_get_contents('php://input');
        $data = json_decode($data, true);


        $deal_id = $data['meta']['id'];


        // Токен Pipedrive
        $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
        // Токен Poster
        $poster_api_token = '700115:0576459e25fcc87687ae3a1b33142706';

        // URL Pipedrive API
        $pipedrive_url = 'https://api.pipedrive.com/v1/';
        // URL Poster API
        $poster_url = 'https://joinposter.com/api/';


        // Отримання інформації про угоду
        $deal_url = $pipedrive_url . 'deals/' . $deal_id . '?api_token=' . $pipedrive_api_token;

        $deal_response = wp_remote_get($deal_url);
        if (is_wp_error($deal_response)) {
            error_log('Помилка при отриманні угоди з Pipedrive: ' . $deal_response->get_error_message());
            return array();
        }

        $deal_response_body = wp_remote_retrieve_body($deal_response);
        $deal = json_decode($deal_response_body, true);
        $phone = '';
        $name = '';
        if(!empty($deal['data']['person_id'])){
            $name = $deal['data']['person_id']['name'];
            if(!empty($deal['data']['person_id']['phone'][0]['value'])){
                $phone = $deal['data']['person_id']['phone'][0]['value'];
            }
        }
        $status = $deal['data']['status'];
        $comment = $deal['data']['fc4ee5328ab0a1c6d5db36814f00ece0bde334a8'];
        $address = $deal['data']['38a3380abae966cc665e69260c73530acaf83856'];
        $timeDelivery = $deal['data']['a7de320a9bd09a2e62dc9337bec6ebbaf229b269'];
        $dateDelivery = (!empty($deal['data']['6c4e24941e6ca71c1923fa6491badfd2341a66d5'])? $deal['data']['6c4e24941e6ca71c1923fa6491badfd2341a66d5'] : date('Y-m-d'));
        $fullDateDelivery = $dateDelivery.' '.$timeDelivery;
        $source = $deal['data']['fbe86ec9ff0fbaaeada151555b6a8508f89baa1a']; // 101 Ofline
        $payment = $deal['data']['f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d']; // 66 -  Оплата готівкою без решти, 113 - Оплата термінал, 114 - Оплата переказ на карту, 132 - Оплата готівкою з рештою
        $sumReshta = $deal['data']['61c93cfb617ba7c513836825264e18f8a2389397'];
        $sumReshtaTitle = 'Решта з суми: '.$sumReshta;
        $paymentTitle = '';
        switch($payment){
            case 66:
                $paymentTitle = 'Оплата готівкою без решти';
                break;
            case 113:
                $paymentTitle = 'Оплата термінал';
                break;
            case 114:
                $paymentTitle = 'Оплата переказ на карту';
                break;
            case 132:
                $paymentTitle = 'Оплата готівкою з рештою';
                break;

        }
        $dealValue = $deal['data']['value'];
        if($source == 101){
            // угоди Ofline не обробляти!!!!
            die();
        }


        $shopKey = '32fad1e114d019f0977fee93416030cabd95b3d1';
        $shop_option_id = $deal['data'][$shopKey];
        $shopObject = null;
        $shopOptionObject = null;


        // Отримання кастомних полів
        $shop_url = $pipedrive_url . 'dealFields?api_token=' . $pipedrive_api_token;
        $shop_response = wp_remote_get($shop_url);
        if (is_wp_error($shop_response)) {
            error_log('Помилка при отриманні кастомних полів з Pipedrive: ' . $shop_response->get_error_message());
            return array();
        }
        $shop_response_body = wp_remote_retrieve_body($shop_response);
        $shop = json_decode($shop_response_body, true);
        // Отримання магазина
        foreach ($shop['data'] as $object) {
            if ($object['key'] === $shopKey) {
                $shopObject = $object;
                break;
            }
        }

        if ($shopObject !== null) {
            foreach ($shopObject['options'] as $option) {
                if ($option['id'] === intval($shop_option_id)) {
                    $shopOptionObject = $option;
                    break;
                }
            }
        }

        $poster_shop_id = 1;

        if(!empty($shopOptionObject['label'])){
            switch ($shopOptionObject['label']) {
                case 'Буча Нове Шосе 8а':
                    $poster_shop_id = 1;
                    break;
                case 'Вишгород Набережна 2д':
                    $poster_shop_id = 2;
                    break;
                case 'Васильків Грушевського 25':
                    $poster_shop_id = 3;
                    break;
                case 'Черкаси Смілянська 36':
                    $poster_shop_id = 4;
                    break;
                case 'Чайки Лобановського 31':
                    $poster_shop_id = 5;
                    break;
                case 'Бровари Героїв України 11':
                    $poster_shop_id = 6;
                    break;
                case 'Бровари Київська 253':
                    $poster_shop_id = 7;
                    break;
            }
        }
        // Отримання продуктів
        $product_url = $pipedrive_url . 'deals/' . $deal_id . '/products?api_token=' . $pipedrive_api_token;

        $product_response = wp_remote_get($product_url);

        if (is_wp_error($product_response)) {
            error_log('Помилка при отриманні товарів з Pipedrive: ' . $product_response->get_error_message());
            return array();
        }

        $product_response_body = wp_remote_retrieve_body($product_response);
        $products = json_decode($product_response_body, true);
        $newProductsArray = [];
        if(!empty($products['data'])){
            $str = '';
            $product_mapping = getPipeProducts(array_column($products['data'], 'product_id'));
            foreach ($products['data'] as $item) {
                $productId = $item['product_id'];
                $count = $item['quantity'];
                $price = $item['item_price'] * 100;

                $poster_product_id = isset($product_mapping[$productId]) ? $product_mapping[$productId]['375301cd5d99cfacd197fced35c0bc29f2242ec5'] : ''; // 375301cd5d99cfacd197fced35c0bc29f2242ec5 - key Fields of PosterID

                $str .= $poster_product_id;

                if(!empty($poster_product_id)){
                    $newProductsArray[] = [
                        'product_id' => $poster_product_id,
                        'count' => $count,
                        'price' => $price
                    ];
                }
            }
        }

        // Надсилання запиту в Poster для стоврення онлайн-замовлення, якщо замовлення виграно
        // тимчасово вимкнено. Проводиться тестування
        //*

        if ($status === 'won' && !empty($newProductsArray)) {
            $order_url = $poster_url . 'incomingOrders.createIncomingOrder?token=' . $poster_api_token;
            $string = "Abc123Def456Ghi789";
            $clearPhone = preg_replace("/[^0-9]/", "", $phone);
            $order_body = array(
                'spot_id' => $poster_shop_id,
                'first_name' => $name,
                'phone' => '+'.$clearPhone,
                'service_mode' => 3,
                'products' => $newProductsArray,
                'comment'=> implode("\n",[$comment, $paymentTitle, $sumReshtaTitle]),
                'payment'=>[
                    'type' => 0,
                    'sum' => $dealValue,
                    'currency' => 'UAH'
                ],
                "address"=>$address,
                "delivery_time"=>$fullDateDelivery,
            );
            if(empty($phone)){
                unset($order_body['phone']);
            }
            if(empty($timeDelivery)){
                unset($order_body['delivery_time']);
            }
            $order_response = wp_remote_post($order_url, array(
                'method' => 'POST',
                'headers' => array('Content-Type' => 'application/json'),
                'body' => json_encode($order_body),
            ));

            if (is_wp_error($order_response)) {
                file_put_contents($file, json_encode($order_response) . PHP_EOL, FILE_APPEND);
            } else {
                $response_code = wp_remote_retrieve_response_code($order_response);
                $response_body = wp_remote_retrieve_body($order_response);
                file_put_contents($file, $status . json_encode($order_response) . PHP_EOL, FILE_APPEND);
                dd($response_body);
                http_response_code(200);
            }

        }
        //*/
    }
}
catch (Exception $e) {
    file_put_contents($file, $status . 'Error: ' .json_encode($e->getMessage()) . PHP_EOL, FILE_APPEND);
}

http_response_code(200);
