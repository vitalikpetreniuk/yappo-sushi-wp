<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/pipedrive/system_errors.log');

/** Set up WordPress environment */
require_once  $_SERVER['DOCUMENT_ROOT']. '/wp-load.php';
global $pipedrive_request;
try {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log';
    header('Access-Control-Allow-Origin: *');
    function dd( $v ) { var_dump($v); exit();}

    function addCustomLogPipe($message, $data=[]){
        $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log';
        if(empty($data)){
            file_put_contents($file, date('d.m.Y G:i:s')." ".$message."\n", FILE_APPEND); //fixme PRINT
        }else{
            $messageToFile = date('d.m.Y G:i:s')." ".$message."\n";
            file_put_contents($file, $messageToFile.print_r($data,1)."\n", FILE_APPEND); //fixme PRINT
        }

    }

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
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log', date('d.m.Y G:i:s').' Помилка при отриманні товарів з Pipedrive: ' . $response->get_error_message()."\n", FILE_APPEND); //fixme PRINT
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

    $data = $pipedrive_request->get_json_params();

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
    $comment = $deal['data']['fc4ee5328ab0a1c6d5db36814f00ece0bde334a8']; // Коментар
    $address = $deal['data']['38a3380abae966cc665e69260c73530acaf83856']; // Адреса доставки
    $timeDelivery = $deal['data']['a7de320a9bd09a2e62dc9337bec6ebbaf229b269']; // Час замовлення
    $typeGetOrder = $deal['data']['94ebd4ecea470e6c08206f1a4a16fa3c8e26c3a5']; // Спосіб отримання //  118:Cамовиніс || 119:Доставка || 129:У магазині
    $dateDelivery = (!empty($deal['data']['6c4e24941e6ca71c1923fa6491badfd2341a66d5'])? $deal['data']['6c4e24941e6ca71c1923fa6491badfd2341a66d5'] : date('Y-m-d'));
    $fullDateDelivery = $dateDelivery.' '.$timeDelivery;
    $source = $deal['data']['fbe86ec9ff0fbaaeada151555b6a8508f89baa1a']; // 101 Ofline
    $payment = $deal['data']['f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d']; // 66 -  Оплата готівкою без решти, 113 - Оплата термінал, 114 - Оплата переказ на карту, 132 - Оплата готівкою з рештою
    $sumReshta = $deal['data']['61c93cfb617ba7c513836825264e18f8a2389397']; // Решта з суми
    $sumReshtaTitle = 'Решта з суми: '.$sumReshta;
    $paymentTitle = '';
    $service_mode = null;
    if(!empty($typeGetOrder)){
        // Создает заказ указанного типа: 1 — в заведении, 2 — навынос, 3 — доставка
        switch ($typeGetOrder){
            case 118:
                $service_mode = 2;
                break;
            case 119:
                $service_mode = 3;
                break;
            case 129:
                $service_mode = 1;
                break;
        }
    }

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
    if($source == 101 || $status != 'won'){
        // угоди Ofline не обробляти!!!! та не обробляти ті які не виграні
        die();
    }
    addCustomLogPipe("На обробці угода №$deal_id");
//    addCustomLogPipe('$deal', $deal); // Log


    $shopKey = '32fad1e114d019f0977fee93416030cabd95b3d1';
    $shop_option_id = $deal['data'][$shopKey];
    $shopObject = null;
    $shopOptionObject = null;


    // Отримання кастомних полів
    $shop_url = $pipedrive_url . 'dealFields?api_token=' . $pipedrive_api_token;
    $shop_response = wp_remote_get($shop_url);
    if (is_wp_error($shop_response)) {
        addCustomLogPipe("Помилка при отриманні кастомних полів з Pipedrive: ".$shop_response->get_error_message()); // Log
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
        addCustomLogPipe("Помилка при отриманні товарів з Pipedrive: ".$product_response->get_error_message()); // Log
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
    //*

    if ($status === 'won' && !empty($newProductsArray)) {
        $order_url = $poster_url . 'incomingOrders.createIncomingOrder?token=' . $poster_api_token;
//        $string = "Abc123Def456Ghi789";
        $clearPhone = preg_replace("/[^0-9]/", "", $phone);
        $commentArray = [];
        if(!empty($comment)){
            $commentArray[] = $comment;
        }
        if(!empty($payment)){
            $commentArray[] = $paymentTitle;
        }
        if(!empty($sumReshta)){
            $commentArray[] = $sumReshtaTitle;
        }
        $order_body = array(
            'spot_id' => $poster_shop_id,
            'first_name' => $name,
            'phone' => '+'.$clearPhone,
            'service_mode' => $service_mode,
            'products' => $newProductsArray,
            'comment'=> implode("\n", $commentArray),
//            'payment'=>[
//                'type' => 0,
//                'sum' => $dealValue,
//                'currency' => 'UAH'
//            ],
            "address"=>$address,
            "delivery_time"=>$fullDateDelivery,
        );
        if($service_mode == null){
            unset($order_body['service_mode']);
        }
        if(empty(trim($address))){
            unset($order_body['address']);
        }
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
            addCustomLogPipe("Запити на створення угоди у Poster не вдався. ".$order_response->get_error_message()); // Log
        } else {
            $response_code = wp_remote_retrieve_response_code($order_response);
            $response_body = wp_remote_retrieve_body($order_response);
            $response = json_decode($response_body, true);
            if(empty($response['response']['incoming_order_id'])){
                // it's bad
                addCustomLogPipe("Угода у Poster не створилась. Pipedeal ID $deal_id. Текст помилки: ". $response['message']); // Log
                addCustomLogPipe('$response', $response); // Log
            }else{
                $posterDealId = $response['response']['incoming_order_id'];
                addCustomLogPipe("Успішно створене онлайн замовлення у Poster. PosterDealId $posterDealId. PipedriveDeal ID $deal_id."); // Log
            }
        }

    }
    //*/

}
catch (Exception $e) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_exception_deals.log', $status . 'Error: ' .json_encode($e->getMessage()) . PHP_EOL, FILE_APPEND);
}
