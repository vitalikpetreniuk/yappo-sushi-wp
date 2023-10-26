<?php

namespace ACFWP\Abstracts;

use ACFWP\Helpers\Helper_Functions;
use ACFWP\Helpers\Plugin_Constants;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that act as based model.
 *
 * @since 3.5.5
 */
abstract class Base_Model {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 3.5.5
     * @access private
     * @var ACFWP\Models\ChildClass
     */
    protected static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 3.5.5
     * @access private
     * @var Plugin_Constants
     */
    protected $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 3.5.5
     * @access private
     * @var Helper_Functions
     */
    protected $_helper_functions;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 3.5.5
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 3.5.5
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return ACFWP\Models\ChildClass
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! static::$_instance instanceof static ) {
            static::$_instance = new static( $main_plugin, $constants, $helper_functions );
        }

        return static::$_instance;
    }

}
