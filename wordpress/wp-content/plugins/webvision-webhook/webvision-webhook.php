<?php
/**
 * Plugin Name: Webvision webhook
 * Description: WebHook Poster for intergation with Pipedrive and Site
 * Version: 1.0.0
 * Author: Webvision
 */

use Poster\Handler\Transaction;
use Poster\Handler\PosterProduct;
use Poster\Handler\SiteProduct;

add_action('rest_api_init', 'webvision_rest_api_init');

require_once(plugin_dir_path(__FILE__) . 'inc/order.php');

function webvision_rest_api_init()
{
    register_rest_route('webhook/v1', '/poster', [
        'methods' => 'POST',
        'callback' => 'webvision_webhook_endpoint_callback_poster',
        'permission_callback' => 'webvision_webhook_endpoint_callback_poster_permissions_check',
    ]);
    register_rest_route('webhook/v1', 'pipedrive/updateDeal', [
        'methods' => 'POST',
        'callback' => 'webvision_webhook_endpoint_callback_pipedrive',
        'permission_callback' => 'webvision_webhook_endpoint_callback_pipedrive_permissions_check',
    ]);
}

function webvision_webhook_endpoint_callback_poster_permissions_check($request)
{
    $postData = $request->get_json_params();
    $verify_original = $postData['verify'];
    unset($postData['verify']);

    $verify = [
        $postData['account'],
        $postData['object'],
        $postData['object_id'],
        $postData['action'],
    ];

    // Если есть дополнительные параметры
    if (isset($postData['data'])) {
        $verify[] = $postData['data'];
    }
    $verify[] = $postData['time'];
    $verify[] = '2e540c93ec4e67f8a5af3a2d3df5dbbc'; // Poster client secret 2e540c93ec4e67f8a5af3a2d3df5dbbc

    // Создаём строку для верификации запроса клиентом
    $verify = md5(implode(';', $verify));

    // Проверяем валидность данных
    if ($verify != $verify_original) {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/validation_failed_'.date('d.m.Y G:i').'.php', 'validation_failed-->'.print_r('11',1).'<--'."\n", FILE_APPEND); //fixme PRINT
        return false;
    }
    return true;
}

function webvision_webhook_endpoint_callback_pipedrive_permissions_check()
{
    // немає технології перевірки прав
    return true;
}

// Обробка запиту на ендпоінт
function webvision_webhook_endpoint_callback_poster($request)
{
    $postData = $request->get_json_params();
    try {
        switch ($postData['object']) {
            case 'dish':
            case 'product':
                require_once(plugin_dir_path(__FILE__) . 'handler/site_product.php');
                require_once(plugin_dir_path(__FILE__) . 'handler/poster_product.php');
                //Обробка товарів які створились/змінились/видалились
                //ForPoster
                $posterProduct = new PosterProduct($postData);
                $posterProduct->init();

                //For Shop
                $siteProduct = new SiteProduct($postData);
                $siteProduct->init();
                break;
            case 'transaction':
                //Обробка закритих чеків з Poster
                require_once(plugin_dir_path(__FILE__) . 'handler/transaction.php');
                $posterTransaction = new Transaction($postData);
                $posterTransaction->init();
                break;
        }

    } catch (\Exception $e) {
        $message = date('d.m.Y G:i:s').' Poster WebHook caught exception: ' . $e->getMessage() . "\n";
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/poster/exception.log', $message."\n\n", FILE_APPEND); //fixme PRINT
    }
    wp_send_json(['status' => 'accept']);
}

function webvision_webhook_endpoint_callback_pipedrive($request)
{
    global $pipedrive_request;
    $pipedrive_request = $request;
    require_once(plugin_dir_path(__FILE__) . 'inc/pipedrive-webhook.php');
    http_response_code(200);
}




