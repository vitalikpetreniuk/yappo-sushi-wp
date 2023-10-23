<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive/system_errors.log');

/** Set up WordPress environment */
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
global $pipedrive_request;
define('WV_PIPEDRIVE_API_TOKEN', 'e952c2d4b8239f6e760ecbe012e341d5aa018adf');
define('WV_PIPEDRIVE_API_URL', 'https://api.pipedrive.com/v1/');

try {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log';
    header('Access-Control-Allow-Origin: *');

    function addCustomLogPipe($message, $data = [])
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log';
        if (empty($data)) {
            file_put_contents($file, "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n", FILE_APPEND); //fixme PRINT
        } else {
            $messageToFile = "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n";
            file_put_contents($file, $messageToFile . print_r($data, 1) . "\n", FILE_APPEND); //fixme PRINT
        }

    }

    function addCustomLogPipeArray($message, $data = [])
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals_array.log';
        if (empty($data)) {
            file_put_contents($file, "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n", FILE_APPEND); //fixme PRINT
        } else {
            $messageToFile = "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n";
            file_put_contents($file, $messageToFile . print_r($data, 1) . "\n", FILE_APPEND); //fixme PRINT
        }

    }

    function addCustomLogPipeCopyOrders($message, $data = [])
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals_copy.log';
        if (empty($data)) {
            file_put_contents($file, "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n", FILE_APPEND); //fixme PRINT
        } else {
            $messageToFile = "(+3 години) " . date('d.m.Y G:i:s') . " " . $message . "\n";
            file_put_contents($file, $messageToFile . print_r($data, 1) . "\n", FILE_APPEND); //fixme PRINT
        }

    }

    function getPipeProducts($arIdsPipeProductInDeal)
    {
        $httpFilter = http_build_query(['ids' => implode(',', $arIdsPipeProductInDeal)]);
        // URL Pipedrive API для отримання списку товарів
        $api_url = WV_PIPEDRIVE_API_URL . 'products?api_token=' . WV_PIPEDRIVE_API_TOKEN . "&" . $httpFilter;


        // Виконання запиту
        $response = wp_remote_get($api_url);

        // Обробка відповіді
        if (is_wp_error($response)) {
            // Обробка помилки
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_deals.log', date('d.m.Y G:i:s') . ' Помилка при отриманні товарів з Pipedrive: ' . $response->get_error_message() . "\n", FILE_APPEND); //fixme PRINT
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

    function getPipeDrivePersonDetail($person_id=''){
        if(empty($person_id))
            return [];

        // URL Pipedrive API
        $person_url = WV_PIPEDRIVE_API_URL . 'persons/' . $person_id . '?api_token=' . WV_PIPEDRIVE_API_TOKEN;
        $person_response = wp_remote_get($person_url);
        if (is_wp_error($person_response)) {
            error_log('Помилка при отриманні person з Pipedrive: ' . $person_response->get_error_message());
            return array();
        }
        $person_response_body = wp_remote_retrieve_body($person_response);
        $dataResponse = json_decode($person_response_body, true);
        if($dataResponse['success']){
            return $dataResponse['data'];
        }

        return [];
    }

    function updateDealInPipedrive($dealId, $data){
        $deal_url = WV_PIPEDRIVE_API_URL . 'deals/' . $dealId . '?api_token=' . WV_PIPEDRIVE_API_TOKEN;
        $response = wp_remote_post($deal_url, array(
            'timeout' => 30, // Максимальний час очікування у секундах
            'method' => 'PUT',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($data), // http_build_query($data) || json_encode($data)
        ));

        if (is_wp_error($response)) {
            // has error
            addCustomLogPipe("Функція updateDealInPipedrive пройшла з помилкою. Угода №$dealId не оновила дані.\nПомилка1 \"". $response->get_error_message()."\"");
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $decodeBody = json_decode($response_body, true);
            if( empty($decodeBody['success']) && !empty($decodeBody['error']) ){
                // error
                addCustomLogPipe("Функція updateDealInPipedrive пройшла з помилкою. Угода №$dealId не оновила дані.\nПомилка2 \"". implode("\n", [$decodeBody['error'], $decodeBody['error_info']]) ."\"");
            }
        }
    }

    $data = $pipedrive_request->get_json_params();

    $deal_id = $data['meta']['id'];
    $metaDealCurrent = $data['current'];
    $metaDealPrevious = $data['previous'];

    /**
     * person_name +
     * persone_id phone - треба отримувати запитом
     *
     * Deal
     * status +
     * fc4ee5328ab0a1c6d5db36814f00ece0bde334a8 +
     * 38a3380abae966cc665e69260c73530acaf83856 +
     * a7de320a9bd09a2e62dc9337bec6ebbaf229b269 +
     * 94ebd4ecea470e6c08206f1a4a16fa3c8e26c3a5 +
     * 6c4e24941e6ca71c1923fa6491badfd2341a66d5 +
     * fbe86ec9ff0fbaaeada151555b6a8508f89baa1a +
     * f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d +
     * 61c93cfb617ba7c513836825264e18f8a2389397 +
     * 32fad1e114d019f0977fee93416030cabd95b3d1 +
     */

    // Токен Poster
    $poster_api_token = '700115:0576459e25fcc87687ae3a1b33142706';

    // URL Poster API
    $poster_url = 'https://joinposter.com/api/';

    $phone = '';
    $name = '';
    if (!empty($metaDealCurrent['person_id'])) {
        $name = $metaDealCurrent['person_name'];
        $getDataPerson = getPipeDrivePersonDetail($metaDealCurrent['person_id']);
        $currentPhonePerson = current($getDataPerson['phone']);
        if (!empty($currentPhonePerson)) {
            $phone = $currentPhonePerson['value'];
        }
    }

    $status = $metaDealCurrent['status'];
    $comment = $metaDealCurrent['fc4ee5328ab0a1c6d5db36814f00ece0bde334a8']; // Коментар
    $address = $metaDealCurrent['38a3380abae966cc665e69260c73530acaf83856']; // Адреса доставки
    $timeDelivery = $metaDealCurrent['a7de320a9bd09a2e62dc9337bec6ebbaf229b269']; // Час замовлення
    $typeGetOrder = $metaDealCurrent['94ebd4ecea470e6c08206f1a4a16fa3c8e26c3a5']; // Спосіб отримання //  118:Cамовиніс || 119:Доставка || 129:У магазині
    $dateDelivery = (!empty($metaDealCurrent['6c4e24941e6ca71c1923fa6491badfd2341a66d5']) ? $metaDealCurrent['6c4e24941e6ca71c1923fa6491badfd2341a66d5'] : date('Y-m-d'));
    $fullDateDelivery = $dateDelivery . ' ' . $timeDelivery;
    $source = $metaDealCurrent['fbe86ec9ff0fbaaeada151555b6a8508f89baa1a']; // 101 Ofline
    $payment = $metaDealCurrent['f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d']; // 66 -  Оплата готівкою без решти, 113 - Оплата термінал, 114 - Оплата переказ на карту, 132 - Оплата готівкою з рештою
    $sumReshta = $metaDealCurrent['61c93cfb617ba7c513836825264e18f8a2389397']; // Решта з суми
    $incomingOrders = $metaDealCurrent['5d8521bdee2e3df20572a18b05a967395c53a9e9']; // incomingOrders - id онлайн замовлення
    $shopKey = '32fad1e114d019f0977fee93416030cabd95b3d1';
    $sumReshtaTitle = 'Решта з суми: ' . $sumReshta;
    $paymentTitle = '';
    $service_mode = null;
    $conditionStatus = ($metaDealCurrent['status'] == 'won') ? true : false;
    $conditionPrevStatus = ($metaDealCurrent['status'] != $metaDealPrevious['status']) ? true : false;
    if (!empty($typeGetOrder)) {
        // Создает заказ указанного типа: 1 — в заведении, 2 — навынос, 3 — доставка
        switch ($typeGetOrder) {
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
    switch ($payment) {
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

    if (!$conditionStatus || !$conditionPrevStatus || $source == 101 || (!empty($incomingOrders) && intval($incomingOrders) > 0) ) {

        if(!empty($incomingOrders) && intval($incomingOrders) > 0){
            addCustomLogPipeCopyOrders("На обробці угода №$deal_id");
            addCustomLogPipeCopyOrders('$metaDealPrevious', $metaDealPrevious);
            addCustomLogPipeCopyOrders('$metaDealCurrent', $metaDealCurrent);
        }

        // угоди Ofline не обробляти!!!! та не обробляти ті які не виграні
        die();
    }
    addCustomLogPipe("На обробці угода №$deal_id");

    addCustomLogPipeArray("На обробці угода №$deal_id");
    addCustomLogPipeArray('$metaDealPrevious', $metaDealPrevious);
    addCustomLogPipeArray('$metaDealCurrent', $metaDealCurrent);

    $shop_option_id = $metaDealCurrent[$shopKey];
    $shopObject = null;
    $shopOptionObject = null;


    // Отримання кастомних полів
    $shop_url = WV_PIPEDRIVE_API_URL . 'dealFields?api_token=' . WV_PIPEDRIVE_API_TOKEN;
    $shop_response = wp_remote_get($shop_url);
    if (is_wp_error($shop_response)) {
        addCustomLogPipe("Помилка при отриманні кастомних полів з Pipedrive: " . $shop_response->get_error_message()); // Log
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

    if (!empty($shopOptionObject['label'])) {
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
    $product_url = WV_PIPEDRIVE_API_URL . 'deals/' . $deal_id . '/products?api_token=' . WV_PIPEDRIVE_API_TOKEN;

    $product_response = wp_remote_get($product_url);

    if (is_wp_error($product_response)) {
        addCustomLogPipe("Помилка при отриманні товарів з Pipedrive: " . $product_response->get_error_message()); // Log
        return array();
    }

    $product_response_body = wp_remote_retrieve_body($product_response);
    $products = json_decode($product_response_body, true);
    $newProductsArray = [];
    if (!empty($products['data'])) {
        $str = '';
        $product_mapping = getPipeProducts(array_column($products['data'], 'product_id'));
        foreach ($products['data'] as $item) {
            $productId = $item['product_id'];
            $count = $item['quantity'];
            $price = $item['item_price'] * 100;
            $poster_product_id = isset($product_mapping[$productId]) ? $product_mapping[$productId]['375301cd5d99cfacd197fced35c0bc29f2242ec5'] : ''; // 375301cd5d99cfacd197fced35c0bc29f2242ec5 - key Fields of PosterID
            $str .= $poster_product_id;
            if (!empty($poster_product_id)) {
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
        $posterDealId = 0;
        $clearPhone = preg_replace("/[^0-9]/", "", $phone);
        $commentArray = [];
        if (!empty($comment)) {
            $commentArray[] = $comment;
        }
        if (!empty($payment)) {
            $commentArray[] = $paymentTitle;
        }
        if (!empty($sumReshta)) {
            $commentArray[] = $sumReshtaTitle;
        }
        $order_body = array(
            'spot_id' => $poster_shop_id,
            'first_name' => $name,
            'phone' => '+' . $clearPhone,
            'service_mode' => $service_mode,
            'products' => $newProductsArray,
            'comment' => implode("\n", $commentArray),
            "address" => $address,
            "delivery_time" => $fullDateDelivery,
        );
        if ($service_mode == null) {
            unset($order_body['service_mode']);
        }
        if (empty(trim($address))) {
            unset($order_body['address']);
        }
        if (empty($phone)) {
            unset($order_body['phone']);
        }
        if (empty($timeDelivery)) {
            unset($order_body['delivery_time']);
        }

        $order_response = wp_remote_post($order_url, array(
            'timeout' => 30, // Максимальний час очікування у секундах
            'method' => 'POST',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($order_body),
        ));

        $responseMessage = '';
        if (is_wp_error($order_response)) {
            addCustomLogPipe("Запити на створення угоди у Poster не вдався. " . $order_response->get_error_message()); // Log
        } else {
            $response_code = wp_remote_retrieve_response_code($order_response);
            $response_body = wp_remote_retrieve_body($order_response);
            $response = json_decode($response_body, true);
            if (empty($response['response']['incoming_order_id'])) {
                // it's bad
                $responseMessage = $response['message'];
                addCustomLogPipe("Угода у Poster не створилась. Pipedeal ID $deal_id. Текст помилки: " . $response['message']); // Log
                addCustomLogPipe('$response', $response); // Log
            } else {
                $posterDealId = $response['response']['incoming_order_id'];
                $responseMessage = $posterDealId;
                addCustomLogPipe("Успішно створене онлайн замовлення у Poster. PosterDealId $posterDealId. PipedriveDeal ID $deal_id."); // Log
            }
        }
        $dataUpdateDealInPipedrive["5d8521bdee2e3df20572a18b05a967395c53a9e9"] = $responseMessage; // 5d8521bdee2e3df20572a18b05a967395c53a9e9 = incomingOrders
        updateDealInPipedrive($deal_id, $dataUpdateDealInPipedrive);
    }
    //*/

} catch (Exception $e) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/pipedrive_exception_deals.log', $status . 'Error: ' . json_encode($e->getMessage()) . PHP_EOL, FILE_APPEND);
}
