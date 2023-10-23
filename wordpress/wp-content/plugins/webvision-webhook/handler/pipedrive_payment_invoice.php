<?php

namespace Pipedrive;

use WpOrg\Requests\Exception;
use LiqPay;

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once __DIR__ . '/../lib/LiqPay.php';

class PaymentInvoice
{

    const WV_NAME_PLUGIN_PREFIX = 'wv_integration_';
    const LOG_FILE_NAME = 'payment.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/pipedrive/liqpay/';
    const PIPEDRIVE_API_TOKEN = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    const PIPEDRIVE_URL = 'https://api.pipedrive.com/v1/';

    private $currentData;

    private $previousData;

    private $dealId;

    private $postData;

    public function __construct($postData)
    {
        $this->postData = $postData;
        $this->dealId = $postData['meta']['id'];
        $this->currentData = $this->postData['current'];
        $this->previousData = $this->postData['previous'];
    }

    function init()
    {
        $this->addLog('Для Pipedrive: Початок генерації посилання на оплату в ' . date("d.m.Y G:i:s") . ' | Угода №' . $this->dealId);

        $requestData = $this->prepareDataRequest();
        $response = $this->invoice_send($requestData);
        //  - 6afe6262be2f1cb8065a6e3b0106707a5c752848
        // Посилання на оплату - 9556f59534f5a71571687f054d38a2392602e3a8
        $dataUpdateDealInPipedrive["6afe6262be2f1cb8065a6e3b0106707a5c752848"] = $response[0]; // Статус оплати
        $dataUpdateDealInPipedrive["9556f59534f5a71571687f054d38a2392602e3a8"] = $response[1]; // Посилання на оплату
        $this->updateDealInPipedrive($dataUpdateDealInPipedrive);

        $this->addLog('Для Pipedrive: Кінець генерації посилання на оплату в ' . date("d.m.Y G:i:s") . ' | Угода №' . $this->dealId . "\n\n");
    }

    private function prepareDataRequest(): array
    {
        /**
         * Треба отримати:
         * + email;
         * + суму угоди;
         * - список товарів з цінами;
         * + валюту;
         * + id угоди
         */
        $requestData = [];
        $dealData = $this->currentData;
        $defaultEmail = 'test@gmail.com';
        $getDataPerson = $this->getPipeDrivePersonDetail($dealData['person_id']);
        $email = (empty($getDataPerson['primary_email']) ? current($getDataPerson['email'])['value'] : $getDataPerson['primary_email']);
        $email = !empty($email) ? $email : $defaultEmail;
        $requestData['email'] = $email;
        $requestData['totalSum'] = $dealData['value'];
        $requestData['currency'] = $dealData['currency'];
        $requestData['deal_id'] = $dealData['id'];
        $requestData['goods'] = $this->getPipeProducts();
        return $requestData;
    }

    private function invoice_send($requestData)
    {
        $privateKey = get_option(self::WV_NAME_PLUGIN_PREFIX . 'private_key');
        $publicKey = get_option(self::WV_NAME_PLUGIN_PREFIX . 'public_key');
        $result_url = get_option(self::WV_NAME_PLUGIN_PREFIX . 'result_url');
        $server_url = get_option(self::WV_NAME_PLUGIN_PREFIX . 'server_url');  //'http://wp.loc/wp-json/pipedrive/payment/liqpay/result';

        $liqpay = new LiqPay($publicKey, $privateKey);
        $description = 'Description Liqpay paymant ';
        $data = [
            'description' => $description,
            'action' => 'invoice_send',
            'version' => '3',
            'email' => $requestData['email'],
            'amount' => $requestData['totalSum'],
            'currency' => $requestData['currency'],
            'language' => 'uk',
            'order_id' => 'deal_id_'.time().'_'.$requestData['deal_id'],
            'goods' => $requestData['goods'],
            'result_url' => $result_url,
            'server_url' => $server_url,
        ];
        $this->addLog('$data', $data);
        $resObj = $liqpay->api("request",
            $data
        );
        $this->addLog("Відповідь Liqpay", $resObj);
        $result = [];
        if ($resObj->status == 'error') {
            $result = [$resObj->err_code, ''];
        }else{
            $result = [$resObj->status, $resObj->href];
        }
        return $result;
    }

    function getPipeProducts()
    {
        $product_url = self::PIPEDRIVE_URL . 'deals/' . $this->dealId . '/products?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $product_response = wp_remote_get($product_url);
        if (is_wp_error($product_response)) {
            $this->addLog("Помилка при отриманні товарів з Pipedrive: " . $product_response->get_error_message()); // Log
            return array();
        }

        $product_response_body = wp_remote_retrieve_body($product_response);
        $products = json_decode($product_response_body, true);
        $productsForLiqPay = [];

        foreach ($products['data'] as $product) {
            if(empty($product['duration_unit'])){
                $product['duration_unit'] = 'шт.';
            }
            $item = [
                'amount' => $product['item_price'],
                'count' => $product['quantity'],
                'unit' => $product['duration_unit'],
                'name' => $product['name']
            ];
            $productsForLiqPay[] = $item;
        }
        return $productsForLiqPay;
    }

    function getPipeDrivePersonDetail($person_id = '')
    {
        if (empty($person_id))
            return [];

        // URL Pipedrive API
        $person_url = self::PIPEDRIVE_URL . 'persons/' . $person_id . '?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $person_response = wp_remote_get($person_url);
        if (is_wp_error($person_response)) {
            error_log('Помилка при отриманні person з Pipedrive: ' . $person_response->get_error_message());
            return array();
        }
        $person_response_body = wp_remote_retrieve_body($person_response);
        $dataResponse = json_decode($person_response_body, true);
        if ($dataResponse['success']) {
            return $dataResponse['data'];
        }

        return [];
    }

    function updateDealInPipedrive($data){
        $dealId = $this->dealId;
        $deal_url = self::PIPEDRIVE_URL . 'deals/' . $dealId . '?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $response = wp_remote_post($deal_url, array(
            'timeout' => 30, // Максимальний час очікування у секундах
            'method' => 'PUT',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($data), // http_build_query($data) || json_encode($data)
        ));

        if (is_wp_error($response)) {
            // has error
            $this->addLog("Функція updateDealInPipedrive пройшла з помилкою. Угода №$dealId не оновила дані.\nПомилка1 \"". $response->get_error_message()."\"");
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $decodeBody = json_decode($response_body, true);
            $this->addLog('$decodeBody', $decodeBody);
            if( empty($decodeBody['success']) && !empty($decodeBody['error']) ){
                // error
                $this->addLog("Функція updateDealInPipedrive пройшла з помилкою. Угода №$dealId не оновила дані.\nПомилка2 \"". implode("\n", [$decodeBody['error'], $decodeBody['error_info']]) ."\"");
            }
        }
    }

    private function addLog($title, $data = [])
    {
        wp_mkdir_p($_SERVER['DOCUMENT_ROOT'] . self::LOG_DIR_URL);
        $logPath = $_SERVER['DOCUMENT_ROOT'] . self::LOG_DIR_URL . self::LOG_FILE_NAME;
        if (empty($data)) {
            file_put_contents($logPath, $title . "\n", FILE_APPEND);
        } else {
            file_put_contents($logPath, $title . "\n" . print_r($data, 1) . "\n", FILE_APPEND);
        }

    }
}
