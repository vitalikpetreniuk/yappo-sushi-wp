<?php
namespace ACFWP\Helpers\Functions;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trait that houses all the helper functions specificly for product category.
 *
 * @since 3.5.5
 */
trait Product_Category {

    /**
     * Get WooCommerce Product Categories
     *
     * @since 3.5.5
     * @access public
     */
    public function get_product_categories() {
        $product_categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
        $product_categories = wp_list_pluck( $product_categories, 'name', 'term_id' );
        asort( $product_categories, SORT_STRING );

        return $product_categories;
    }

}
