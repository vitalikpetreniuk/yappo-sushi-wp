<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/woocommerce/woocommerce.php';

$posterProducts = getPosterProducts();
$shopProducts = getShopProducts();

if(empty($posterProducts) || empty($shopProducts)){
    die('PosterProducts or ShopProducts is empty');
}

$updated = [];
$created = [];
$start = microtime(true);
foreach ($posterProducts as $posterProduct) {
    $shopProduct = searchArrayByFieldValue($shopProducts, 'post_title', $posterProduct['product_name']);
    if($shopProduct !== null ){
        // update fields and price
        $product = wc_get_product( $shopProduct['post_id'] );
        $priceFull = current($posterProduct['price']);
        $priceCorrect = ($priceFull/100);
        $fields = [
            'poster_category_name'=>$posterProduct['category_name'],
            'poster_category_id'=>$posterProduct['menu_category_id'],
            'poster_product_id'=>$posterProduct['product_id'],
        ];
//        $product->set_regular_price($priceCorrect); // ціну оновлювати не потрібно.
        $product->save();
        foreach ($fields as $key=>$val) {
            update_field($key, $val, $shopProduct['post_id']);
        }
        $updated[] = $shopProduct['post_id'];
    }else{
        // need create product
        $product = new WC_Product();
        // Заповнюємо дані про товар
        $product->set_name($posterProduct['product_name']);
        $priceFull = current($posterProduct['price']);
        $priceCorrect = ($priceFull/100);
        $product->set_regular_price($priceCorrect);
        $product->set_sku('product-'.$posterProduct['product_id']);
        $product->set_status('draft');
        $product->set_catalog_visibility('hidden');

        $shopProductId = $product->save();
        //region uploadFieldsAndPhotos
        if($shopProductId > 0){
            $fields = [
                'poster_category_name'=>$posterProduct['category_name'],
                'poster_category_id'=>$posterProduct['menu_category_id'],
                'poster_product_id'=>$posterProduct['product_id'],
            ];
            foreach ($fields as $key=>$val) {
                update_field($key, $val, $shopProductId);
            }
            if(!empty($posterProduct['photo_origin'])){
                uploadPhotoInProduct($shopProductId, $posterProduct['photo_origin']);
            }
            $created[] = $shopProductId;
        }
        //endregion
    }
}

$timeWorkScript = microtime(true) - $start;
$log = "Оновлено: ".count($updated)."\n";
$log .= "Створено: ".count($created)."\n";
$log .= "Час роботи скрипта: ".$timeWorkScript."\n";

file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/updateShopProduct_'.date('d.m.Y G:i').'.php', $log."\n", FILE_APPEND); //fixme PRINT
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/log/updateShopProduct_arr_'.date('d.m.Y G:i').'.php', '-->'.print_r([
    '$updated'=>$updated,
    '$created'=>$created,
    ],1).'<--'."\n", FILE_APPEND); //fixme PRINT

echo 'Кінець роботи скрипта';
function uploadPhotoInProduct($productId, $photoUrl)
{
    // Отримайте URL фотографії зі стороннього ресурсу
    $url_photo = 'https://joinposter.com'.$photoUrl; // Замініть на ваш URL фотографії
    // Завантажте фотографію і приєднайте її до товару
    $upload_dir = wp_upload_dir(); // Отримайте каталог завантаження WordPress
    $image_data = file_get_contents($url_photo); // Завантажте вміст зображення
    $filename = basename($url_photo); // Отримайте ім'я файлу з URL
    $file_path = $upload_dir['path'] . '/' . $filename; // Складіть повний шлях до файлу
    file_put_contents($file_path, $image_data); // Збережіть зображення на сервері

    // Отримайте тип MIME файлу
    $file_type = wp_check_filetype($file_path)['type'];

    // Підготуйте вкладений файл для приєднання до товару
    $attachment = array(
        'guid'           => $upload_dir['url'] . '/' . $filename,
        'post_mime_type' => $file_type,
        'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Вставте вкладений файл до медіабібліотеки
    $attachment_id = wp_insert_attachment($attachment, $file_path, $productId);

    // Оновіть метадані товару для приєднання зображення
    update_post_meta($productId, '_thumbnail_id', $attachment_id);

}

function searchArrayByFieldValue($array, $field, $value)
{
    foreach ($array as $element) {
        $name1 = trim($element[$field]);
        $name2 = trim($value);
        if (strtolower($name1) == strtolower($name2)) {
            return $element;
        }
    }

    return null; // Якщо елемент не знайдено
}
function getShopProducts(){

    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $products = get_posts($args);
    $result = [];

    foreach ($products as $product) {
        $product_id = $product->ID;

        // Створюємо масив з властивостями товару
        $product_data = [
            'post_id' => $product_id,
            'post_title' => $product->post_title,
        ];
        $result[] = $product_data;
    }

    return $result;
}
function getPosterProducts(){
    // Ваші дані доступу до API Poster
    $poster_api_token = '700115:0576459e25fcc87687ae3a1b33142706';
// URL Poster API для отримання списку товарів
    $api_url = 'https://joinposter.com/api/menu.getProducts?token=' . $poster_api_token;
// Виконання запиту до API Poster
    $ch = curl_init();
    $head = [
        "Content-type: application/json",
        "Accept: application/json",
//    "Authorization: Basic " . base64_encode($LOGIN . ":" . $PASSWORD)
    ];
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_POST, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $productResponse = json_decode($response, true);
    return $productResponse['response'];
}
