<?php
add_filter('woocommerce_add_to_cart_fragments', 'yappo_mini_cart_fragments');

function yappo_mini_cart_fragments($fragments)
{
    ob_start();
    yappo_mini_cart();
    $fragments['.fix-cart'] = ob_get_clean();
    ob_start();
    woocommerce_mini_cart();
    $fragments['.widget_shopping_cart_content'] = ob_get_clean();
    ob_start();
    ?>
  <span class="mini-cart-count <?php if (WC()->cart->get_cart_contents_count()) {
      echo 'mini-cart-count-active';
  } ?>"><?= WC()->cart->get_cart_contents_count() ?></span>
    <?php
    $fragments['.mini-cart-count'] = ob_get_clean();

    return $fragments;
}

function yappo_mini_cart()
{
    ?>
  <div class="fix-cart">
    <div class="cart">
        <span class="mini-cart-count <?php if (WC()->cart->get_cart_contents_count()) {
            echo 'mini-cart-count-active';
        } ?>">
<?= WC()->cart->get_cart_contents_count() ?>
        </span>

      <svg width="26" height="26" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M0.0302734 10.8458C0.0302734 8.7218 1.75208 7 3.87602 7H19.1845C21.3085 7 23.0303 8.7218 23.0303 10.8458V10.8458C23.0303 14.2744 22.7093 17.6955 22.0716 21.0643L22.0319 21.2741C21.6228 23.4354 19.7342 25 17.5345 25H11.5303H5.52603C3.32639 25 1.43779 23.4354 1.02867 21.2741L0.988949 21.0643C0.351232 17.6955 0.0302734 14.2744 0.0302734 10.8458V10.8458Z"
            fill="#ffff"/>
        <path
            d="M7.03027 7V6.5C7.03027 4.01472 9.04499 2 11.5303 2V2C14.0156 2 16.0303 4.01472 16.0303 6.5V7"
            stroke="#ffff" stroke-width="3"/>
      </svg>


      <div class="speech right">
        = <?php echo WC()->cart->get_cart_total(); ?>
      </div>
    </div>

  </div>
    <?php
}

add_filter('woocommerce_product_add_to_cart_text', function ($text, $tthis) {
    return $tthis->is_purchasable() && $tthis->is_in_stock() ? __('Беру', 'yappo') : __('Переглянути', 'yappo');
}, 10, 2);

add_filter('wc_price', function ($return, $price, $args, $unformatted_price, $original_price) {
//	return $return;
    $args = apply_filters(
        'wc_price_args',
        wp_parse_args(
            $args,
            array(
                'ex_tax_label' => false,
                'currency' => '',
                'decimal_separator' => wc_get_price_decimal_separator(),
                'thousand_separator' => wc_get_price_thousand_separator(),
                'decimals' => wc_get_price_decimals(),
                'price_format' => get_woocommerce_price_format(),
            )
        )
    );

    $original_price = $price;

    $unformatted_price = $price;
    $negative = $price < 0;

    /**
     * Filter raw price.
     *
     * @param float $raw_price Raw price.
     * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
     */
    $price = apply_filters('raw_woocommerce_price', $negative ? $price * -1 : $price, $original_price);
    /**
     * Filter formatted price.
     *
     * @param float $formatted_price Formatted price.
     * @param float $price Unformatted price.
     * @param int $decimals Number of decimals.
     * @param string $decimal_separator Decimal separator.
     * @param string $thousand_separator Thousand separator.
     * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
     */
    if (apply_filters('woocommerce_price_trim_zeros', false) && $args['decimals'] > 0) {
        $price = wc_trim_zeros($price);
    }
    $formatted_price = ($negative ? '-' : '') . sprintf($args['price_format'], '<span class="woocommerce-Price-currencySymbol"> <meta itemprop="priceCurrency" content="' . get_woocommerce_currency_symbol($args['currency']) . '">
' . get_woocommerce_currency_symbol($args['currency']) . '</span>', $price);
    $return = $formatted_price;

    return $return;
}, 10, 5);

add_filter('woocommerce_get_price_html', function ($price, $tthis) {
    return '<span itemprop="price">' . $price . '</span>';
}, 10, 2);

add_filter('woocommerce_format_sale_price', function ($price, $regular_price, $sale_price) {
    $price = '<li class="regular-price">' . (is_numeric($regular_price) ? wc_price($regular_price) : $regular_price) . '</li><li class="price">' . (is_numeric($sale_price) ? wc_price($sale_price) : $sale_price) . '</li>';

    return $price;
}, 10, 3);

remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);

function yappo_product_badges($p_id = null)
{
    ?>
  <div class="sale-bage-wrap">
      <?php if (has_term(apply_filters('wpml_object_id', 62, 'product_tag'), 'product_tag', $p_id)) { ?>
        <div class="sale-badge hot-sale">
          <div class="сhat-bubbles-wrap">
            <div class="сhat-bubbles">
              <p>
                  <?php esc_html_e('Гостре', 'yappo'); ?>
              </p>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (has_term(apply_filters('wpml_object_id', 63, 'product_tag'), 'product_tag', $p_id)) { ?>
        <div class="sale-badge vegaterian-sale">
          <div class="сhat-bubbles-wrap">
            <div class="сhat-bubbles">
              <p>
                  <?php esc_html_e('Вегетаріанське', 'yappo'); ?>
              </p>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (has_term(apply_filters('wpml_object_id', 60, 'product_tag'), 'product_tag', $p_id)) { ?>
        <div class="sale-badge new-sale">

          <div class="сhat-bubbles-wrap">
            <div class="сhat-bubbles">
              <p>
                  <?php esc_html_e('Новинки', 'yappo'); ?>
              </p>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (has_term(apply_filters('wpml_object_id', 61, 'product_tag'), 'product_tag', $p_id)) { ?>
        <div class="sale-badge discount-sale">
          <div class="сhat-bubbles-wrap">
            <div class="сhat-bubbles">
              <p>
                  <?php esc_html_e('Акція', 'yappo'); ?>
              </p>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (has_term(apply_filters('wpml_object_id', 59, 'product_tag'), 'product_tag', $p_id)) { ?>
        <div class="sale-badge popular-sale">
          <div class="сhat-bubbles-wrap">
            <div class="сhat-bubbles">
              <p>
                  <?php esc_html_e('Популярне', 'yappo'); ?>
              </p>
            </div>
          </div>
        </div>
      <?php } ?>
  </div>
    <?php
}

function yappo_loadmore_ajax_handler()
{

    wp_reset_query();
    wp_reset_postdata();
    // prepare our arguments for the query
    $params = [];
    if ($_POST['category']) {
        $params['category'] = $_POST['category'];
    }
    $args = call_user_func_array($_POST['func'], $params);

    $paged = intval($_POST['paged']);

    $args['paged'] = $paged;

//	$args['posts_per_page'] = 6;
//	$args['offset']         = $args['posts_per_page'] * $args['paged'] - $args['posts_per_page'];
    // it is always better to use WP_Query but not here
    $query = new WP_Query($args);
    $query->set('paged', $paged);
    ob_start();
    if ($query->have_posts()) :

        // run the loop
        while ($query->have_posts()): $query->the_post();

            wc_get_template_part('content', 'product');


        endwhile;

    endif;
    $content = ob_get_clean();
    $arr = [];
    if ($content) {
        $arr['posts'] = $content;
    }
    wp_send_json_success($arr);
}


add_action('wp_ajax_loadmore', 'yappo_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'yappo_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

function yappo_query_for_new()
{
    return $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug', // Or 'name' or 'term_id'
                'terms' => array('additional'),
                'operator' => 'NOT IN', // Excluded
            ),
            array(
                'taxonomy' => 'product_visibility',
                'terms' => array('exclude-from-catalog'),
                'field' => 'name',
                'operator' => 'NOT IN',
            )
        ),
        'orderby' => array(
            'date' => 'DESC',
            'menu_order' => 'ASC',
        ),
        'post_type' => 'product',
        'posts_per_page' => 6,
        'paged' => 1,
    );
}

function yappo_query_for_popular()
{
    return $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN', // or 'NOT IN' to exclude feature products
            ),
            array(
                'taxonomy' => 'product_visibility',
                'terms' => array('exclude-from-catalog'),
                'field' => 'name',
                'operator' => 'NOT IN',
            )
        ),
        'orderby' => 'date',
        'post_type' => 'product',
        'posts_per_page' => 6,
        'paged' => 1,
    );
}

function yappo_query_for_category($category)
{
    return array(
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $category,
            ),
            array(
                'taxonomy' => 'product_visibility',
                'terms' => array('exclude-from-catalog'),
                'field' => 'name',
                'operator' => 'NOT IN',
            )
        ),
        'post_type' => 'product',
        'posts_per_page' => 6,
        'paged' => 1,
    );
}

function update_item_from_cart()
{
    $cart_item_key = $_POST['cart_item_key'];
    $quantity = $_POST['qty'];

    // Get mini cart
    ob_start();

    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        if ($cart_item_key == $key) {
            WC()->cart->set_quantity($cart_item_key, $quantity, $refresh_totals = true);
        }
    }

    WC_AJAX::get_refreshed_fragments();

    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();
    wp_send_json_success();
}

add_action('wp_ajax_update_item_from_cart', 'update_item_from_cart');
add_action('wp_ajax_nopriv_update_item_from_cart', 'update_item_from_cart');

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper');
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end');

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination');
add_action('woocommerce_after_shop_loop', function () {
    ?>
  <div class="row justify-content-center">
    <div class="col-xxl-1 col-lg-2 col-md-3 col-5">
        <?php
        bootstrap_pagination(); ?>
    </div>
  </div>
    <?php
});

require_once 'checkout.php';
require_once 'single-product.php';

function yappo_current_language()
{
    $lang = apply_filters('wpml_current_language', null);

    return ($lang);
}

add_filter('woocommerce_loop_add_to_cart_args', function ($args) {
    static $i = 1;
    $args['i'] = $i;
    $i++;

    return $args;
});

add_action('init', 'registerCities');

function registerCities()
{
    $cities = getCities();

    /* @var WP_Term $city */
    foreach ($cities as $city) {
//		add_action( 'acf/include_fields', function () use ( $city ) {
//			if ( ! function_exists( 'acf_add_local_field_group' ) ) {
//				return;
//			}
//
//			acf_add_local_field_group( array(
//				'key'                    => 'group_6486f2fbb70b8',
//				'title'                  => 'Блок',
//				'fields'                 => array(
//					array(
//						'key'                 => 'field_649a9670e2b8a',
//						'label'               => 'Категорія для відображення',
//						'name'                => 'category_to_display',
//						'aria-label'          => '',
//						'type'                => 'taxonomy',
//						'instructions'        => '',
//						'required'            => 0,
//						'conditional_logic'   => 0,
//						'wrapper'             => array(
//							'width' => '',
//							'class' => '',
//							'id'    => '',
//						),
//						'wpml_cf_preferences' => 1,
//						'taxonomy'            => 'product_cat',
//						'add_term'            => 0,
//						'save_terms'          => 0,
//						'load_terms'          => 0,
//						'return_format'       => 'id',
//						'field_type'          => 'select',
//						'allow_null'          => 1,
//						'multiple'            => 0,
//					),
//				),
//				'location'               => array(
//					array(
//						array(
//							'param'    => 'post_type',
//							'operator' => '==',
//							'value'    => $city->slug,
//						),
//					),
//				),
//				'acfml_field_group_mode' => 'translation',
//			) );
//		} );

        register_post_type($city->slug, [
            'labels' => [
                'name' => $city->name, // основное название для типа записи
                'singular_name' => $city->name, // название для одной записи этого типа
                'menu_name' => $city->name, // название меню
            ],
            'description' => '',
            'public' => true,
            // 'publicly_queryable'  => null, // зависит от public
            // 'exclude_from_search' => null, // зависит от public
            // 'show_ui'             => null, // зависит от public
            // 'show_in_nav_menus'   => null, // зависит от public
            'show_in_menu' => null,
            // показывать ли в меню админки
            // 'show_in_admin_bar'   => null, // зависит от show_in_menu
            'show_in_rest' => true,
            // добавить в REST API. C WP 4.7
            'rest_base' => null,
            // $post_type. C WP 4.7
            'menu_position' => null,
            'menu_icon' => null,
            //'capability_type'   => 'post',
            //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
            //'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
            'hierarchical' => true,
            'supports' => ['title', 'editor', 'page-attributes'],
            // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
            'taxonomies' => [],
            'has_archive' => false,
            'rewrite' => true,
            'query_var' => true,
        ]);
    }
}

function getCities()
{
    return get_terms(array(
        'taxonomy' => get_locale() == 'uk' ? 'cities' : 'cities_ru',
        'hide_empty' => false
    ));
}

add_action('created_product_cat', 'yappo_create_new_cat_in_cities');

function yappo_create_new_cat_in_cities($category_id)
{
    $term = get_term_by('id', $category_id, 'product_cat');
    $cities = getCities();
    foreach ($cities as $city) {
        wp_insert_post(
            array(
                'post_type' => $city->slug,
                'post_title' => $term->name,
                'post_name' => $term->slug,
            )
        );
    }
}

add_action('delete_product_cat', 'yappo_delete_cat_in_cities', 10, 4);

/**
 * @param int $term Term ID.
 * @param int $tt_id Term taxonomy ID.
 * @param WP_Term $deleted_term Copy of the already-deleted term.
 * @param array $object_ids List of term object IDs.
 *
 * @return void
 */
function yappo_delete_cat_in_cities($term, $tt_id, $deleted_term, $object_ids)
{
    $cities = getCities();
    foreach ($cities as $city) {
        $id = post_exists($city->name, '', '', $city->slug);

        if ($id) {
            wp_delete_post($id);
        }
    }
}

//add_filter( 'home_url', function ( $url, $path, $orig_scheme, $blog_id ) {
//	if ( $orig_scheme === 'rest' ) {
//		return $url;
//	}
//
//	if ( $path === 'relative' ) {
//		return str_replace( 'yappo.ninesquares.studio/', 'yappo.ninesquares.studio/brovary1/', $url );
//	}
//
//	return $url;
//}, 10, 4 );

//add_filter( 'term_link', function ( $termlink, $term, $taxonomy ) {
//	$termlink = str_replace('yappo.ninesquares.studio/', 'yappo.ninesquares.studio/brovary1/', $termlink);
//	return $termlink;
//}, 10, 3 );

function get_product_category_min_max()
{
    // Get the current product category term object
    $term = get_queried_object();

    global $wpdb;

    # Get ALL related products prices related to a specific product category
    $results = $wpdb->get_col("
        SELECT pm.meta_value
        FROM {$wpdb->prefix}term_relationships as tr
        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->prefix}terms as t ON tr.term_taxonomy_id = t.term_id
        INNER JOIN {$wpdb->prefix}postmeta as pm ON tr.object_id = pm.post_id
        WHERE tt.taxonomy LIKE 'product_cat'
        AND t.term_id = {$term->term_id}
        AND pm.meta_key = '_price'
    ");

    // Sorting prices numerically
    sort($results, SORT_NUMERIC);

    $min = current($results);
    $max = end($results);

    return ['min' => $min, 'max' => $max];
}

add_action('woocommerce_before_order_itemmeta', 'storage_location_of_order_items', 10, 3);
function storage_location_of_order_items($item_id, $item, $product)
{
    // Only on backend order edit pages
    if (!(is_admin() && $item->is_type('line_item'))) {
        return;
    }

    // Get your ACF product value (replace the slug by yours below)
    if ($acf_value = get_field('zaklad', $product->get_id())) {
        $acf_label = __('Stored in: ');

        // Outputing the value of the "location storage" for this product item
        echo '<div class="wc-order-item-custom"><strong>' . $acf_value . $acf_label . '</strong></div>';
    }
}

function remove_menus()
{

    $author = wp_get_current_user();
    if (isset($author->roles[0])) {
        $current_role = $author->roles[0];
    } else {
        $current_role = 'no_role';
    }

    if ($current_role == 'shop_manager') {

        remove_menu_page('index.php');                  //Dashboard
        remove_menu_page('edit.php');                   //Posts
        remove_menu_page('upload.php');                 //Media
        remove_menu_page('edit.php?post_type=page');    //Pages
        remove_menu_page('edit.php?post_type=product');    //Pages
        remove_menu_page('edit-comments.php');          //Comments
        remove_menu_page('themes.php');                 //Appearance
        remove_menu_page('plugins.php');                //Plugins
        remove_menu_page('users.php');                  //Users
        remove_menu_page('tools.php');                  //Tools
        remove_menu_page('options-general.php');        //Settings
        remove_menu_page('admin.php?page=wc-admin');        //Settings
        remove_menu_page('admin.php?page=wc-admin');        //Settings
        remove_menu_page('admin.php?page=wpseo_dashboard');
        remove_menu_page('admin.php?page=wpseo-workouts');
        remove_menu_page('admin.php?page=wpseo_workouts');
        remove_menu_page('wpseo-dashboard');
        remove_menu_page('wpseo-workouts');
        remove_menu_page('wpseo_workouts');
        remove_menu_page('wpseo-menu');
        remove_menu_page('wpseo-settings');
        remove_submenu_page('wpseo-menu', 'wpseo-notifications');
        remove_submenu_page('wpseo-menu', 'wpseo-settings');
        remove_submenu_page('wpseo-settings', 'wpseo-general');
        remove_submenu_page('wpseo-settings', 'wpseo-page-settings');
        remove_submenu_page('wpseo-settings', 'wpseo-integrations');
        remove_action('admin_menu', ['WPSEO_Admin_Menu', 'register_settings_page'], 5);
        remove_menu_page('?page=wpseo_dashboard');
        remove_submenu_page('wpseo_dashboard', 'wpseo_dashboard');

        add_filter('woocommerce_admin_disabled', '__return_true');
    } else {
        if (function_exists('acf_add_options_page')) {

            acf_add_options_page();

        }
    }
}

add_action('admin_menu', 'remove_menus', 9999);

// Returns true if user has specific role
function check_user_role($role, $user_id = null)
{
    if (is_numeric($user_id)) {
        $user = get_userdata($user_id);
    } else {
        $user = wp_get_current_user();
    }
    if (empty($user)) {
        return false;
    }

    return in_array($role, (array)$user->roles);
}

// Disable WordPress SEO meta box for all roles other than administrator and seo
function wpse_init()
{
    if (!(check_user_role('shop_manager') || check_user_role('administrator'))) {
        // Remove page analysis columns from post lists, also SEO status on post editor
        add_filter('wpseo_use_page_analysis', '__return_false');
        // Remove Yoast meta boxes
        add_action('add_meta_boxes', 'disable_seo_metabox', 100000);
    }
}

add_action('init', 'wpse_init');

function disable_seo_metabox()
{
    remove_meta_box('wpseo_meta', 'post', 'normal');
    remove_meta_box('wpseo_meta', 'page', 'normal');
}

/**
 * Add the custom fields or the UOM to the prodcut general tab.
 *
 * @since 3.0.0
 */
function wc_uom_product_fields()
{
    echo '<div>';
    woocommerce_wp_text_input(
        array(
            'id' => 'measure_unit',
            'label' => __('Одиниця виміру'),
            'placeholder' => '',
            'desc_tip' => 'true',
            'value' => get_post_meta(get_the_ID(), 'measure_unit', true) ?? 'г',
            'description' => __('Enter your unit of measure for this product here.'),
        )
    );
    echo '</div>';
}

function wc_uom_save_field_input($post_id)
{
    if (isset($_POST['measure_unit'])) :
        $woo_uom_input = sanitize_text_field(wp_unslash($_POST['measure_unit']));
        update_post_meta($post_id, 'measure_unit', esc_attr($woo_uom_input));
    endif;
}

add_action('woocommerce_product_options_shipping_product_data', 'wc_uom_product_fields');
add_action('woocommerce_process_product_meta', 'wc_uom_save_field_input');

add_filter("wpseo_breadcrumb_links", "wpse_100012_override_yoast_breadcrumb_trail");

function wpse_100012_override_yoast_breadcrumb_trail($links)
{
    if (is_product()) {
        global $product;
        $terms = get_the_terms(get_queried_object(), 'product_cat');

        $links[1] = array(
            'url' => get_term_link($terms[0]->term_id, 'product_cat'),
            'text' => $terms[0]->name
        );
    }

    return $links;
}

function product_category_min_max($term)
{

    global $wpdb;

    # Get ALL related products prices related to a specific product category
    $results = $wpdb->get_col("
        SELECT pm.meta_value
        FROM {$wpdb->prefix}term_relationships as tr
        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->prefix}terms as t ON tr.term_taxonomy_id = t.term_id
        INNER JOIN {$wpdb->prefix}postmeta as pm ON tr.object_id = pm.post_id
        WHERE tt.taxonomy LIKE 'product_cat'
        AND t.term_id = $term
        AND pm.meta_key = '_price'
    ");


    // Sorting prices numerically
//    sort($results, SORT_NUMERIC);

    // remove blank price element from array.
    $resultDirect = array_filter($results);
// min and max value from array
    $min = min($resultDirect);
    $max = max($resultDirect);

    return ['min' => $min, 'max' => $max];
}

const FILTERED_TAXONOMIES = [
    'pa_ingredients',
    'product_tag'
];

//add_action('pre_get_posts', function ($q) {
add_action('woocommerce_product_query', 'yappo_product_query', 10, 2);

function yappo_product_query($q) {

    switch ($_GET['orderby']) {
        case 'popularity':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'total_sales';
            break;
        case 'low_to_high':
            $meta_key = '_price';
            $order = 'asc';
            $orderby = 'meta_value_num';
            break;
        case 'high_to_low':
            $meta_key = '_price';
            $order = 'desc';
            $orderby = 'meta_value_num';
            break;
        case 'newness':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'date';
            break;
        case 'rating':
            $meta_key = '';
            $order = 'desc';
            $orderby = 'rating';
            break;
        default:
            $meta_key = '';
            $order = 'asc';
            $orderby = 'menu_order title';
            break;
    }

    $q->set('order', $order);
    $q->set('orderby', $orderby);
    $q->set('meta_key', $meta_key);
    $tax_query = $q->get('tax_query');
    $meta_query = $q->get('meta_query');

    if (isset($_GET['pa_ingredients'])) {
        $tax_query[] = [
            'taxonomy' => 'pa_ingredients',
            'field' => 'slug',
            'terms' => $_GET['pa_ingredients'],
        ];
    }

    if (isset($_GET['product_tag'])) {
        $tax_query[] = [
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $_GET['product_tag'],
        ];
    }

    if (isset($_GET['min_price'], $_GET['max_price'])) {
        $meta_query = array(
            'relation' => 'AND',
            'min_price' => [
                'key' => '_price',
                'compare' => '>=',
                'type' => 'numeric',
                'value' => $_GET['min_price']
            ],
            'max_price' => [
                'key' => '_price',
                'compare' => '<=',
                'type' => 'numeric',
                'value' => $_GET['max_price']
            ]
        );
    }


    $q->set('tax_query', $tax_query);
    $q->set('meta_query', $meta_query);

    return $q;
}

add_action('pre_get_posts', function ($q) {
    if($q->get('yappo_filter')) {
        $q = yappo_product_query($q);
    }
    return $q;
});
