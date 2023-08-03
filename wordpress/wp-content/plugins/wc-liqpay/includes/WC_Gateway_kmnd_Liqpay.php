<?php

/** payment gateway
 *  class WC_Gateway_kmnd_Liqpay
 */

class WC_Gateway_kmnd_Liqpay extends WC_Payment_Gateway {

    private $_checkout_url = 'https://www.liqpay.ua/api/checkout';
    protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');

    public function __construct() {

            global $woocommerce;
            $this->id = 'liqpay';
            $this->has_fields = false;
            $this->method_title = 'liqPay';
            $this->method_description = __('Payment system LiqPay', 'wc-liqpay');
            $this->init_form_fields();
            $this->init_settings();
            $this->public_key = $this->get_option('public_key');
            $this->private_key = $this->get_option('private_key');
            $this->sandbox = $this->get_option('sandbox');
            $this->connection_status = $this->get_option('connection_status');

            if ($this->get_option('lang') == 'uk/en' && !is_admin()) {
                $this->lang = call_user_func($this->get_option('lang_function'));
                if ($this->lang == 'uk') {
                    $key = 0;
                } else {
                    $key = 1;   
                }

                $array_explode = explode('::', $this->get_option('title'));
                $this->title = $array_explode[$key];
                $array_explode = explode('::', $this->get_option('description'));
                $this->description = $array_explode[$key];
                $array_explode = explode('::', $this->get_option('pay_message'));
                $this->pay_message = $array_explode[$key];

            } else {

                $this->lang = $this->get_option('lang');
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->pay_message = $this->get_option('pay_message');

            }

            $this->icon = $this->get_option('icon');
            $this->status = $this->get_option('status');
            $this->redirect_page = $this->get_option('redirect_page');
            $this->redirect_page_error = $this->get_option('redirect_page_error');
            $this->button = $this->get_option('button');


            add_action('woocommerce_receipt_liqpay', array($this, 'receipt_page')); 
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options')); 
            add_action('woocommerce_api_wc_gateway_' . $this->id, array($this, 'check_ipn_response')); 

            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }

    }

    public function admin_options() { ?>
        <h3><?php esc_html_e('Payment system LiqPay', 'wc-liqpay'); ?></h3>
        <?php if(!empty($this->connection_status) && $this->connection_status !='success') : ?>
            <div class="inline error">
                <p class='warning'><?php esc_html_e('Last returned result is liqpay:', 'wc-liqpay'); ?> 
                    <a href="https://www.liqpay.ua/uk/documentation/api/information/status/doc" 
                    target="_blank" 
                    rel="noopener noreferrer"><?php echo esc_html($this->connection_status);?></a>
                </p>
            </div>
        <?php endif;
            if ( $this->is_valid_for_use() ) : ?>

        <table class="form-table"><?php $this->generate_settings_html(); ?></table>

        <?php  else : ?>
        <div class="inline error">
            <p>
                <strong><?php esc_html_e('Gateway disabled', 'wc-liqpay'); ?></strong>:
                <?php esc_html_e('Liqpay does not support your stores currencies .', 'wc-liqpay'); ?>
            </p>
        </div>
    <?php endif;

    }

    /** 
     * form_fields 
     * */

    public function init_form_fields() {

        $this->form_fields = array(
                'enabled'     => array(
                    'title'   => __('Turn on/Switch off', 'wc-liqpay'),
                    'type'    => 'checkbox',
                    'label'   => __('Turn on', 'wc-liqpay'),
                    'default' => 'yes',
                ),

                'title'       => array(
                    'title'       => __('Heading', 'wc-liqpay'),
                    'type'        => 'textarea',
                    'description' => __('Title that appears on the checkout page', 'wc-liqpay'),
                    'default'     => __('LiqPay'),
                    'desc_tip'    => true,
                ),

                'description' => array(
                    'title'       => __('Description', 'wc-liqpay'),
                    'type'        => 'textarea',
                    'description' => __('Description that appears on the checkout page', 'wc-liqpay'),
                    'default'     => __('Pay using the payment system LiqPay::Pay with LiqPay payment system', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'pay_message' => array(
                    'title'       => __('Message before payment', 'wc-liqpay'),
                    'type'        => 'textarea',
                    'description' => __('Message before payment', 'wc-liqpay'),
                    'default'     => __('Thank you for your order, click the button below to continue::Thank you for your order, click the button'),
                    'desc_tip'    => true,
                ),

                'public_key'  => array(
                    'title'       => __('Public key', 'wc-liqpay'),
                    'type'        => 'text',
                    'description' => __('Public key LiqPay. Required parameter', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'private_key' => array(
                    'title'       => __('Private key', 'wc-liqpay'),
                    'type'        => 'text',
                    'description' => __('Private key LiqPay. Required parameter', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'lang' => array(
                    'title'       => __('Language', 'wc-liqpay'),
                    'type'        => 'select',
                    'default'     => 'uk',
                    'options'     => array('uk'=> __('uk'), 'en'=> __('en')),
                    'description' => __('Interface language (For uk + en install multi-language plugin. Separating languages ​​with :: .)', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'lang_function'     => array(
                    'title'       => __('Language detection function', 'wc-liqpay'),
                    'type'        => 'text',
                    'default'     => 'pll_current_language',
                    'description' => __('The function of determining the language of your plugin', 'wc-liqpay'),
                    'desc_tip'    => true,

                ),

                'icon'     => array(
                    'title'       => __('Logotype', 'wc-liqpay'),
                    'type'        => 'text',
                    'default'     =>  WC_LIQPAY_DIR.'assets/images/logo_liqpay.svg',
                    'description' => __('Full path to the logo, located on the order page', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'button'     => array(
                    'title'       => __('Button', 'wc-liqpay'),
                    'type'        => 'text',
                    'default'     => '',
                    'description' => __('Full path to the image of the button to go to LiqPay', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'status'     => array(
                    'title'       => __('Order status', 'wc-liqpay'),
                    'type'        => 'text',
                    'default'     => 'processing',
                    'description' => __('Order status after successful payment', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'sandbox'     => array(
                    'title'       => __('Test mode', 'wc-liqpay'),
                    'label'       => __('Turn on', 'wc-liqpay'),
                    'type'        => 'checkbox',
                    'description' => __('This mode will help to test the payment without withdrawing funds from the cards', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'redirect_page'     => array(
                    'title'       => __('Redirect page URL', 'wc-liqpay'),
                    'type'        => 'url',
                    'default'     => '',
                    'description' => __('URL page to go to after gateway LiqPay', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),

                'redirect_page_error'     => array(
                    'title'       => __('URL error Payment page', 'wc-liqpay'),
                    'type'        => 'url',
                    'default'     => '',
                    'description' => __('URL page to go to after gateway LiqPay', 'wc-liqpay'),
                    'desc_tip'    => true,
                ),
        );

    }

    function is_valid_for_use() {

        if (!in_array(get_option('woocommerce_currency'), array('RUB', 'UAH', 'USD', 'EUR'))) {
            return false;
        }
        return true;
    }
    
    function process_payment($order_id) {
        $order = new WC_Order($order_id);
        return array(
            'result'   => 'success',
            'redirect' => add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, $order->get_checkout_payment_url(true)))
        );

    }

    public function receipt_page($order) {

        echo '<p>' . esc_html($this->pay_message) . '</p><br/>';
        echo $this->generate_form($order);

    }

    public function generate_form($order_id) {

        global $woocommerce;
        $order = new WC_Order($order_id);
        $result_url = add_query_arg('wc-api', 'wc_gateway_' . $this->id, home_url('/'));
        $currency= get_woocommerce_currency();

        if ($this->sandbox == 'yes') {
                $sandbox = 1;
        } else {
                $sandbox = 0;
        }

        if (trim($this->redirect_page) == '') {
                $redirect_page_url = $order->get_checkout_order_received_url();
        } else {
                $redirect_page_url = trim($this->redirect_page) . '?wc_order_id=' .$order_id;
        }
             
        $html = $this->cnb_form(array(
            'version'     => '3',
            'amount'      => esc_attr($this->get_order_total()),
            'currency'    => esc_attr($currency),
            'description' => esc_attr(__("Payment for order -", "wc-liqpay") . ' ' . $order_id),
            'order_id'    => esc_attr($order_id),
            'result_url'  => esc_url($redirect_page_url),
            'server_url'  => esc_attr($result_url),
            'language'    => $this->lang,
            'sandbox'     => $sandbox

        ));

        return $html;

    }
    
    function check_ipn_response() {

        global $woocommerce;
        $success = isset($_POST['data']) && isset($_POST['signature']);

        if ($success) {

            // is the unique signature of each request
            $received_signature = $this->clean_data($_POST['signature']);             

            // json string parameters encoded by the function base64
            $parsed_data = $this->decode_params( $_POST['data'] );
            $received_public_key = !empty($parsed_data['public_key']) ? $this->clean_data($parsed_data['public_key']) : '';
            $order_id = !empty($parsed_data['order_id']) ? sanitize_key($parsed_data['order_id']) : '';
            $status = !empty($parsed_data['status']) ? sanitize_key($parsed_data['status']) : '';

            // is the generation of a unique signature for each request

            $str_signature = $this->private_key . $this->clean_data($_POST['data']) . $this->private_key;
            $generated_signature = $this->str_to_sign($str_signature);

            // upd status (sanitize the decoded data)

            $this->update_option( 'connection_status', $status );
            // comparison of the keys that are $generated_signature and that were returned to us $received_signature

            if ( $received_signature != $generated_signature || $this->public_key != $received_public_key) { 
                wp_die('IPN Request Failure');

            }

            $order = new WC_Order($order_id);
            if ($status == 'success' || ($status == 'sandbox' && $this->sandbox == 'yes')) {
                $order->update_status($this->status, esc_html__('Order has been paid (payment received)', 'wc-liqpay'));
                $order->add_order_note(esc_html__('The client paid for his order', 'wc-liqpay'));
                $woocommerce->cart->empty_cart();
            } else {

                $order->update_status('failed', esc_html__('Payment has not been received', 'wc-liqpay'));
                wp_redirect($order->get_cancel_order_url());
                exit;
            }

        } else {
                wp_die('IPN Request Failure');
        }
    }

    public function cnb_form($params) {

        $language = !isset($params['language']) ? $language = 'uk' : $params['language'];
        $params    = $this->cnb_params($params);
        $data      = $this->encode_params($params) ;
        $signature = $this->cnb_signature($params);

        if (trim($this->button) == '') {

            $button = '<input type="image" style="width: 160px" src="' . esc_url( WC_LIQPAY_DIR . '/assets/images/LiqPay.png') . '" name="btn_text" />';
        } else {
            $button = '<input type="image" style="width: 160px" src="' . esc_url( $this->button) . '" name="btn_text" />';
        }

        $template = sprintf('
            <div 
            class="load_window_liqpay" 
            style="position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: #f9f9f9;
                opacity: 1;
                z-index: 99999999999;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
            "> <p style="color:#000;">Loading...</p></div>
            <form method="POST" action="%s" id="%s_payment_form" accept-charset="utf-8">
                %s
                %s'. $button . '
            </form>',
                $this->_checkout_url,
                $this->id,
                sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
                sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
                $language
        );

        $skip_script ='<script type="text/javascript">
              jQuery(function() {
                jQuery("#' . $this->id . '_payment_form").submit(); 
              })
            </script>';

        return $template . PHP_EOL . $skip_script;

    }

    private function cnb_params($params) {

        $params['public_key'] = $this->public_key;

        if (!isset($params['version'])) {

            throw new InvalidArgumentException('version is null');
        }

        if (!isset($params['amount'])) {
            throw new InvalidArgumentException('amount is null');
        }

        if (!isset($params['currency'])) {
            throw new InvalidArgumentException('currency is null');
        }

        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new InvalidArgumentException('currency is not supported');
        }

        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }

        if (!isset($params['description'])) {
            throw new InvalidArgumentException('description is null');
        }

        return $params;

    }


    /**
     * cnb_signature
     */

    public function cnb_signature($params) {

        $params      = $this->cnb_params($params);
        $private_key = $this->private_key;
        $json      = $this->encode_params($params );
        $signature = $this->str_to_sign($private_key . $json . $private_key);
        return $signature;
    }
    /**
     * str_to_sign
     */

    public function str_to_sign($str) {
        $signature = base64_encode(sha1($str,1));
        return $signature;
    }
    /**
     * encode_params
     */
    private function encode_params($params){
        return base64_encode(json_encode($params));
    }
    
   /**
    * decode_params
    */

    public function decode_params($params){
        return json_decode(base64_decode($params), true);
    }

    /**
     *  private function to sanitize a string from user input or from the database.
     * 
     * @param string $str String to sanitize.
     * @return string Sanitized string.
     */

    private function clean_data($str){
        if ( is_object( $str ) || is_array( $str ) ) {
            return '';
        }
        $str = (string) $str;
        $filtered = wp_check_invalid_utf8( $str );
        $filtered = trim(preg_replace( '/[\r\n\t ]+/', ' ', $filtered ));
        $filtered = stripslashes($filtered);
        $filtered = htmlspecialchars($filtered);
        return $filtered;
    }
}
