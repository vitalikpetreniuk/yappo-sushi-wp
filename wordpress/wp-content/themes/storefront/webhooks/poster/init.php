<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/poster/system_errors.log');
include_once (__DIR__.'/handler/product.php');
include_once (__DIR__.'/handler/transaction.php');
use Poster\Handler\Transaction;
use Poster\Handler\Product;

$postJSON = file_get_contents('php://input');
$postData = json_decode($postJSON, true);

try{
    switch ($postData['object']){
        case 'dish':
        case 'product':
//            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/poster/productData_'.date('d.m.Y G:i').'.php', 'posterProductData-->'.print_r($postData,1).'<--'."\n", FILE_APPEND); //fixme PRINT
            //Обробка товарів які створились/змінились/видалились
            $posterProduct = new Product($postData);
            $posterProduct->init();
            break;
        case 'transaction':
            //Обробка закритих чеків з Poster
            $posterTransaction = new Transaction($postData);
            $posterTransaction->init();
            break;
    }

}
catch (\Exception $e){
    error_log('Poster WebHook caught exception: '.  $e->getMessage(). "\n");
}
echo json_encode(['status' => 'accept']);
