import TableProductTemplate from "./table_product_template";
import TableProductTrigger from "./table_product_trigger";
import TableProductRow from "./table_product_row";

declare var acfw_edit_coupon: any;

/**
 * Table Product class to generate table.
 * - psaeic = Product Stock Availability Exists In Cart
 *
 * @since 3.5.4
 *
 * @page Edit Coupons
 * @section Cart Conditions
 * */
export default class TableProduct {
  /**
   * Property that holds products.
   *
   * @since 3.5.4
   * @access private
   */
  private _products: any = {};

  /**
   * Property that holds Table Product Trigger Class.
   *
   * @since 3.5.4
   * @access private
   */
  private _trigger: any = {};

  /**
   * Property that holds Table Product Template Class.
   *
   * @since 3.5.4
   * @access private
   */
  private _template: any = {};

  /**
   * Property that holds Table Product Row Class.
   *
   * @since 3.5.4
   * @access private
   * */
  private _row: any = {};

  /**
   * Getter for products.
   *
   * @since 3.5.4
   * */
  get products(): any {
    return this._products;
  }

  /**
   * Setter for products.
   *
   * @since 3.5.4
   * */
  set products(value: any) {
    this._products = value;
  }

  /**
   * Getter for template.
   *
   * @since 3.5.4
   * */
  get template(): any {
    return this._template;
  }

  /**
   * Getter for trigger.
   *
   * @since 3.5.4
   * */
  get trigger(): any {
    return this._trigger;
  }

  /**
   * Getter for row.
   *
   * @since 3.5.4
   * */
  get row(): any {
    return this._row;
  }

  /**
   * Build Table Product child class based on the data.
   *
   * @since 3.5.4
   * */
  build() {
    this._row = new TableProductRow();
    this._template = new TableProductTemplate();
    this._trigger = new TableProductTrigger();
    this.save();
  }

  /**
   * Save table product to window object.
   *
   * @since 3.5.4
   * */
  save() {
    (<any>window).acfw_psaeic = {
      ...acfw_edit_coupon.cart_condition_fields
        .acfw_product_stock_availability_exists_in_cart,
      tableproduct: this,
    };
  }
}
