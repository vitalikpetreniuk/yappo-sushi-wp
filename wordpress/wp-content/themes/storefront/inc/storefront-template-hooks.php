<?php
require get_template_directory() . '/inc/theme/banner.php';
require get_template_directory() . '/inc/theme/footer.php';
require get_template_directory() . '/inc/theme/delivery.php';
require get_template_directory() . '/inc/theme/pay.php';
require get_template_directory() . '/inc/theme/about.php';
require get_template_directory() . '/inc/theme/cart.php';
/**
 * Storefront hooks
 *
 * @package storefront
 */

/**
 * General
 *
 * @see  storefront_header_widget_region()
 * @see  storefront_get_sidebar()
 */
add_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );
//add_action( 'woocommerce_archive_description', 'yappo_banner', 0 );
//add_action( 'woocommerce_after_main_content', 'yappo_about', 0 );
//add_action( 'woocommerce_after_main_content', 'yappo_delivery', 0 );
//add_action( 'woocommerce_after_main_content', 'yappo_pay', 10 );
add_action( 'wp', 'bbloomer_remove_default_sorting_storefront' );
add_action( 'woocommerce_widget_shopping_cart_total2', 'woocommerce_widget_shopping_cart_subtotal2', 90 );
if ( ! function_exists( 'woocommerce_widget_shopping_cart_subtotal2' ) ) {
    /**
     * Output to view cart subtotal.
     *
     * @since 3.7.0
     */
    function woocommerce_widget_shopping_cart_subtotal2() {
//        echo '<strong>' . esc_html__( 'Subtotal:', 'woocommerce' ) . '</strong> ' . WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="cart__preview-summery">
        <span>РАЗОМ: </span>
        <p>'. WC()->cart->get_cart_subtotal() .'</p>
    </div>';
    }
}

add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    ?>
    <span id="mini-cart-count">
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <?php
    $fragments['#mini-cart-count'] = ob_get_clean();
    return $fragments;
}

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'UAH': $currency_symbol = 'грн'; break;
    }
    return $currency_symbol;
}

function bbloomer_remove_default_sorting_storefront() {
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
}

/**
 * Header
 *
 * @see  storefront_skip_links()
 * @see  storefront_secondary_navigation()
 * @see  storefront_site_branding()
 * @see  storefront_primary_navigation()
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );
add_filter( 'woocommerce_after_shop_loop', '__return_false' );


add_action( 'storefront_header', 'storefront_skip_links', 5 );
add_action( 'storefront_header', 'storefront_site_branding', 20 );
add_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
add_action( 'storefront_header', 'storefront_header_container_close', 41 );
add_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
add_action( 'storefront_header', 'storefront_primary_navigation', 50 );
add_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );

/**
 * Footer
 *
 * @see  storefront_footer_widgets()
 * @see  storefront_credit()
 */
//add_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
//add_action( 'storefront_footer', 'storefront_credit', 20 );
add_action('yappo_footer', 'yappo_footer', 0);

/**
 * Homepage
 *
 * @see  storefront_homepage_content()
 */
add_action( 'homepage', 'storefront_homepage_content', 10 );

/**
 * Posts
 *
 * @see  storefront_post_header()
 * @see  storefront_post_meta()
 * @see  storefront_post_content()
 * @see  storefront_paging_nav()
 * @see  storefront_single_post_header()
 * @see  storefront_post_nav()
 * @see  storefront_display_comments()
 */
add_action( 'storefront_loop_post', 'storefront_post_header', 10 );
add_action( 'storefront_loop_post', 'storefront_post_content', 30 );
add_action( 'storefront_loop_post', 'storefront_post_taxonomy', 40 );
add_action( 'storefront_loop_after', 'storefront_paging_nav', 10 );
add_action( 'storefront_single_post', 'storefront_post_header', 10 );
add_action( 'storefront_single_post', 'storefront_post_content', 30 );
add_action( 'storefront_single_post_bottom', 'storefront_edit_post_link', 5 );
add_action( 'storefront_single_post_bottom', 'storefront_post_taxonomy', 5 );
add_action( 'storefront_single_post_bottom', 'storefront_post_nav', 10 );
add_action( 'storefront_single_post_bottom', 'storefront_display_comments', 20 );
add_action( 'storefront_post_header_before', 'storefront_post_meta', 10 );
add_action( 'storefront_post_content_before', 'storefront_post_thumbnail', 10 );

/**
 * Pages
 *
 * @see  storefront_page_header()
 * @see  storefront_page_content()
 * @see  storefront_display_comments()
 */
add_action( 'storefront_page', 'storefront_page_header', 10 );
add_action( 'storefront_page', 'storefront_page_content', 20 );
add_action( 'storefront_page', 'storefront_edit_post_link', 30 );
add_action( 'storefront_page_after', 'storefront_display_comments', 10 );

/**
 * Homepage Page Template
 *
 * @see  storefront_homepage_header()
 * @see  storefront_page_content()
 */
add_action( 'storefront_homepage', 'storefront_homepage_header', 10 );
add_action( 'storefront_homepage', 'storefront_page_content', 20 );

function ace_shop_page_add_quantity_field() {

    /** @var WC_Product $product */
    $product = wc_get_product( get_the_ID() );

    if ( ! $product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_purchasable() ) {
        woocommerce_quantity_input( array( 'min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity() ) );
    }

}
add_action( 'yappo_quantity-field', 'ace_shop_page_add_quantity_field', 12 );


/**
 * Add required JavaScript.
 */
add_action( 'woocommerce_after_quantity_input_field', 'bbloomer_display_quantity_plus' );

function bbloomer_display_quantity_plus() {
    echo '<button type="button" class="plus">+</button>';
}

add_action( 'woocommerce_before_quantity_input_field', 'bbloomer_display_quantity_minus' );

function bbloomer_display_quantity_minus() {
    echo '<button type="button" class="minus">-</button>';
}

// -------------
// 2. Trigger update quantity script

add_action( 'wp_footer', 'bbloomer_add_cart_quantity_plus_minus' );

function bbloomer_add_cart_quantity_plus_minus() {

    if ( ! is_product() && ! is_cart() ) return;

    wc_enqueue_js( "

      $(document).on( 'click', '.woocommerce-cart-form__cart-item .quantity button.plus, .woocommerce-cart-form__cart-item .quantity button.minus', function() {
            console.log('click');

         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));

         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
            }
         }
         
         jQuery(\"[name='update_cart']\").trigger('click');

      });

   " );
}

remove_action(
    'woocommerce_before_shop_loop_item',
    'woocommerce_template_loop_product_link_open',
    10
);

remove_action(
    'woocommerce_after_shop_loop_item',
    'woocommerce_template_loop_product_link_close',
    5
);

//add_filter('request', function( $vars ) {
//    global $wpdb;
//    if( ! empty( $vars['pagename'] ) || ! empty( $vars['category_name'] ) || ! empty( $vars['name'] ) || ! empty( $vars['attachment'] ) ) {
//        $slug = ! empty( $vars['pagename'] ) ? $vars['pagename'] : ( ! empty( $vars['name'] ) ? $vars['name'] : ( !empty( $vars['category_name'] ) ? $vars['category_name'] : $vars['attachment'] ) );
//        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT t.term_id FROM $wpdb->terms t LEFT JOIN $wpdb->term_taxonomy tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'product_cat' AND t.slug = %s" ,array( $slug )));
//        if( $exists ){
//            $old_vars = $vars;
//            $vars = array('product_cat' => $slug );
//            if ( !empty( $old_vars['paged'] ) || !empty( $old_vars['page'] ) )
//                $vars['paged'] = ! empty( $old_vars['paged'] ) ? $old_vars['paged'] : $old_vars['page'];
//            if ( !empty( $old_vars['orderby'] ) )
//                $vars['orderby'] = $old_vars['orderby'];
//            if ( !empty( $old_vars['order'] ) )
//                $vars['order'] = $old_vars['order'];
//        }
//    }
//    return $vars;
//});



add_action('woocommerce_before_mini_cart_contents','mini_cart_nonce' );
function mini_cart_nonce(){
    wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce', false );
}

function disable_shipping_calc_on_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );

//add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 20 );
//add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 10 );

add_filter( 'post_limits', 'product_category_archives_limit', 10, 2 );
function product_category_archives_limit( $limit, $query ) {
    if ( is_product_category() ) {
        $limit = 'LIMIT 1000';
    }
    return $limit;
}
add_action( 'wp_ajax_woocommerce_update_cart', 'woocommerce_update_cart' );
add_action( 'wp_ajax_nopriv_woocommerce_update_cart', 'woocommerce_update_cart' );

function woocommerce_update_cart() {
    WC_Form_Handler::update_cart_action();
    wp_send_json( WC_AJAX::get_refreshed_fragments() );
    die();
}

add_filter( 'woocommerce_checkout_fields', 'bbloomer_checkout_phone_us_format', 9999 );

function bbloomer_checkout_phone_us_format( $fields ) {
    $fields['billing']['billing_phone']['placeholder'] = '+380-991-111111';
    $fields['billing']['billing_phone']['maxlength'] = 15;
    $fields['billing']['billing_phone']['custom_attributes']['pattern'] = '.{15,}';
    return $fields;
}

add_action( 'woocommerce_checkout_process', 'bbloomer_checkout_fields_custom_validation' );

function bbloomer_checkout_fields_custom_validation() {
    if ( isset( $_POST['billing_phone'] ) && ! empty( $_POST['billing_phone'] ) ) {
        if ( strlen(str_replace('_', '', $_POST['billing_phone']) ) < 15 ) {
            wc_add_notice( 'Телефон не правильний', 'error' );
        }
    }
}

add_action( 'woocommerce_after_checkout_form', 'bbloomer_phone_mask_us' );

function bbloomer_phone_mask_us() {
    wc_enqueue_js( '$("#billing_phone").inputmask("+38(099)9999999", { greedy: true,
"onincomplete": function(){ $(this).addClass("woocommerce-invalid");},
"oncleared": function(){ $(this).removeClass("woocommerce-invalid");},
"oncomplete": function(){ $(this).removeClass("woocommerce-invalid");}
});');
}
//add_filter( 'Product', 'Товар2', 20, 3 );
function custom_text_replace( $translated_text, $untranslated_text, $domain ) {
    switch ( $untranslated_text ) {
        case 'Product':
            $translated_text = __( 'Замовлення', $domain );
            break;
        case 'Subtotal':
            $translated_text = __( 'Вартість', $domain );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'custom_text_replace', 20, 3 );
function lpac_change_btn_text( $default_text ){
    return 'Визначити поточне місцезнаходження';
}
add_filter( 'lpac_find_location_btn_text', 'lpac_change_btn_text' );
