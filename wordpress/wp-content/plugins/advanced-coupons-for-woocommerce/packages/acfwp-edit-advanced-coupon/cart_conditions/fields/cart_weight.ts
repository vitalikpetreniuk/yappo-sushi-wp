import { condition_options } from "../../helper";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { cart_weight } = acfw_edit_coupon.cart_condition_fields;

/**
 * Register cart weight custom field.
 *
 * @since 3.5.6
 */
export default function register_cart_weight() {
  cart_weight.default_data_value = {};
  cart_weight.template_callback = template;
  cart_weight.scraper_callback = scraper;
}

/**
 * Return cart weight condition field template markup.
 *
 * @since 3.5.6
 *
 * @param data
 */
function template(data: any): string {
  const { condition, value } = data;
  const { title, desc, field } = cart_weight;
  const { condition_label } = acfw_edit_coupon;

  return `
    <div class="cart-weight-field condition-field" data-type="cart-weight">
        <a class="remove-condition-field" href="javascript:void(0);"><i class="dashicons dashicons-trash"></i></a>
        <h3 class="condition-field-title">${title}</h3>
        <label>${desc}</label>
        <div class="field-control">
            <label>${condition_label}</label>
            <select class="condition-select">
                ${condition_options(condition)}
            </select>
        </div>
        <div class="field-control">
            <label>${field}</label>
            <input class="condition-value wc_input_decimal" type="text" value="${value >= 0 ? value : ""}">
        </div>
    </div>
    `;
}

/**
 * Cart weight condition field scraper.
 *
 * @since 3.5.6
 *
 * @param condition_field
 */
function scraper(condition_field: HTMLElement) {
  return {
    condition: $(condition_field).find(".condition-select").val(),
    value: $(condition_field).find(".condition-value").val(),
  };
}
