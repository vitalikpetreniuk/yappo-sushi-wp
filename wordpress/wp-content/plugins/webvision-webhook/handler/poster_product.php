<?php

namespace Poster\Handler;

use WpOrg\Requests\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

class PosterProduct
{
    const LOG_FILE_NAME = 'webhook_poster_product.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/poster/';
    const POSTER_CLIENT_SECRET = '2e540c93ec4e67f8a5af3a2d3df5dbbc';
    const PIPEDRIVE_API_TOKEN = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    const POSTER_API_TOKEN = '700115:0576459e25fcc87687ae3a1b33142706';
    const PIPEDRIVE_URL = 'https://api.pipedrive.com/v1/';
    const POSTER_URL = 'https://joinposter.com/api/';

    const REMOVE_PRODUCT_TITLE = ' (Видалено)';

    private $postData;
    private $posterProductId;
    private $posterProductData;
    private $prepareData;
    private $pipeProduct;

    public function __construct($postData)
    {
        $this->postData = $postData;
//        $this->verify(); // fixme розкоментувати перед кінечною вигрузкою
    }

    function init()
    {
        $this->addLog('Для Pipedrive: Початок обробки товару в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id']);
        $this->posterProductId = $this->postData['object_id'];
        switch($this->postData['action']){
            case 'added':
            case 'changed':
                // Подія на створеня/оновлення товару
                $this->getPosterProduct();
                $this->prepareData();
                $this->getPipeProduct();
                if(!empty($this->pipeProduct['id'])){
                    // буває таке що запит на оновлення приходить раніше ніж запит на створення
                    // якщо товар знайдений то оновнити
                    $this->updatePipeProduct();
                    $this->addLog('У Pipe був змінений товар');
                }else{
                    // якщо товар не знайдений то створити
                    $this->addPipeProduct();
                    $this->addLog('У Pipe був створений товар');
                }
                break;
            case 'removed':
                $this->getPipeProduct();
                $this->prepareToRemoveData();
                $this->removePipeProduct();
                $this->addLog('У Pipe був змінений товар з поміткою(Видалено)');
                break;
        }
        $this->addLog('Для Pipedrive: Кінець обробки товару в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id']."\n\n");
    }

    private function getPosterProduct(){
        $product_url = self::POSTER_URL . 'menu.getProduct';
        $product_url .= '?token=' . self::POSTER_API_TOKEN
            . '&product_id='.$this->posterProductId;

        $product_response = wp_remote_get($product_url);
        if (is_wp_error($product_response)) {
            $this->addLog('getPosterProduct: Не вдалий запит про отримання даних про товар', $product_url);
            throw new \Exception('Не вдалий запит про отримання даних про товар');
        }
        $response_ts = wp_remote_retrieve_body($product_response);
        $product = json_decode($response_ts, true);
        if(empty($product['response'])){
            $this->addLog('getPosterProduct: Запит не дав відповіді про товар', $product_url);
            throw new \Exception('getPosterProduct: Запит не дав відповіді про товар');
        }
        $this->posterProductData = $product['response'];
    }

    private function verify()
    {
        $postData = $this->postData;
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
        $verify[] = self::POSTER_CLIENT_SECRET;

        // Создаём строку для верификации запроса клиентом
        $verify = md5(implode(';', $verify));

        // Проверяем валидность данных
        if ($verify != $verify_original) {
            $this->addLog('verify: Дані не пройшли валідацію', $postData);
            throw new \Exception('Дані не пройшли валідацію');
        }
    }

    function prepareData()
    {
        // 375301cd5d99cfacd197fced35c0bc29f2242ec5 prop PosterID
        // 426de68b9d5a888f12d95980d67aea84aaafbcae - Category Name
        // 9af8acba7925291abd645693d33817feee070004 - Category Id
        $posterProduct = $this->posterProductData;
        $priceCount = current($posterProduct['price']);
        $price = [
            'price' => $priceCount / 100,
            'currency' => 'UAH',
            'cost' => 0,
            'overhead_cost' => 0,
        ];
        $prices[] = $price;
        $this->prepareData = [
            'name' => $posterProduct['product_name'],
            '375301cd5d99cfacd197fced35c0bc29f2242ec5' => $posterProduct['product_id'],
            '426de68b9d5a888f12d95980d67aea84aaafbcae' => $posterProduct['category_name'],
            '9af8acba7925291abd645693d33817feee070004' => $posterProduct['menu_category_id'],
            'prices' => $prices,
        ];
    }

    function prepareToRemoveData()
    {
        // 375301cd5d99cfacd197fced35c0bc29f2242ec5 prop PosterID
        // 426de68b9d5a888f12d95980d67aea84aaafbcae - Category Name
        // 9af8acba7925291abd645693d33817feee070004 - Category Id
        $pipeProduct = $this->pipeProduct;
        $this->prepareData = [
            'name' => $pipeProduct['name'].self::REMOVE_PRODUCT_TITLE,
            '375301cd5d99cfacd197fced35c0bc29f2242ec5' => $pipeProduct['375301cd5d99cfacd197fced35c0bc29f2242ec5'],
            '426de68b9d5a888f12d95980d67aea84aaafbcae' => $pipeProduct['426de68b9d5a888f12d95980d67aea84aaafbcae'],
            '9af8acba7925291abd645693d33817feee070004' => $pipeProduct['9af8acba7925291abd645693d33817feee070004'],
        ];
    }

    function getPipeProduct(){
        $this->pipeProduct = [];
        $products_url = self::PIPEDRIVE_URL . 'products?api_token=' . self::PIPEDRIVE_API_TOKEN . "&limit=500";
        $products_response = wp_remote_get($products_url);
        if (is_wp_error($products_response)) {
            error_log('Помилка при отриманні товарів з Pipedrive: ' . $products_response->get_error_message());
            return array();
        }
        $products_response_body = wp_remote_retrieve_body($products_response);
        $productsPipedrive = json_decode($products_response_body, true); // $productsPipedrive['data']
        $this->pipeProduct = $this->searchArrayByFieldValue($productsPipedrive['data'], '375301cd5d99cfacd197fced35c0bc29f2242ec5', $this->posterProductId);
    }
    function addPipeProduct()
    {
        if( empty($this->prepareData)){
            $this->addLog("Не вдале створення товару у Pipe", $this->prepareData);
            return false;
        }

        // Створення товару у Pipedrive
        $api_url = self::PIPEDRIVE_URL . 'products?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $dataJson = json_encode($this->prepareData);
        $head = [
            "Content-type: application/json",
            "Accept: application/json",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        if (empty($res['data'])) {
            // error, need log
            $this->addLog("addPipeProduct: Порожня відповідь по створенню товару", $response);
            return false;
        }
        return true;
    }
    function updatePipeProduct()
    {
        if(empty($this->pipeProduct['id']) || empty($this->prepareData)){
            $this->addLog("Не вдале оновлення товару у Pipe. pipeProduct['id'] чи prepareData порожні", [$this->pipeProduct['id'], $this->prepareData]);
            throw new \Exception("Не вдале оновлення товару у Pipe. pipeProduct['id'] чи prepareData порожні");
        }
        // Оновлення товару у Pipedrive
        $api_url = self::PIPEDRIVE_URL . 'products/' . $this->pipeProduct['id'] . '?api_token=' . self::PIPEDRIVE_API_TOKEN;

        $dataJson = json_encode($this->prepareData);
        $head = [
            "Content-type: application/json",
            "Accept: application/json",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        if (empty($res['data'])) {
            // error, need log
            $this->addLog("updatePipeProduct: Порожня відповідь по оновленню товару", $response);
            return false;
        }
        return true;
    }
    function removePipeProduct()
    {
        if(empty($this->pipeProduct['id']) || empty($this->prepareData)){
            $this->addLog("Не вдале оновлення товару у Pipe пілся видалення у Poster", [$this->pipeProduct['id'], $this->prepareData]);
            throw new \Exception("Не вдале оновлення товару у Pipe пілся видалення у Poster");
        }
        // Оновлення товару у Pipedrive
        $api_url = self::PIPEDRIVE_URL . 'products/' . $this->pipeProduct['id'] . '?api_token=' . self::PIPEDRIVE_API_TOKEN;

        $dataJson = json_encode($this->prepareData);
        $head = [
            "Content-type: application/json",
            "Accept: application/json",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        if (empty($res['data'])) {
            // error, need log
            $this->addLog("removePipeProduct: Порожня відповідь по оновленню товару", $response);
            return false;
        }
        return true;
    }
    function searchArrayByFieldValue($array, $field, $value)
    {
        foreach ($array as $element) {
            if ($element[$field] == $value) {
                return $element;
            }
        }

        return null; // Якщо елемент не знайдено
    }
    private function addLog($title, $data = [])
    {
        wp_mkdir_p( $_SERVER['DOCUMENT_ROOT'] . self::LOG_DIR_URL );
        $logPath = $_SERVER['DOCUMENT_ROOT'] . self::LOG_DIR_URL.self::LOG_FILE_NAME;
        if (empty($data)) {
            file_put_contents($logPath, $title . "\n", FILE_APPEND);
        } else {
            file_put_contents($logPath, $title . "\n" . print_r($data, 1) . "\n", FILE_APPEND);
        }

    }
}
