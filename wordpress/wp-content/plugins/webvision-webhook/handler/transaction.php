<?php

namespace Poster\Handler;

use WpOrg\Requests\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

class Transaction
{
    const LOG_FILE_NAME = 'webhook_transaction.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/poster/';
    const POSTER_CLIENT_SECRET = '2e540c93ec4e67f8a5af3a2d3df5dbbc';
    const PIPEDRIVE_API_TOKEN = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    const POSTER_API_TOKEN = '700115:0576459e25fcc87687ae3a1b33142706';
    const PIPEDRIVE_URL = 'https://api.pipedrive.com/v1/';
    const POSTER_URL = 'https://joinposter.com/api/';

    private $postData;
    private $checkData;
    private $checkId = 0;
    private $clientData;
    private $clientId;

    private $prepareData;

    public function __construct($postData)
    {
        $this->postData = $postData;
//        $this->verify(); // fixme розкоментувати коли на прод
    }

    function init()
    {
        if ($this->postData['action'] == 'closed') {
            // цікавлять лише закриті чеки
            $this->addLog('Початок обробки чеку в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id']);
            $this->initCheck();
            $this->initClient();
            $this->prepareDataToPipe();
            $this->checkContactInPipe();
            $this->createDealInPipe();
            $this->addLog('Закінчення обробки чеку в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id'] . "\n\n");
        }
    }

    private function initCheck()
    {
        $this->checkId = $this->postData['object_id'];
        $this->getCheck();
    }

    private function initClient()
    {
        if (empty($this->checkData))
            return [];
        $this->clientId = $this->checkData['client_id'];
        $this->getClientData();
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

    private function getPipeProduct()
    {
        $products_url = self::PIPEDRIVE_URL . 'products?api_token=' . self::PIPEDRIVE_API_TOKEN . "&limit=500";

        $products_response = wp_remote_get($products_url);

        if (is_wp_error($products_response)) {
            error_log('Помилка при отриманні товарів з Pipedrive: ' . $products_response->get_error_message());
            return array();
        }

        $products_response_body = wp_remote_retrieve_body($products_response);
        $productsPipedrive = json_decode($products_response_body, true);
        return $productsPipedrive['data'];
    }

    public function getCheck(): array
    {
        if ($this->checkId <= 0)
            return [];

        $transactionId = $this->checkId;
        $transactionData = [];
        $transactionUrl = self::POSTER_URL . 'dash.getTransaction';
        $transactionUrl .= '?token=' . self::POSTER_API_TOKEN
            . '&transaction_id='.$transactionId
            . '&include_products=true';

        $transaction_response = wp_remote_get($transactionUrl);
        if (is_wp_error($transaction_response)) {
            $this->addLog('getCheck: Не вдалий запит про отримання даних про чек', $transactionUrl);
            throw new \Exception('Не вдалий запит про отримання даних про чек');
        }
        $response_ts = wp_remote_retrieve_body($transaction_response);
        $transaction = json_decode($response_ts, true);
        $transactionArray = current($transaction['response']);
        if (!empty($transactionArray)) {
            $transactionData = $transactionArray;
        } else {
            $this->addLog('Request getCheck: dash.getTransactions data is empty ', $transactionUrl);
            $this->addLog('Response getCheck: dash.getTransactions data is empty ', $transaction);
        }
        $this->checkData = $transactionData;
        return $transactionData;
    }

    private function getClientData()
    {
        if ($this->clientId <= 0)
            return [];

        $client_url = self::POSTER_URL . 'clients.getClient';
        $client_url .= '?token=' . self::POSTER_API_TOKEN
            . '&client_id=' . $this->clientId;

        $clientResponse = wp_remote_get($client_url);
        if (is_wp_error($clientResponse)) {
            $this->addLog('Request getClientData: clients.getClient data is empty ', $client_url);
            throw new \Exception('Не вдалий запит на отримання даних про клієнта');
        }
        $bodyResponse = wp_remote_retrieve_body($clientResponse);
        $clientData = json_decode($bodyResponse, true);
        $this->clientData = current($clientData['response']);
        return current($clientData['response']);
    }

    private function prepareDataToPipe()
    {
        /**
         *
         ** Заказ
         * Сума - payed_sum
         * Магазин - spot_id
         * ід транзакції(можливо вставити у title як у прикладі з магазином)
         * Тип оплати(cash, card, cert) - до речі, судячи з документації тут може бути одночасно кілька оплати, тобто оплата розбита на різні типи.
         * Оплачено - Общая сумма оплаты, от payed_cash и payed_card
         * Дата закритя чеку
         ** Товар
         ** Клієнт
         * ПІБ
         * телефон
         */
        $prepareDate = [];
        $checkData = $this->checkData;
        $clientData = $this->clientData;
        $productsPipedrive = $this->getPipeProduct();

        $products = [];

        foreach ($checkData['products'] as $product) {
            $products[] = [
                'pipedrive_product_id' => $this->searchArrayByFieldValue($productsPipedrive, '375301cd5d99cfacd197fced35c0bc29f2242ec5', $product['product_id']),
                'product_id' => $product['product_id'],
                'product_sum' => ($product['product_price']/100),
                'payed_sum' => $product['payed_sum'],
                'num' => round($product['num']), //Кільість товару в чекові
            ];
        }
        $prepareDate['client'] = [
            'pipedrive_client_id' => '',
            'firstname' => '',
            'lastname' => '',
            'patronymic' => '',
            'client_id' => '',
            'phone' => '',
            'phone_number' => '',
            'full_name' => '',
            'address' => []
        ];
        $prepareDate['check'] = [
            'sum' => $checkData['sum'],
            'service_mode' => $checkData['service_mode'],
            'payed_sum' => $checkData['payed_sum'],
            'spot_id' => $checkData['spot_id'],
            'transaction_id' => $checkData['transaction_id'],
            'client_id' => $checkData['client_id'],
            'pay_type' => $checkData['pay_type'],
            'date_close' => $checkData['date_close_date'],
            'comment'=> $checkData['transaction_comment'],
            'products' => $products
        ];
        if (!empty($clientData)) {
            $address = '';
            if(!empty(end($clientData['addresses'])['address1'])){
                $address = end($clientData['addresses'])['address1'];
            }
            $cleanedNumber = preg_replace('/\D/', '', $clientData['phone_number']);
            $prepareDate['client'] = [
                'firstname' => $clientData['firstname'],
                'lastname' => $clientData['lastname'],
                'patronymic' => $clientData['patronymic'],
                'client_id' => $clientData['client_id'],
                'phone' => $clientData['phone'],
                'phone_number' => $cleanedNumber,
                'address' => $address
            ];
            $prepareDate['client']['full_name'] = implode(' ', [trim($prepareDate['client']['lastname']), trim($prepareDate['client']['firstname']), trim($prepareDate['client']['patronymic'])]);
        }

        $this->prepareData = $prepareDate;
    }

    function searchArrayByFieldValue($array, $field, $value)
    {
        foreach ($array as $element) {
            if ($element[$field] == $value) {
                return $element['id'];
            }
        }

        return null; // Якщо елемент не знайдено
    }

    private function checkContactInPipe()
    {
        $prepareData = $this->prepareData;
        $phone = $prepareData['client']['phone_number'];

        if (!empty($phone)) {
            $contactRequest = self::PIPEDRIVE_URL . 'persons/search?term=' . urlencode($phone) . '&search_by_phone=1&api_token=' . self::PIPEDRIVE_API_TOKEN;
            $response = wp_remote_get($contactRequest);
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                $this->addLog('Request checkContactInPipe: persons/search ', $contactRequest);
                throw new \Exception("Помилка при з'єднанні з Pipedrive API: " . $error_message);
            }
            $contactResponse = json_decode(wp_remote_retrieve_body($response), true);
            if (!empty($contactResponse['data']) && !empty($contactResponse['data']['items'][0])) {
                $itemPerson = current($contactResponse['data']['items']);
                $this->prepareData['client']['pipedrive_client_id'] = $itemPerson['item']['id'];
                return $itemPerson['item']['id'];
            }
        }

        if (empty(trim($prepareData['client']['full_name']))) {
            // контакт не передали
            return 0;
        }
        $new_contact_data = [
            'name' => $prepareData['client']['full_name'],
            'phone' => $phone,
        ];
        $new_contact_endpoint = self::PIPEDRIVE_URL . 'persons?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $responseNewContact = wp_remote_post($new_contact_endpoint, [
            'method' => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($new_contact_data),
        ]);
        if (is_wp_error($responseNewContact)) {
            $error_message = $responseNewContact->get_error_message();
            $this->addLog('Request checkContactInPipe: persons ', $new_contact_data);
            throw new \Exception("Помилка запиту при створенні контакту в Pipedrive: " . $error_message);
        }
        $newContact = json_decode(wp_remote_retrieve_body($responseNewContact), true);
        if (!empty($newContact['data']['id'])) {
            $this->prepareData['client']['pipedrive_client_id'] = $newContact['data']['id'];
            return $newContact['data']['id'];
        } else {
            $this->addLog('Request checkContactInPipe: Контакт не створився в Pipedrive ', $new_contact_data);
            $this->addLog('Response checkContactInPipe: Контакт не створився в Pipedrive ', $newContact);
            throw new \Exception("Контакт не створився в Pipedrive");
        }
    }

    private function createDealInPipe()
    {
        $prepareData = $this->prepareData;
        if($prepareData['check']['transaction_id'] <= 0){
            $this->addLog('Помилка створення угоди. transaction_id <= 0');
            throw new Exception("transaction_id <= 0");
        }
        // Створення угоди в Pipedrive
        $paymentTitle = '';
        $shopTitle = '';
        $timeClose = explode(' ', $prepareData['check']['date_close'])[1];
        $timeCloseExplode = explode(':', $timeClose);
        switch ($prepareData['check']['pay_type']) {
            case 0;
                //закрыт без оплаты
                $paymentTitle = 'Закрите без оплати';
                break;
            case 1;
                //оплата наличным расчётом
                $paymentTitle = 'Оплата готівкою без решти';
                break;
            case 2;
                //оплата безналичным расчётом
                $paymentTitle = 'Оплата термінал';
                break;
            case 3;
                //смешанная оплата
                $paymentTitle = 'Зміщена оплата';
                break;
        }
        switch ($prepareData['check']['spot_id']) {
            case 1:
            {
                $shopTitle = 'Буча Нове Шосе 8а';
                break;
            }
            case 2:
            {
                $shopTitle = 'Вишгород Набережна 2д';
                break;
            }
            case 3:
            {
                $shopTitle = 'Васильків Грушевського 25';
                break;
            }
            case 4:
            {
                $shopTitle = 'Черкаси Смілянська 36';
                break;
            }
            case 5:
            {
                $shopTitle = 'Чайки Лобановського 31';
                break;
            }
            case 6:
            {
                $shopTitle = 'Бровари Героїв України 11';
                break;
            }
            case 7:
            {
                $shopTitle = 'Бровари Київська 253';
                break;
            }
            default:
            {
                $shopTitle = 'Буча Нове Шосе 8а';
                break;
            }
        }
        $typeOrderDelivery = '';
        // // Создает заказ указанного типа: 1 — в заведении, 2 — навынос, 3 — доставка
        // Спосіб отримання //  118:Cамовиніс || 119:Доставка || 129:У магазині
        if(!empty($prepareData['check']['service_mode'])){
            switch($prepareData['check']['service_mode']){
                case 1:
                    $typeOrderDelivery = 'У магазині';
                    break;
                case 2:
                    $typeOrderDelivery = 'Самовиніс';
                    break;
                case 3:
                    $typeOrderDelivery = 'Доставка';
                    break;
            }
        }
        $dataToCreateDeal = [
            'title' => 'Чек №' . $prepareData['check']['transaction_id'] . ', ' . $prepareData['client']['full_name'],
            'value' => $prepareData['check']['transaction_id'],
            'currency' => 'UAH',
            'pipeline_id' => 1, // це лінія Online
            "user_id" => 14818825,
            'status' => 'won', // можливо треба добавити
            '37b3e9059434b03c78e453864a723e0ed8117242' => 1, // Кількість осіб
            '94ebd4ecea470e6c08206f1a4a16fa3c8e26c3a5' => $typeOrderDelivery, // Спосіб отримання old 6658944630026abfc0b89d6fe33881a7ef148b0a
            'f6a43073f5a72a2e65cdd9dfe73cd8d8e6f1e84d' => $paymentTitle, // Оплата : Оплата готівкою без решти || Оплата термінал
//            '5d235e313b0ce48b9f65bf088067d92171707346' => $billing_city, // Місто / Село
            'fbe86ec9ff0fbaaeada151555b6a8508f89baa1a' => 'Ofline', // Джерело
            '32fad1e114d019f0977fee93416030cabd95b3d1' => $shopTitle, // Магазин
            'a7de320a9bd09a2e62dc9337bec6ebbaf229b269' => implode(':', [$timeCloseExplode[0], $timeCloseExplode[1]]), // Час виконання
            'fc4ee5328ab0a1c6d5db36814f00ece0bde334a8' => $prepareData['check']['comment'], // Час виконання
            // Додайте інші необхідні поля для угоди тут
        ];
        if (!empty($prepareData['client']['pipedrive_client_id'])) {
            $dataToCreateDeal['person_id'] = $prepareData['client']['pipedrive_client_id'];
        }
        if (!empty($prepareData['client']['address'])) {
            $dataToCreateDeal['38a3380abae966cc665e69260c73530acaf83856'] = $prepareData['client']['address']; // 38a3380abae966cc665e69260c73530acaf83856 - адреса доставки
        }

        $newDealRequestUrl = self::PIPEDRIVE_URL . 'deals?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $response = wp_remote_post($newDealRequestUrl, [
            'method' => 'POST',
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($dataToCreateDeal),
        ]);

        if (is_wp_error($response)) {
            $this->addLog('Request createDealInPipe: deals ', $dataToCreateDeal);
            $error_message = $response->get_error_message();
            throw new Exception("Помилка при запиті створенні угоди в Pipedrive: " . $error_message);
        }
        $responseNewDeal = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($responseNewDeal['data'])) {
            $this->addLog('Request createDealInPipe: deals ', $dataToCreateDeal);
            $this->addLog('Response createDealInPipe: deals ', $responseNewDeal);
            throw new Exception("Відбулась якась проблема у створенні нової угоди");
        }
        $dealId = $responseNewDeal['data']['id'];
        $this->addLog('createDealInPipe: New Deal ' . $dealId);

//         Прив'язка товарів до угоди
        $deal_products_endpoint = self::PIPEDRIVE_URL . 'deals/' . $dealId . '/products?api_token=' . self::PIPEDRIVE_API_TOKEN;
        $items = $prepareData['check']['products'];
        foreach ($items as $item) {
            $request_data = [
                'product_id' => (int)$item['pipedrive_product_id'],
                'quantity' => (int)$item['num'],
                'item_price' => (int)($item['product_sum'] / $item['num'])
                // Додайте інші необхідні поля для товару тут
            ];

            $response = wp_remote_post($deal_products_endpoint, array(
                'method' => 'POST',
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($request_data),
            ));
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            if (is_wp_error($response)) {
                $this->addLog('Request createDealInPipe: ' . 'deals/' . $dealId . '/products', $request_data);
                $error_message = $response->get_error_message();
                throw new Exception("Помилка при запиті додавання товару до угоди в Pipedrive: " . $error_message);
            }
            if (empty($response_body['data'])) {
                $this->addLog('Request createDealInPipe: ' . 'deals/' . $dealId . '/products', $request_data);
                $this->addLog('Response createDealInPipe: ' . 'deals/' . $dealId . '/products', $response_body);
                throw new Exception("Помилка при додавання товару до угоди в Pipedrive");
            }
        }
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
