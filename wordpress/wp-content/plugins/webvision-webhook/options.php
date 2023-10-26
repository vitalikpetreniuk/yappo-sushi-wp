<?php
define('WV_NAME_PLUGIN', 'wv_integration');


/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function wv_integration_settings_init() {
    // Register a new setting for "wporg" page.
    // Register a new section in the "wporg" page.
    add_settings_section(
        WV_NAME_PLUGIN.'_section_developers',
        __( 'Загальні налаштування', WV_NAME_PLUGIN ), WV_NAME_PLUGIN.'_section_developers_callback',
        WV_NAME_PLUGIN
    );

    // Register a new field in the "wporg_section_developers" section, inside the "wporg" page.

    $nameField = 'public_key';
    register_setting( WV_NAME_PLUGIN, WV_NAME_PLUGIN."_$nameField" );
    add_settings_field(
        WV_NAME_PLUGIN. '_' . $nameField, // As of WP 4.6 this value is used only internally.
        __( 'Публічний ключ', WV_NAME_PLUGIN ),
        WV_NAME_PLUGIN.'_text_input_cb',
        WV_NAME_PLUGIN,
        WV_NAME_PLUGIN.'_section_developers',
        [
            'label_for'         => WV_NAME_PLUGIN.'_'.$nameField,
            'class'             => WV_NAME_PLUGIN.'_row',
        ]
    );

    $nameField = 'private_key';
    register_setting( WV_NAME_PLUGIN, WV_NAME_PLUGIN."_$nameField" );
    add_settings_field(
        WV_NAME_PLUGIN. '_' . $nameField, // As of WP 4.6 this value is used only internally.
        __( 'Приватний ключ', WV_NAME_PLUGIN ),
        WV_NAME_PLUGIN.'_password_input_cb',
        WV_NAME_PLUGIN,
        WV_NAME_PLUGIN.'_section_developers',
        [
            'label_for'         => WV_NAME_PLUGIN.'_'.$nameField,
            'class'             => WV_NAME_PLUGIN.'_row',
        ]
    );

    $nameField = 'result_url';
    register_setting( WV_NAME_PLUGIN, WV_NAME_PLUGIN."_$nameField" );
    add_settings_field(
        WV_NAME_PLUGIN. '_' . $nameField, // As of WP 4.6 this value is used only internally.
        __( 'Посилання на сторінку успішної оплати', WV_NAME_PLUGIN ),
        WV_NAME_PLUGIN.'_text_input_cb',
        WV_NAME_PLUGIN,
        WV_NAME_PLUGIN.'_section_developers',
        [
            'label_for'         => WV_NAME_PLUGIN.'_'.$nameField,
            'class'             => WV_NAME_PLUGIN.'_row',
            'default_value'             => 'on'
        ]
    );

    $nameField = 'server_url';
    register_setting( WV_NAME_PLUGIN, WV_NAME_PLUGIN."_$nameField" );
    add_settings_field(
        WV_NAME_PLUGIN. '_' . $nameField, // As of WP 4.6 this value is used only internally.
        __( 'Посилання на сторінку обробки оплати', WV_NAME_PLUGIN ),
        WV_NAME_PLUGIN.'_text_input_cb',
        WV_NAME_PLUGIN,
        WV_NAME_PLUGIN.'_section_developers',
        [
            'label_for'         => WV_NAME_PLUGIN.'_'.$nameField,
            'class'             => WV_NAME_PLUGIN.'_row',
            'default_value'             => 'on'
        ]
    );
}

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', WV_NAME_PLUGIN.'_settings_init' );

/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function wv_integration_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Налаштування для генерації посилання на оплату з Pipedrive', WV_NAME_PLUGIN ); ?></p>
    <?php
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function wv_integration_text_input_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( $args['label_for'] );
    ?>
    <input  id="<?php echo esc_attr( $args['label_for'] ); ?>"
            type="text"
            size="30"
            class = "<?php echo esc_attr( $args['class'] ); ?>"
            name="<?php echo esc_attr( $args['label_for'] ); ?>"
            value="<?=$options?>"
    >
    <?php
}
function wv_integration_password_input_cb( $args ) {
    $options = get_option( $args['label_for'] );
    ?>
    <input  id="<?php echo esc_attr( $args['label_for'] ); ?>"
            type="password"
            size="30"
            class = "<?php echo esc_attr( $args['class'] ); ?>"
            name="<?php echo esc_attr( $args['label_for'] ); ?>"
            value="<?=$options?>"
    >
    <?php
}
function wv_integration_select_input_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( $args['label_for'] );
    if(empty($options)){
        $options = $args['default_value'];
    }
    $optionsValue = $options[$args['label_for']];
    ?>
    <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            class = "<?php echo esc_attr( $args['class'] ); ?>"
            name="wv_integration_sandbox_mode[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="on" <?php echo isset( $optionsValue ) ? ( selected( $optionsValue, 'on', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Включений', WV_NAME_PLUGIN ); ?>
        </option>
        <option value="off" <?php echo isset( $optionsValue ) ? ( selected( $optionsValue, 'off', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Виключений', WV_NAME_PLUGIN ); ?>
        </option>
    </select>
    <?php
}


/**
 * Add the top level menu page.
 */


/**
 * Register our wporg_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', WV_NAME_PLUGIN.'_options_page' );
function wv_integration_options_page() {
    add_submenu_page(
        null,
        'Налаштування LiqPay',
        'WV LiqPay(Pipedrive)',
        'manage_options',
        WV_NAME_PLUGIN,
        WV_NAME_PLUGIN.'_options_liqpay_page_html'
    );
}

/**
 * Top level menu callback function
 */
function wv_integration_options_liqpay_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( WV_NAME_PLUGIN.'_messages', WV_NAME_PLUGIN.'_message', __( 'Налаштування збережено', WV_NAME_PLUGIN ), 'updated' );
    }

    // show error/update messages
    settings_errors( WV_NAME_PLUGIN.'_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( WV_NAME_PLUGIN );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( WV_NAME_PLUGIN );
            // output save settings button
            submit_button( 'Зберегти налаштування' );
            ?>
        </form>
    </div>
    <?php
}

function wv_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wv_integration">Налаштування</a>';
    array_push($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_webvision-webhook/webvision-webhook.php", 'wv_add_settings_link');
