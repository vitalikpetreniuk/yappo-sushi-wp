<?php
function getPipedriveProducts()
{
    $limit = 1000;
// Ваші дані доступу до API Poster
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

    $pipedrive_url = 'https://api.pipedrive.com/v1/';
    $params = ['limit' => $limit];
    $httpBuilder = http_build_query($params);

// URL Pipedrive API для отримання списку товарів
    $api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token . '&' . $httpBuilder;
// 375301cd5d99cfacd197fced35c0bc29f2242ec5 - PosterID
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
    $res = json_decode($response, true);

    $newData = [];
    foreach ($res['data'] as $datum) {
        $newData[$datum['id']] = $datum;
    }

    return $newData;
}

function getPosterProducts()
{
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
    $poster_products = json_decode($response, true);

    $newData = [];
    foreach ($poster_products['response'] as $datum) {
        $newData[$datum['product_id']] = $datum;
    }
    return $newData;
}

function prepareData($dataPipe, $id = 0)
{
    // 375301cd5d99cfacd197fced35c0bc29f2242ec5 prop PosterID
    // 426de68b9d5a888f12d95980d67aea84aaafbcae - Category Name
    // 9af8acba7925291abd645693d33817feee070004 - Category Id
    $curPrice = current($dataPipe['price']);
    $priceCount = ($curPrice>0? $curPrice/100 : $curPrice);
    $price = [
        'price' => $priceCount,
        'currency' => 'UAH',
        'cost' => 0,
        'overhead_cost' => 0,
    ];
    $prices = [];
    $prices[] = $price;
    $newArray = [
        'name' => $dataPipe['product_name'],
        '375301cd5d99cfacd197fced35c0bc29f2242ec5' => $dataPipe['product_id'],
        '426de68b9d5a888f12d95980d67aea84aaafbcae' => $dataPipe['category_name'],
        '9af8acba7925291abd645693d33817feee070004' => $dataPipe['menu_category_id'],
        'prices' => $prices,
    ];
    return $newArray;
}

function createProductPipe($data)
{
    $data = prepareData($data);
    // Ваші дані доступу до API Poster
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    $pipedrive_url = 'https://api.pipedrive.com/v1/';

    // URL Pipedrive API для отримання списку товарів
    $api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token;

    $dataJson = json_encode($data);
    // Виконання запиту до API Poster
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
        return false;
    } elseif (!empty($result['data']['id'])) {
        return true;
    }
}

function updateProductPipe($id, $data)
{
    $data = prepareData($data, $id);
    // Ваші дані доступу до API Poster
    $pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
    $pipedrive_url = 'https://api.pipedrive.com/v1/';
    // URL Pipedrive API для отримання списку товарів
    $api_url = $pipedrive_url . 'products/' . $id . '?api_token=' . $pipedrive_api_token;

    $dataJson = json_encode($data);
    // Виконання запиту до API Poster
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
        return false;
    } elseif (!empty($result['data']['id'])) {
        return true;
    }
}

function filterArrayByProductID($posterArray, $pipeArray)
{
    $updateProductsPipe = [
        'create' => [],
        'update' => [],
    ];
    foreach ($posterArray as $idPoster => $elPoster) {
        $elPoster['product_name'] = trim($elPoster['product_name']);
        $findElement = false;
        foreach ($pipeArray as $idPipe => $elPipe) {
            // 375301cd5d99cfacd197fced35c0bc29f2242ec5 prop PosterID
            // 426de68b9d5a888f12d95980d67aea84aaafbcae - Category Name
            // 9af8acba7925291abd645693d33817feee070004 - Category Id
            if ($elPipe['375301cd5d99cfacd197fced35c0bc29f2242ec5'] == $idPoster) {
                $findElement = true;
                $needUpdate = false;
                if (
                    ($elPoster['product_name'] !== $elPipe['name']) ||
                    ($elPoster['category_name'] !== $elPipe['426de68b9d5a888f12d95980d67aea84aaafbcae']) ||
                    ($elPoster['menu_category_id'] != $elPipe['9af8acba7925291abd645693d33817feee070004'] && $elPoster['menu_category_id'] != 0) ||
                    ( (current($elPoster['price']) / 100) !== current($elPipe['prices'])['price'] )
                ) {
                    $needUpdate = true;
                }

                if ($needUpdate === true) {
                    $updateProductsPipe['update'][$idPipe] = $elPoster;
                }
            }
        }
        if (!$findElement) {
            $updateProductsPipe['create'][] = $elPoster;
        }
    }
    return $updateProductsPipe;
}
