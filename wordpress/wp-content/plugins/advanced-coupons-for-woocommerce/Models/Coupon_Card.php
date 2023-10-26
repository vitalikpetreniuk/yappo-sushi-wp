<?php
namespace ACFWP\Models;

use ACFWP\Abstracts\Abstract_Main_Plugin_Class;
use ACFWP\Abstracts\Base_Model;
use ACFWP\Interfaces\Model_Interface;
use ACFWP\Helpers\Plugin_Constants;
use ACFWP\Helpers\Helper_Functions;
use ACFWP\Models\Objects\Advanced_Coupon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Coupon Card module.
 * Public Model.
 *
 * @since 3.5.6
 */
class Coupon_Card extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds coupon card to be displayed on current page.
     *
     * @since 3.5.6
     * @access public
     * @var $coupons array
     */
    public $coupons = array();

    /**
     * Property that holds owned coupon by customer.
     *
     * @since 3.5.6
     * @access public
     * @var $coupons array
     */
    public $owned = array();

    /**
     * Property that holds used or expired coupon by customer.
     *
     * @since 3.5.6
     * @access public
     * @var $coupons array
     */
    public $usedexpired = array();

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Class constructor.
     *
     * @since 3.5.6
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Get default attributes for coupon blocks.
     *
     * @since 3.5.6
     * @access public
     */
    public function get_default_attributes() {
        return array(
            'order_by'          => 'date/desc',
            'columns'           => 3,
            'count'             => 999, // Maximum number of coupons.
            'contentVisibility' => (object) array(
                'discount_value' => true,
                'description'    => true,
                'usage_limit'    => true,
                'schedule'       => true,
            ),
            'isPreview'         => false,
            'className'         => '',
        );
    }

    /**
     * Add coupon card field to coupon General tab.
     *
     * @since 3.5.6
     * @access public
     *
     * @param int        $coupon_id Coupon ID.
     * @param \WC_Coupon $coupon    Coupon data.
     */
    public function display_coupon_card_custom_field( $coupon_id, $coupon ) {
        $meta_name = $this->_constants->SHOW_ON_MY_COUPONS_PAGE;

        // Add coupon show on my coupons page field.
        woocommerce_wp_checkbox(
            array(
                'id'          => $meta_name,
                'value'       => $coupon->get_meta( $meta_name ),
                'class'       => 'toggle-trigger-field',
                'label'       => __( 'Show on my coupons page?', 'advanced-coupons-for-woocommerce' ),
                'description' => __( 'When checked, this will show the coupon in all customers my coupons page', 'advanced-coupons-for-woocommerce' ),
                'desc_tip'    => false,
            )
        );
    }

    /**
     * Save coupon card data.
     *
     * @since 3.5.6
     * @access public
     *
     * @param int                                   $coupon_id Coupon ID.
     * @param \ACFWP\Models\Objects\Advanced_Coupon $coupon Coupon object.
     */
    public function save_coupon_card_field_value( $coupon_id, $coupon ) {
        // Verify WP's nonce to make sure the request is valid before we save ACFW related data.
        $nonce = sanitize_key( $_POST['_wpnonce'] ?? '' );
        if ( ! $nonce || false === wp_verify_nonce( $nonce, 'update-post_' . $coupon_id ) ) {
            return;
        }

        // Save Custom Field - Show on my coupons page.
        $meta_name = $this->_constants->SHOW_ON_MY_COUPONS_PAGE;
        $value     = sanitize_text_field( wp_unslash( $_POST[ $meta_name ] ?? '' ) );
        $coupon->update_meta_data( $meta_name, $value );
    }

    /**
     * Get all coupons available on a page.
     *
     * @since 3.5.6
     * @access public
     *
     * @return array of \ACFWP\Models\Objects\Advanced_Coupon
     */
    public function get_all_coupons_available_on_my_coupons_page() {
        // Query all coupons  page.
        $posts = get_posts(
            array(
                'post_type'      => 'shop_coupon',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'meta_key'       => $this->_constants->SHOW_ON_MY_COUPONS_PAGE,
                'meta_value'     => 'yes',
            )
        );

        // Validate if coupons available on my coupons page.
        $discounts = new \WC_Discounts( \WC()->cart );
        $coupons   = array();
        if ( $posts && ! empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $coupon   = new Advanced_Coupon( $post->ID );
                $is_valid = $discounts->is_coupon_valid( $coupon ); // Check if coupon is valid.
                if (
                    'yes' === get_post_meta( $post->ID, $this->_constants->SHOW_ON_MY_COUPONS_PAGE, true ) && // Check if SHOW_ON_MY_COUPONS_PAGE is set to yes.
                    ! is_wp_error( $is_valid ) // Check if coupon is valid.
                ) {
                    $coupons[ $post->ID ] = $coupon;
                }
            }
        }

        return $coupons;
    }

    /**
     * Get coupon cards markup on current page.
     *
     * @since 3.5.6
     * @access public
     *
     * @param array $block_class List of classnames for the block.
     * @param array $attributes  Block attributes.
     *
     * @return string
     */
    public function get_coupon_cards_markup_on_my_coupons_page( $block_class = '', $attributes = array() ) {
        // Get all coupons in my accounts page if not already loaded.
        if ( empty( $this->coupons ) ) {
            $this->coupons = $this->get_all_coupons_available_on_my_coupons_page();
        }

        // Don't display if coupons is present in owned, used or expired section.
        $coupons = array();
        foreach ( $this->coupons as $coupon ) {
            if ( ! isset( $this->owned[ $coupon->get_id() ] ) && ! isset( $this->usedexpired[ $coupon->get_id() ] ) ) {
                $coupons[] = $coupon;
            }
        }

        // Validate coupons.
        if ( empty( $coupons ) ) {
            return '';
        }

        // Get coupon cards markup.
        ob_start();
        \ACFWF()->Editor_Blocks->load_coupons_list_template(
            $coupons,
            $block_class,
            array_replace_recursive( $this->get_default_attributes(), $attributes )
        );
        return ob_get_clean();
    }

    /**
     * Get coupon cards markup owned by customer.
     *
     * @since 3.5.6
     * @access public
     *
     * @param array $attributes Block attributes.
     *
     * @return string
     */
    public function get_coupon_cards_owned_by_customer( $attributes = array() ) {
        // Merge attributes.
        $attributes = array_replace_recursive( $this->get_default_attributes(), $attributes );

        // Get owned coupons.
        $coupons = \ACFWP()->Editor_Blocks->get_customer_coupons_by_attributes( $attributes );

        // if current user has no coupons, then return an empty string.
        if ( empty( $coupons ) ) {
            return '';
        }

        // Save owned coupons, if it's not used or expired.
        $discounts = new \WC_Discounts( \WC()->cart );
        $owned     = array();
        foreach ( $coupons as $coupon ) {
            $is_valid = $discounts->is_coupon_valid( $coupon ); // Check if coupon is valid.
            if (
                ! isset( $this->usedexpired[ $coupon->get_id() ] ) && // Check if coupon is not used or expired.
                ! is_wp_error( $is_valid ) // Check if coupon is valid.
            ) {
                $owned[ $coupon->get_id() ] = $coupon; // Set owned coupon.
            }
        }
        $this->owned = $owned;

        // If no coupons are owned, then return an empty string.
        if ( empty( $owned ) ) {
            return '';
        }

        // Get coupon cards markup.
        ob_start();
            \ACFWF()->Editor_Blocks->load_coupons_list_template(
                $owned,
                'acfw-coupons-by-customer-block',
                $attributes,
            );
        return ob_get_clean();
    }

    /**
     * Get coupon cards used or expired.
     *
     * @since 3.5.6
     * @access public
     *
     * @param array $attributes Block attributes.
     *
     * @return string
     */
    public function get_coupon_cards_used_or_expired( $attributes = array() ) {
        // Query used and expired coupons.
        $posts = get_posts(
            array(
				'post_type'      => 'shop_coupon',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'usage_count',
						'value'   => 0,
						'compare' => '>',
					),
					array(
						'key'     => 'date_expires',
						'value'   => current_time( 'mysql' ),
						'compare' => '<',
						'type'    => 'DATETIME',
					),
				),
            )
        );

        // Validate posts.
        if ( empty( $posts ) ) {
            return '';
        }

        // Transform posts into coupon instance.
        $usedexpired = array();
        foreach ( $posts as $post ) {
            // Get coupon instance.
            $coupon      = new Advanced_Coupon( $post->ID );
            $get_used_by = array_map( 'intval', $coupon->get_used_by() );

            // Validate coupon.
            if (
                in_array( get_current_user_id(), $get_used_by, true ) || // Check if used coupon is owned by customer.
                $coupon->is_coupon_owned_by_user( get_current_user_id() ) // Check if expired coupon is owned by customer.
            ) {
                $usedexpired[ $post->ID ] = new Advanced_Coupon( $post->ID );
            }
        }
        $this->usedexpired = $usedexpired;

        // If no coupons are used or expired, then return an empty string.
        if ( empty( $usedexpired ) ) {
            return '';
        }

        // Get coupon cards markup.
        ob_start();
            \ACFWF()->Editor_Blocks->load_coupons_list_template(
                $usedexpired,
                '',
                array_replace_recursive( $this->get_default_attributes(), $attributes )
            );
        return ob_get_clean();
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Execute Coupon_Label class.
     *
     * @since 3.5.6
     * @access public
     * @inherit ACFWP\Interfaces\Model_Interface
     */
    public function run() {
        // Custom Fields.
        add_action( 'woocommerce_coupon_options', array( $this, 'display_coupon_card_custom_field' ), 10, 2 ); // Add coupon available field to coupon General tab.
        add_action( 'acfw_before_save_coupon', array( $this, 'save_coupon_card_field_value' ), 10, 2 ); // Save coupon tab data.
    }
}
