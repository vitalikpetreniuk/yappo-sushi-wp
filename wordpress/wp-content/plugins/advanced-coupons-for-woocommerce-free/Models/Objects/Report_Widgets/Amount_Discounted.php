<?php

namespace ACFWF\Models\Objects\Report_Widgets;

use ACFWF\Abstracts\Abstract_Report_Widget;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Amounts discounted report widget data.
 *
 * @since 4.3
 */
class Amount_Discounted extends Abstract_Report_Widget {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new Report Widget object.
     *
     * @since 4.3
     * @access public
     *
     * @param Date_Period_Range $report_period Date period range object.
     */
    public function __construct( $report_period ) {
        $this->key         = 'amount_discounted';
        $this->widget_name = __( 'Amount Discounted', 'advanced-coupons-for-woocommerce-free' );
        $this->type        = 'big_number';
        $this->description = __( 'Amount Discounted', 'advanced-coupons-for-woocommerce-free' );

        // build report data.
        parent::__construct( $report_period );
    }

    /*
    |--------------------------------------------------------------------------
    | Query methods
    |--------------------------------------------------------------------------
    */

    /**
     * Query report data freshly from the database.
     *
     * @since 4.3
     * @since 4.5.1 Add support for BOGO, Add Products and Shipping overrides discounts.
     * @since 4.5.6 Refactor query so it is valid for HPOS.
     * @access protected
     */
    protected function _query_report_data() {
        $orders         = $this->_query_orders();
        $total_discount = wc_add_number_precision( 0.0 );

        foreach ( $orders as $order ) {
            $coupon_items   = $order->get_coupons();
            $total_discount = array_reduce(
                $coupon_items,
                function( $c, $i ) {
                    $extra_discount = \ACFWF()->Helper_Functions->get_coupon_order_item_extra_discounts( $i );
                    return $c + wc_add_number_precision( $i->get_discount() ) + wc_add_number_precision( $i->get_discount_tax() ) + $extra_discount;
                },
                $total_discount
            );
        }

        $this->raw_data = wc_remove_number_precision( $total_discount );
    }

    /*
    |--------------------------------------------------------------------------
    | Utility methods
    |--------------------------------------------------------------------------
     */

    /**
     * NOTE: This method needs to be override on the child class.
     *
     * @since 4.3
     * @access public
     */
    protected function _format_report_data() {
        $this->title = $this->_format_price( $this->raw_data );
    }
}
