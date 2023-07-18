<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/system_errors.log');
include_once (__DIR__.'/handler/product.php');
include_once (__DIR__.'/handler/transaction.php');
use Poster\Handler\Transaction;
use Poster\Handler\Product;

//$postJSON = file_get_contents('php://input');
//$postData = json_decode($postJSON, true);

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$postData = [
    'account' =>'yappo-sushi',
    'object' =>'product',
    'object_id' =>'318', // test other
    'action' =>'changed', // added, changed, removed
    'time' =>'1687791942',
    'verify' =>'aff2e1e6ad7b62b7807c41c0bec02f9b',
    'account_number' =>'700115',
];


try{
    switch ($postData['object']){
        case 'dish':
        case 'product':
            //Обробка товарів які створились/змінились/видалились
            $posterProduct = new Product($postData);
            $posterProduct->init();
            break;
        case 'transaction':
            //Обробка закритих чеків з Poster
//            $posterTransaction = new Transaction($postData);
//            $posterTransaction->init();
            break;
    }


}
catch (\Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
echo json_encode(['status' => 'accept']);
