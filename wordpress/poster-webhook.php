<?php
$postJSON = file_get_contents('php://input');
$postData = json_decode($postJSON, true);
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/PosterData.php', date('d.m.Y G:i').' ' . print_r($postData, 1) . '<--' . "\n", FILE_APPEND); //fixme PRINT
die('');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/php_errors.log');
/** Set up WordPress environment */
require_once __DIR__ . '/wp-load.php';

$file = $_SERVER['DOCUMENT_ROOT'] . 'pipedrive_webhook.txt';
$client_secret = '2e540c93ec4e67f8a5af3a2d3df5dbbc';
$pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
$pipedrive_url = 'https://api.pipedrive.com/v1/';

$postJSON = file_get_contents('php://input');
$postData = json_decode($postJSON, true);

file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/log/posterData_' . date('d.m.Y G:i') . '.php', '$postData-->' . print_r($postData, 1) . '<--' . "\n", FILE_APPEND); //fixme PRINT

$verify_original = $postData['verify'];
unset($postData['verify']);

$verify = [
    $postData['account'],
    $postData['object'],
    $postData['object_id'],
    $postData['action'],
];

if (isset($postData['data'])) {
    $verify[] = $postData['data'];
}
$verify[] = $postData['time'];
$verify[] = $client_secret;

$verify = md5(implode(';', $verify));

file_put_contents($file, '1' . PHP_EOL, FILE_APPEND);


// Пошук контакту за номером телефону
$contacts_endpoint = $pipedrive_url . 'persons/find?term=' . urlencode($phone) . '&search_by_phone=1&api_token=' . $pipedrive_api_token;
$response = wp_remote_get($contacts_endpoint);

if (is_wp_error($response)) {
    $error_message = $response->get_error_message();
    error_log("Помилка при з'єднанні з Pipedrive API: " . $error_message);
    return;
} else {
    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    $contact_id = null;

    if (!empty($response_body['data'][0]['id'])) {
        // Контакт знайдений за номером телефону
        $contact_id = $response_body['data'][0]['id'];
    } else {
        // Контакт не знайдений, створення нового контакту в Pipedrive
        $new_contact_data = array(
            'name' => $first_name,
            'phone' => $phone,
            '38bff65393675b482d8f3620964d96eec518a0f8' => 'Сайт',
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
            error_log("Помилка при створенні контакту в Pipedrive: " . $error_message);
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
        error_log("Помилка при створенні угоди в Pipedrive: " . $error_message);
        return;
    }

    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    $deal_id = $response_body['data']['id'];

    error_log("Угода створена в Pipedrive з ID: " . $deal_id);


// Перевіряємо валідність даних
    if ($verify != $verify_original) {
        exit;
    }

    echo json_encode(['status' => 'accept']);
}
