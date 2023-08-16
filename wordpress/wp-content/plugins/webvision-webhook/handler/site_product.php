<?php

namespace Poster\Handler;

use WpOrg\Requests\Exception;
use \WC_Product;

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/woocommerce/woocommerce.php';


class SiteProduct
{
    const LOG_FILE_NAME = 'webhook_shop_product.log';
    const LOG_DIR_URL = '/wp-content/uploads/log/poster/';
    const POSTER_CLIENT_SECRET = '2e540c93ec4e67f8a5af3a2d3df5dbbc';
    const POSTER_API_TOKEN = '700115:0576459e25fcc87687ae3a1b33142706';
    const POSTER_URL = 'https://joinposter.com/api/';
    const POSTER_URL_CLEAR = 'https://joinposter.com';

    private $postData;
    private $posterProductId;
    private $posterProductData;
    private $shopProductId;
    private $shopProduct;

    private $shopProductObj;
    private $prepareData;

    public function __construct($postData)
    {
        $this->postData = $postData;
//        $this->verify(); // перевірка за poster
    }

    function init()
    {
        $this->addLog('Для магазину: початок обробки товару в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id']. '.Action: '.$this->postData['action']);
        $this->posterProductId = $this->postData['object_id'];
        $this->getPosterProduct();
        $this->prepareData();
        $this->getShopProduct();
        switch($this->postData['action']){
            case 'added':
            case 'changed':
                // Подія на оновлення товару
                if(!empty($this->shopProduct)){
                    // буває таке що запит на оновлення приходить раніше ніж запит на створення
                    // якщо товар знайдений то оновнити
                    $this->addLog('У Shop такий товар знайдений, займаємось оновленням');
                    $this->updateShopProduct();
                    $this->addLog('У Shop був змінений товар');
                }else{
                    // якщо товар не знайдений то створити
                    $this->addLog('У Shop такий НЕ знайдений, займаємось створенням');
                    $this->addShopProduct();
                    $this->addLog('У Shop був створений товар');
                }
                break;
            case 'removed':
                $this->deActivateShopProduct();
                $this->addLog('У Shop був деактивований товар з поміткою(Видалено)');
                break;
        }
        $this->addLog('Кінець обробки товару в ' . date("d.m.Y G:i:s") . ' ' . $this->postData['object_id']."\n\n");
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
        $posterProduct = $this->posterProductData;
        $priceCount = current($posterProduct['price']); // ціна у всіх одна тому немає різниці.
        $this->prepareData['fields'] = [
            'poster_product_id' => $posterProduct['product_id'],
            'poster_category_name' => $posterProduct['category_name'],
            'poster_category_id' => $posterProduct['menu_category_id'],
        ];
        $this->prepareData['product'] = [
            'name' => $posterProduct['product_name'],
            'price' => ($priceCount / 100),
            'photo' => $posterProduct['photo'],
            'photo_origin' => $posterProduct['photo_origin'],
        ];
    }

    function uploadPhotoInProduct()
    {
        // Отримайте URL фотографії зі стороннього ресурсу
        $url_photo = self::POSTER_URL_CLEAR.$this->prepareData['product']['photo_origin']; // Замініть на ваш URL фотографії
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
        $attachment_id = wp_insert_attachment($attachment, $file_path, $this->shopProductId);

        // Оновіть метадані товару для приєднання зображення
        update_post_meta($this->shopProductId, '_thumbnail_id', $attachment_id);

    }

    function getShopProduct(){
        // Отримати товар по ID
        $this->shopProductId = 0;
        $this->shopProduct = [];
        $this->shopProductId = $this->getProductByPosterId();

        if($this->shopProductId <= 0){
            $this->shopProductId = $this->getProductBySKU();
        }

        if($this->shopProductId > 0){
            $product = wc_get_product( $this->shopProductId );
            $this->shopProductObj = $product;
            $this->shopProduct = $product->get_data();
        }
    }

    private function getProductByPosterId(){
        $shopProductId = 0;
        $posts = get_posts([
            'post_type'     => 'product',
            'post_status' => ['publish', 'draft', 'private'],
            'meta_key'      => 'poster_product_id',
            'meta_value'    => $this->posterProductId
        ]);
        if(count($posts) >= 1){
            $shopProductId = current($posts)->ID;
        }
        return $shopProductId;
    }

    private function getProductBySKU(){
        $shopProductId  = 0;
        $args = [
            'post_type' => 'product',
            'post_status' => ['publish', 'draft', 'private'],
            'meta_query' => [
                [
                    'key' => '_sku',
                    'value' => $this->posterProductId.'_',
                    'compare' => 'LIKE'
                ]
            ]
        ];
        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            $shopProductId = current($query->get_posts())->ID;
            wp_reset_postdata();
        } else {
            return 0;
        }

        return $shopProductId;
    }

    function updateFieldsAndPhoto()
    {
        foreach ($this->prepareData['fields'] as $key=>$val) {
            update_field($key, $val, $this->shopProductId);
        }
        if(!empty($this->prepareData['product']['photo'])){
            $this->uploadPhotoInProduct();
        }
    }
    function addShopProduct()
    {
        if( empty($this->prepareData)){
            $this->addLog("Не вдале створення товару у shop", $this->prepareData);
            return false;
        }
        $prepareData = $this->prepareData;

        $sku = $this->posterProductId . '_1';

        // Створення товару у Shop
        $product = new WC_Product();
        $productData = $prepareData['product'];
        // Заповнюємо дані про товар
        $product->set_name($productData['name']);
        $product->set_regular_price($productData['price']);
        $product->set_sku($sku);
        $product->set_status('draft');
        $product->set_catalog_visibility('hidden');

        // Зберігаємо товар
        $product_id = $product->save();

        // Виводимо ID створеного товару
        $this->addLog('Товар додано з ID: ' . $product_id);
        $this->shopProductId = $product_id;
        $this->updateFieldsAndPhoto();
        return true;
    }
    function updateShopProduct()
    {
        if(empty($this->shopProduct['id']) || empty($this->prepareData)){
            $this->addLog("Не вдале оновлення товару у магазині", [$this->shopProduct['id'], $this->prepareData]);
            throw new \Exception("Не вдале оновлення товару у магазині");
        }

        // Оновити загальні дані товару
        $product = $this->shopProductObj;
        $prepareDataProduct = $this->prepareData['product'];
        $updateArray = array(
            'ID'           => $this->shopProductId,
            'post_title'   => $prepareDataProduct['name'],
        );
        wp_update_post($updateArray);
        $product->set_regular_price( $prepareDataProduct['price'] ); // Замініть на власну ціну
        $product->set_sale_price(''); // Замініть на власну ціну
        $product->save();
        // Only update FIELDS
        foreach ($this->prepareData['fields'] as $key=>$val) {
            update_field($key, $val, $this->shopProductId);
        }
        return true;
    }
    function deActivateShopProduct()
    {
        if(empty($this->shopProduct['id']) || empty($this->prepareData)){
            $this->addLog("Не вдала деактивація товару у магазині. Товару не знайдено", [$this->shopProduct['id'], $this->prepareData]);
            throw new \Exception("Не вдала деактивація товару у магазині");
        }
        // Отримуємо об'єкт товару за його ID
        $product = wc_get_product($this->shopProductId);
        // Перевіряємо, чи товар існує
        if ($product) {
            // Деактивуємо товар
            $product->set_status('draft');
            // Отримуємо поточну назву товару
            $product_name = $product->get_name();
            // Додаємо слово "видалено" до назви товару
            $updated_product_name = $product_name;
            // Оновлюємо назву товару
            $product->set_name($updated_product_name);
            // Зберігаємо зміни товару
            $product->save();
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
