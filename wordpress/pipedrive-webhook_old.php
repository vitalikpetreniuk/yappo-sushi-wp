<?php

/** Set up WordPress environment */
require_once __DIR__ . '/wp-load.php';

try {

	$file = $_SERVER['DOCUMENT_ROOT'] . 'pipedrive_webhook.txt';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		header('Access-Control-Allow-Origin: *');
		function dd( $v ) { var_dump($v); exit();}
		function get_pipedrive_products_duplicate() {
			// Ваші дані доступу до API Pipedrive
			$pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';

			// URL Pipedrive API
			$pipedrive_url = 'https://api.pipedrive.com/v1/';

			// URL Pipedrive API для отримання списку товарів
			$api_url = $pipedrive_url . 'products?api_token=' . $pipedrive_api_token;

			// Виконання запиту
			$response = wp_remote_get($api_url);

			// Обробка відповіді
			if (is_wp_error($response)) {
				// Обробка помилки
				error_log('Помилка при отриманні товарів з Pipedrive: ' . $response->get_error_message());
				return array(); // Повернення порожнього масиву у випадку помилки
			}

			// Розпакування та обробка відповіді API Pipedrive
			$response_body = wp_remote_retrieve_body($response);
			$pipedrive_products = json_decode($response_body, true);

			return $pipedrive_products['data'];
		}

		function get_poster_products_duplicate() {
			// Ваші дані доступу до API Poster
			$poster_api_token = '700115:0576459e25fcc87687ae3a1b33142706';

			// URL Poster API для отримання списку товарів
			$api_url = 'https://joinposter.com/api/menu.getProducts?token=' . $poster_api_token;

			// Виконання запиту до API Poster
			$response = wp_remote_get($api_url);

			// Обробка відповіді
			if (is_wp_error($response)) {
				// Обробка помилки
				error_log('Помилка при отриманні товарів з Poster: ' . $response->get_error_message());
				return array(); // Повернення порожнього масиву у випадку помилки
			}

			// Розпакування та обробка відповіді API Poster
			$response_body = wp_remote_retrieve_body($response);
			$poster_products = json_decode($response_body, true);

			return $poster_products['response'];
		}

		function create_product_mapping_duplicate() {
			// Отримання товарів з Pipedrive
			$pipedrive_products = get_pipedrive_products_duplicate();

			// Отримання товарів з Poster
			$poster_products = get_poster_products_duplicate();
			// file_put_contents($file, json_encode(['pipedrive_products' => $pipedrive_products, 'poster_products' => $poster_products]) . PHP_EOL, FILE_APPEND);
			// Мапінг товарів
			$product_mapping = array();

			// Ітеруємося по товарам з Pipedrive
			foreach ($pipedrive_products as $pipedrive_product) {
				// Отримуємо ID товару з Pipedrive
				$pipedrive_product_id = $pipedrive_product['id'];

				// Шукаємо відповідний товар з Poster за певним критерієм (наприклад, назва)
				$matching_product = array_filter($poster_products, function ($poster_product) use ($pipedrive_product) {
					return trim(strtolower($poster_product['product_name'])) === trim(strtolower($pipedrive_product['name']));
				});

				// Перевіряємо, чи знайдено відповідний товар в Poster
				if (!empty($matching_product)) {
					// Отримуємо ID товару з Poster
					$poster_product_id = reset($matching_product)['product_id'];

					// Додаємо відповідність до мапінгу
					$product_mapping[$pipedrive_product_id] = $poster_product_id;
				}
			}

			return $product_mapping;
		}

			
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);

		$deal_id = $data['meta']['id'];
		

		// Токен Pipedrive
		$pipedrive_api_token = 'e952c2d4b8239f6e760ecbe012e341d5aa018adf';
		// Токен Poster
		$poster_api_token = '700115:0576459e25fcc87687ae3a1b33142706';

		// URL Pipedrive API
		$pipedrive_url = 'https://api.pipedrive.com/v1/';
		// URL Poster API
		$poster_url = 'https://joinposter.com/api/';


		// Створення маппігнгу між товарами
		$product_mapping = create_product_mapping_duplicate();
	
		// Отримання інформації про угоду
		$deal_url = $pipedrive_url . 'deals/' . $deal_id . '?api_token=' . $pipedrive_api_token;

		$deal_response = wp_remote_get($deal_url);
		
		if (is_wp_error($deal_response)) {
			error_log('Помилка при отриманні угоди з Pipedrive: ' . $deal_response->get_error_message());
			return array();
		}
		
		$deal_response_body = wp_remote_retrieve_body($deal_response);
		$deal = json_decode($deal_response_body, true);

		$name = $deal['data']['person_id']['name'];
		$phone = $deal['data']['person_id']['phone'][0]['value'];
		$status = $deal['data']['status'];

		$shopKey = '32fad1e114d019f0977fee93416030cabd95b3d1';
		$shop_option_id = $deal['data'][$shopKey];

		$shopObject = null;
		$shopOptionObject = null;


		// Отримання кастомних полів
		$shop_url = $pipedrive_url . 'dealFields?api_token=' . $pipedrive_api_token;

		$shop_response = wp_remote_get($shop_url);

		if (is_wp_error($shop_response)) {
			error_log('Помилка при отриманні кастомних полів з Pipedrive: ' . $shop_response->get_error_message());
			return array();
		}

		$shop_response_body = wp_remote_retrieve_body($shop_response);
		$shop = json_decode($shop_response_body, true);

		// Отримання магазина
		foreach ($shop['data'] as $object) {
			if ($object['key'] === $shopKey) {
				$shopObject = $object;
				break;
			}
		}

		if ($shopObject !== null) {
			foreach ($shopObject['options'] as $option) {
				if ($option['id'] === intval($shop_option_id)) {
				$shopOptionObject = $option;
				break;
				}
			}
		}

		$poster_shop_id = null;

		switch ($shopOptionObject['label']) {
			case 'Буча Нове Шосе 8а': {
				$poster_shop_id = 1;

				break;
			}
			case 'Вишгород Набережна 2д': {
				$poster_shop_id = 2;

				break;
			}
			case 'Васильків Грушевського 25': {
				$poster_shop_id = 3;

				break;
			}
			case 'Черкаси Смілянська 36': {
				$poster_shop_id = 4;

				break;
			}
			case 'Чайки Лобановського 31': {
				$poster_shop_id = 5;

				break;
			}
			case 'Бровари Героїв України 11': {
				$poster_shop_id = 6;

				break;
			}
			case 'Бровари Київська 253': {
				$poster_shop_id = 7;

				break;
			}
			default: {
				$poster_shop_id = 1;

				break;
			}
		}

		// Отримання продуктів
		$product_url = $pipedrive_url . 'deals/' . $deal_id . '/products?api_token=' . $pipedrive_api_token;

		$product_response = wp_remote_get($product_url);

		if (is_wp_error($product_response)) {
			error_log('Помилка при отриманні товарів з Pipedrive: ' . $product_response->get_error_message());
			return array();
		}

		$product_response_body = wp_remote_retrieve_body($product_response);
		$products = json_decode($product_response_body, true);

		$newProductsArray = [];

		$str = '';

		foreach ($products['data'] as $item) {
			$productId = $item['product_id'];
			$count = $item['quantity'];
			$price = $item['item_price'] * 100;

			$poster_product_id = isset($product_mapping[$productId]) ? $product_mapping[$productId] : '';

			$str .= $poster_product_id;

			$newProductsArray[] = [
				'product_id' => $poster_product_id,
				'count' => $count,
				'price' => $price
			];
		}

		// Надсилання запиту в Poster для стоврення онлайн-замовлення, якщо замовлення виграно
		if ($status === 'won') {
			$order_url = $poster_url . 'incomingOrders.createIncomingOrder?token=' . $poster_api_token;
			$order_body = array(
				'spot_id' => $poster_shop_id,
				'first_name' => $name,
				'phone' => $phone,
				'service_mode' => 3,
				'products' => $newProductsArray,
			);
			
			$order_response = wp_remote_post($order_url, array(
				'method' => 'POST',
				'headers' => array('Content-Type' => 'application/json'),
				'body' => json_encode($order_body),
			));

			if (is_wp_error($order_response)) {
				file_put_contents($file, json_encode($order_response) . PHP_EOL, FILE_APPEND);
			} else {
				$response_code = wp_remote_retrieve_response_code($order_response);
				$response_body = wp_remote_retrieve_body($order_response);
					dd($response_body);

				file_put_contents($file, $status . json_encode($order_response) . PHP_EOL, FILE_APPEND);

				http_response_code(200);
			}
			
		}
	}
}
catch (Exception $e) {
	file_put_contents($file, $status . 'Error: ' .json_encode($e->getMessage()) . PHP_EOL, FILE_APPEND);
}

http_response_code(200);