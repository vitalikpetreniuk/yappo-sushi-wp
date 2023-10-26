<?php

namespace Pipedrive;


use WpOrg\Requests\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

class Base
{
    const LOG_FILE_NAME = 'payment_liqpay.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/pipedrive/';
    const PIPEDRIVE_API_TOKEN = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    const PIPEDRIVE_URL = 'https://api.pipedrive.com/v1/';

    private $currentData;
    private $previousData;

    private $dealId;

    private $postData;

    public function __construct($postData)
    {
        $this->postData = $postData;
    }

    function init()
    {
        $this->dealId = $this->postData['meta']['id'];
        $this->currentData = $this->postData['current'];
        $this->previousData = $this->postData['previous'];

        $dealId = $this->dealId;
        $currentData = $this->currentData;
        $previousData = $this->previousData;
        $incomingOrder = $currentData['5d8521bdee2e3df20572a18b05a967395c53a9e9']; // incomingOrders - id онлайн замовлення

        //region Condition for create Poster incomingOrder
        $isStatusWon = ($currentData['status'] == 'won') ? true : false;
        $isStatusChanged = ($currentData['status'] != $previousData['status']) ? true : false;
        $isSourceOffline = ($currentData['fbe86ec9ff0fbaaeada151555b6a8508f89baa1a'] == 101) ? true : false; // fbe86ec9ff0fbaaeada151555b6a8508f89baa1a - source/ Need offline = 101
        $isCreatedIncomingOrder = (!empty($incomingOrder) && intval($incomingOrder) > 0) ? true : false;
        //endregion
        if( ($currentData['stage_id'] == 14) && ($currentData['stage_id'] != $previousData['stage_id'])  ){ // 14 Оплата онлайн
            require_once (__DIR__.'/pipedrive_payment_invoice.php');
            $pipedrivePaymentInvoice = new PaymentInvoice($this->postData);
            $pipedrivePaymentInvoice->init();
        }elseif($isStatusWon && $isStatusChanged && !$isSourceOffline && !$isCreatedIncomingOrder){
            require_once (__DIR__.'/pipedrive2poster_incoming_orders.php');
            $pipedriveToPosterIncomingOrders = new PdToPosterIncomingOrders($this->postData);
            $pipedriveToPosterIncomingOrders->init();
        }
    }
}
