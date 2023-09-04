<?php
ini_set('memory_limit', '128M');

//Призначення скрипту полягає у регулярному оновленні товарів
if (!$_SERVER["DOCUMENT_ROOT"]) {
    $f_info = pathinfo(__FILE__);
    $ar_parts_path = explode("/",$f_info["dirname"]);
    $path_root = "";
    $level_depth = 3; //fixme
    for ($i = 1; $i < count($ar_parts_path) - $level_depth; $i++) {
        $path_root .= "/".$ar_parts_path[$i];
    }
    $_SERVER["DOCUMENT_ROOT"] = $path_root;

    $_SERVER["DOCUMENT_ROOT"] = '/home/vr488025/yapposushi.com/www/';
}

include_once (__DIR__.'/add_function_cron.php');
$pipeProducts = getPipedriveProducts();
$posterProducts = getPosterProducts();
$updateProducts = [];
$filteredArray = filterArrayByProductID($posterProducts, $pipeProducts);
$fileDir = $_SERVER["DOCUMENT_ROOT"].'/wp-content/uploads/log/pipedrive/';
$fileLogName = $fileDir.'update_pipe_products_statistic.log';
$fileLogProducts = $fileDir.'update_pipe_products_fail.log';
file_put_contents($fileLogProducts, '');
$logs['success']=[
    'create'=> 0,
    'update'=> 0,
];
$logs['fail']=[
    'create'=> 0,
    'update'=> 0,
];

foreach ($filteredArray as $key=>$category) {
    if(!in_array($key, ['create', 'update'])){
        continue;
    }
    foreach ($category as $idPipeProduct=>$product) {
        switch ($key){
            case 'create':
                $response = createProductPipe($product);
                if($response){
                    $logs['success'][$key]++;
                }else{
                    $logs['fail'][$key]++;
                    $logs['fail_products_create'][] = $product;
                }
                break;
            case 'update':
                $response = updateProductPipe($idPipeProduct,$product);
                if($response){
                    $logs['success'][$key]++;
                }else{
                    $logs['fail'][$key]++;
                    $logs['fail_products_update'][] = $product;
                }
                break;
        }
    }
}
$message = "Статистика оновлення товарів - ".date('d.m.Y G:i')."\n";
$message .= "Успішно створено: ".$logs['success']['create']."\n";
$message .= "Неусіпішно створено: ".$logs['fail']['create']."\n";
$message .= "Успішно оновлено: ".$logs['success']['update']."\n";
$message .= "Неусіпішно створено: ".$logs['fail']['update']."\n";
file_put_contents($fileLogName, $message);
file_put_contents($fileLogProducts, 'fail_products_create-->'.print_r($logs['fail_products_create'],1).'<--'."\n", FILE_APPEND);
file_put_contents($fileLogProducts, 'fail_products_update-->'.print_r($logs['fail_products_update'],1).'<--'."\n", FILE_APPEND);
