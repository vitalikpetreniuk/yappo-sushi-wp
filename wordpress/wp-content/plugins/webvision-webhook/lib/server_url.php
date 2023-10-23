<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once __DIR__ . '/LiqPay.php';


class LiqPayServerUrl
{
    const PIPEDRIVE_API_TOKEN = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    const PIPEDRIVE_URL = 'https://api.pipedrive.com/v1/';

    const LOG_FILE_NAME = 'server_url.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/pipedrive/liqpay/';

    const WV_NAME_PLUGIN_PREFIX = 'wv_integration_';
    private $postData;
    private $privateKey;
    private $publicKey;

    function __construct($postData)
    {
        $this->postData = $postData;
        $this->getOptions();
    }

    private function getOptions()
    {
        $this->privateKey = get_option(self::WV_NAME_PLUGIN_PREFIX . 'private_key');
        $this->publicKey = get_option(self::WV_NAME_PLUGIN_PREFIX . 'public_key');
    }

    public function init()
    {
        $data = $this->postData['data'];
        $signature = $this->postData['signature'];
        $liqpayObj = new LiqPay($this->publicKey, $this->privateKey);
        $arPayment = $liqpayObj->decode_params($data);
        $signatureGenerate = $liqpayObj->str_to_sign($this->privateKey . $data . $this->privateKey);
        if ($signatureGenerate == $signature) {
            // signature is good.
            $dealId = str_replace('deal_id_', '', $arPayment['order_id']);
            $dealArr = explode('_', $dealId);
            $dealId = $dealArr[1];
            // 'f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d' => $payment_method,
            $dataUpdateDealInPipedrive["f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d"] = 'Оплата online'; // Статус оплати
            $dataUpdateDealInPipedrive["6afe6262be2f1cb8065a6e3b0106707a5c752848"] = $arPayment['status']; // Статус оплати
            $dataUpdateDealInPipedrive["8e67b0c2b794cfec5e57b782dd562b0ba501b0c0"] = $arPayment['amount']; // Сума оплати LiqPay
            $dataUpdateDealInPipedrive["status"] = 'won'; // Статус оплати
            $this->updateDealInPipedrive($dealId, $dataUpdateDealInPipedrive);
        } else {
            // signature is not correct
            $this->addLog('Signature is not correct', $this->postData);
        }
    }
    private function updateDealInPipedrive($dealId, $data){
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
