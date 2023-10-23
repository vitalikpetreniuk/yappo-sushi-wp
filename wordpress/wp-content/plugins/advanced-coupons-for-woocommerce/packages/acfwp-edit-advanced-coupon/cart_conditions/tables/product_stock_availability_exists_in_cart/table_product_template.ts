declare var acfw_edit_coupon: any;
declare var acfw_psaeic: any;

/**
 * Table Product Template class to generate row markup.
 * - psaeic = Product Stock Availability Exists In Cart
 *
 * @since 3.5.4
 *
 * @page Edit Coupons
 * @section Cart Conditions
 * */
export default class TableProductTemplate {
  /**
   * Table placeholder row template. (No categories added)
   * - This is used both in TableCategoryTemplate and TableCategoryRow.
   *
   * @since 3.5.4
   * */
  table_placeholder_row_template(colspan = 4) {
    const { no_products_added } = acfw_edit_coupon;
    return ` 
            <tr class="no-result">
                <td colspan="${colspan}">${no_products_added}</td>
            </tr>
        `;
  }

  /**
   * Generate table markup.
   *
   * @since 3.5.4
   * */
  table_markup() {
    const { add_product_label } = acfw_edit_coupon;
    const { tableproduct } = acfw_psaeic;
    const { products } = tableproduct;
    const { label } = products;

    // Return table markup.
    return `
            <table class="product-stock-availability-exists-in-cart-table acfw-styled-table">
                <thead>
                    <tr>
                        <th class="product">${label.columns.product}</th>
                        <th class="condition">${label.columns.condition}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>
                <tbody>
                    ${(() => {
                      // Return table product row template markup, if product exists.
                      let markup: string = "";
                      if (products && products.data && products.data.length) {
                        for (let product of products.data) {
                          tableproduct.row.data = product;
                          markup += tableproduct.row.template_view_row();
                        }
                      } else
                        markup =
                          tableproduct.template.table_placeholder_row_template(
                            4
                          );
                      return markup;
                    })()}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <a class="psaeic-btn-add-category-row" href="javascript:void(0);" onclick="acfw_psaeic.tableproduct.trigger.trigger_btn_add_category(event)">
                                <i class="dashicons dashicons-plus"></i>
                                ${add_product_label}
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        `;
  }
}
