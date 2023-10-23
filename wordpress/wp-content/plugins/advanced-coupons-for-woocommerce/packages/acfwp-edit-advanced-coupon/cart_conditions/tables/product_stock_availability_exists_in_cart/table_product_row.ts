import { esc_attr } from "../../../helper";

declare var acfw_edit_coupon: any;
declare var acfw_psaeic: any;
declare var jQuery: any;
const $: any = jQuery;
declare var vex: any;

/**
 * Element interface to store _element in an object.
 *
 * @since 3.5.4
 * */
interface Element {
  $button: JQuery; // Current clicked button (Add Category, Add, Edit, Remove, Cancel)
  $table: JQuery; // Closest table
  $tbody: JQuery; // Closest table body
  $row: JQuery; // Closest row
  $product: JQuery; // Closest select (product)
  $condition: JQuery; // Closest select (condition)
  colspan: number; // Colspan of the table
  data: any; // Data _element that is stored in data-* attribute
}

/**
 * Data interface that is being used by the row (product, condition)
 *
 * @since 3.5.4
 * */
interface Data {
  product: {
    id: number;
    label: string;
  };
  condition: {
    id: string;
    label: string;
  };
}

/**
 * Table Product Row class to generate row.
 * - This class is used to generate row for the table.
 *
 * @since 3.5.4
 *
 * @page Edit Coupons
 * @section Cart Conditions
 * */
export default class TableProductRow {
  /**
   * Element in row.
   *
   * @since 3.5.4
   * */
  private _element: Element = {
    $button: $(null),
    $table: $(this),
    $tbody: $(this),
    $row: $(this),
    $product: $(this),
    $condition: $(this),
    colspan: 4,
    data: [],
  };

  /**
   * Data in row.
   *
   * @since 3.5.4
   * */
  private _data: Data;

  /**
   * Constructor.
   *
   * @since 3.5.4
   * */
  constructor() {
    this._data = this.data_initial();
  }

  /**
   * Getter for element.
   *
   * @since 3.5.4
   * */
  get element(): Element {
    return this._element;
  }

  /**
   * Setter for element.
   *
   * @since 3.5.4
   * */
  set element(value: Element) {
    this._element = value;
  }

  /**
   * Getter for data.
   *
   * @since 3.5.4
   * */
  get data(): Data {
    return this._data;
  }

  /**
   * Get Initial Data for re-initiate purpose.
   *
   * @since 3.5.4
   * */
  data_initial(): Data {
    return {
      product: {
        id: 0,
        label: "",
      },
      condition: {
        id: "",
        label: "",
      },
    };
  }

  /**
   * Setter for data.
   *
   * @since 3.5.4
   * */
  set data(value: Data) {
    this._data = value;
  }

  /**
   * Template initiate select2
   *
   * @since 3.5.4
   * */
  template_initiate_select2() {
    this._element.$tbody.find("select.wc-product-search").trigger("change");
    $("body").trigger("wc-enhanced-select-init"); // Re-init select2
  }

  /**
   * Add edit row template.
   * - In table product there's two of type of row (Add/Edit Row and View Row)
   *
   * @since 3.5.4
   * */
  template_add_edit_row() {
    const { tableproduct } = acfw_psaeic;
    const { products } = tableproduct;
    const { edit, add, cancel } = acfw_edit_coupon.product_table_buttons;
    const { type_to_search } = acfw_edit_coupon.bogo_form_fields;

    /**
     * Return add edit row template markup.
     *
     * Note:
     * `psaeic-` prefix class is abbreviation of "Has Ordered Product Categories Before"
     * The abbreviation is used because there's trigger class with the same name. ex (.action, etc)
     * */
    return `
            <tr class="add-edit-form adding">
                <td class="product" data-product="${
                  typeof this._data == "object"
                    ? esc_attr(JSON.stringify(this._data))
                    : 0
                }">
                    <div class="object-search-wrap">
                        <select class="psaeic-select-product wc-product-search" data-placeholder="${type_to_search}" data-action="acfwp_add_products_search">
                            ${
                              typeof this._data == "object" &&
                              this._data.product.id
                                ? `<option value="${this._data.product.id}" selected>${this._data.product.label}</option>`
                                : ""
                            }
                        </select>                        
                    </div>
                </td>
                <td class="condition">                
                    <select class="condition-select psaeic-select-condition">
                        ${Object.keys(products.options.conditions)
                          .map((value: any) => {
                            let label = products.options.conditions[value];
                            let selected =
                              typeof this._data == "object" &&
                              this._data.condition.id == value
                                ? "selected"
                                : "";
                            return `<option value="${value}" ${selected}>${label}</option>`;
                          })
                          .join("")}
                    </select> 
                </td>
                <td class="actions">
                    <button type="button" class="button-primary psaeic-btn-add" onclick="acfw_psaeic.tableproduct.trigger.trigger_btn_action_add(event)">${
                      typeof this._data == "object" && this._data.product.id
                        ? edit
                        : add
                    }</button>
                    <button type="button" class="button psaeic-btn-cancel" onclick="acfw_psaeic.tableproduct.trigger.trigger_btn_action_cancel(event)">${cancel}</button>
                </td>
            </tr>
        `;
  }

  /**
   * Category row template.
   * - In table product there's two of type of row (Add/Edit Row and View Row)
   *
   * @since 3.5.4
   */
  template_view_row() {
    /**
     * Return product template markup.
     *
     * Note:
     * `psaeic-` prefix class is abbreviation of "Has Ordered Product Categories Before"
     * The abbreviation is used because there's trigger class with the same name
     * */
    return `
            <tr>
                <td class="product product-${
                  this._data.product.id
                } object" data-product="${esc_attr(
      JSON.stringify(this._data)
    )}">
                    ${this._data.product.label}
                </td>
                <td class="condition">${this._data.condition.label}</td>
                <td class="actions">
                    <a class="psaeic-btn-edit" href="javascript:void(0)" onclick="acfw_psaeic.tableproduct.trigger.trigger_btn_action_edit(event)"><span class="dashicons dashicons-edit"></span></a>
                    <a class="psaeic-btn-remove" href="javascript:void(0)" onclick="acfw_psaeic.tableproduct.trigger.trigger_btn_action_remove(event)"><span class="dashicons dashicons-no"></span></a>
                </td>
            </tr>
        `;
  }

  /**
   * Detect _element in row.
   * - This function using $button: $(this) as a reference to detect other _element in row.
   *
   * @since 3.5.4
   * */
  detect() {
    // Detect Element
    if (this._element.$button) {
      // Detect _element from the button.
      this._element.$row = this._element.$button.closest("tr");
      this._element.$table =
        this._element.$button.closest(".acfw-styled-table");
      this._element.$tbody = this._element.$table.find("tbody");
      this._element.$product = this._element.$row.find(
        ".psaeic-select-product"
      );
      this._element.$condition = this._element.$row.find(
        ".psaeic-select-condition"
      );
      this._element.colspan = this._element.$row.find("td").length;
      this._element.data = this._element.$row
        .find("td.product")
        .data("product");
    }
  }

  /**
   * Read data from row.
   *
   * @since 3.5.4
   * */
  read() {
    const { $product, $condition } = this._element;
    this._data = {
      product: {
        id: parseInt($product?.val()?.toString() ?? ""),
        label:
          $($product)
            ?.select2("data")[0]
            ?.text.replace("\n", "")
            .replace(/\s+/g, " ")
            .trim() ?? "",
      },
      condition: {
        id: $condition?.val()?.toString() ?? "",
        label: $condition
          .find("option:selected")
          .text()
          .replace("\n", "")
          .replace(/\s+/g, " ")
          .trim(),
      },
    };
  }

  /**
   * Validate data from row.
   *
   * @since 3.5.4
   * */
  validate(validated: boolean = true) {
    const { tableproduct } = acfw_psaeic;
    const { fill_form_propery_error_msg } = acfw_edit_coupon;
    const { $product, $condition } = this._element;
    const { product } = this._data;

    // Validate the data in row
    if (
      !$product.val() || // Make sure the product is selected
      !$condition.val() // Make sure the condition is selected
    ) {
      vex.dialog.alert(fill_form_propery_error_msg);
      return false;
    }

    // don't proceed if product has already been added to the table.
    // this happens when the same product has been added to a new row and trying to cancel the row that already has the same value.
    this._element.$table.find("td.product.object").each(function () {
      if ($(this).data("product").product.id == product.id) {
        vex.dialog.alert(tableproduct.products.exists);
        return false;
      }
    });

    return validated;
  }

  /**
   * Add row to table.
   *
   * @since 3.5.4
   * */
  add() {
    // Detect _element in row
    this.detect();
    if (this._data.product.id && !this.validate()) return; // validate data from row, break if not valid

    // make change to the table
    this._element.$tbody.find("tr.no-result").remove();
    this._element.$tbody.append(this.template_add_edit_row());
    this.template_initiate_select2(); // Initiate select2
  }

  /**
   * Edit row.
   * - This will run when the user clicks the edit button in (row > .actions > edit) on View Row
   *
   * @since 3.5.4
   * */
  edit() {
    this.detect(); // Detect _element in row

    // make change to the table
    this._data = this._element.data;
    this._element.$row.replaceWith(this.template_add_edit_row());
    this.template_initiate_select2(); // Initiate select2
  }

  /**
   * View row.
   * - This will be triggered if the user clicks the add button in (row > .actions > add)
   *
   * @since 3.5.4
   * */
  view(data: any = {}) {
    this.template_initiate_select2(); // Initiate select2
    this.detect(); // Detect _element in row
    if (!Object.keys(data).length) this.read(); // read data from row if not exists
    if (!this.validate()) return; // validate data from row, break if not valid

    // Make change to the table
    this._element.$row.replaceWith(this.template_view_row());
  }

  /**
   * Cancel row.
   * - This will be triggered if the user clicks the cancel button.
   * - This will remove the closest row from the table.
   *
   * @since 3.5.4
   * */
  cancel() {
    this.detect(); // Detect _element in row
    this.remove(); // remove row
  }

  /**
   * Remove row.
   *
   * @since 3.5.4
   * */
  remove() {
    const { tableproduct } = acfw_psaeic;
    this._element.$row.remove();
    if (this._element.$tbody.find("tr").length <= 0) {
      this._element.$tbody.html(
        tableproduct.template.table_placeholder_row_template(
          this._element.colspan
        )
      );
    }
  }
}
