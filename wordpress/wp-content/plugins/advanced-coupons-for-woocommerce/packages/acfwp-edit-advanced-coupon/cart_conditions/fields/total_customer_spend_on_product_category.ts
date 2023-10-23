/**
 * Total Customer Spend on a certain product category
 * - tcspc stands for Total Customer Spend on a certain product category
 *
 * @since 3.5.6
 * @page Edit Coupons
 * @section Cart Conditions
 * @key total_customer_spend_on_product_category
 **/
import { selected } from '../../helper';

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { total_customer_spend_on_product_category } = acfw_edit_coupon.cart_condition_fields;

/**
 * Register custom field.
 *
 * @since 3.5.6
 */
export default function register_total_customer_spend_on_product_category() {
  total_customer_spend_on_product_category.default_data_value = {};
  total_customer_spend_on_product_category.template_callback = template;
  total_customer_spend_on_product_category.scraper_callback = scraper;
}

/**
 * Return field template markup.
 *
 * @since 3.5.6
 *
 * @param data
 */
function template(data: any = {}): string {
  // Data
  const { condition, spend } = data;
  const {
    condition_label,
    cart_condition_fields: {
      total_customer_spend: { total_spend },
      total_customer_spend_on_product_category: {
        title,
        type,
        within_a_period,
        number_of_orders,
        num_prev_days,
        categories,
      },
      product_category: { placeholder },
    },
    condition_field_options: { exactly, anyexcept, morethan, lessthan },
  } = acfw_edit_coupon;

  // Local Variables.
  let types = [
    { value: 'within-a-period', label: within_a_period },
    { value: 'number-of-orders', label: number_of_orders },
  ];
  let conditions = [
    { value: '=', label: exactly },
    { value: '!=', label: anyexcept },
    { value: '>', label: morethan },
    { value: '<', label: lessthan },
  ];

  return `
        <div class="total-customer-spend-on-product-category-field condition-field condition-set" data-type="total-customer-spend-on-product-category">
            <a class="remove-condition-field" href="javascript:void(0);"><i class="dashicons dashicons-trash"></i></a>
            <h3 class="condition-field-title">${title}</h3>
            
            <div class="field-control">
                <label>${type}</label>
                <select class="tcspc-select-type-condition">                    
                    ${types
                      .map((type: any) => {
                        const { value, label } = type;
                        return `<option value="${value}" ${selected(condition, value)}>${label}</option>`;
                      })
                      .join('')}
                </select>
            </div>
            <div class="field-control">
                <label>${num_prev_days}</label>
                <input class="tcspc-select-type-value" type="number" min="0" value="${
                  data.type && data.type.value ? data.type.value : 0
                }">
            </div>
            <div class="field-control categories">
                <label></label>
                <select class="tcspc-select-categories wc-enhanced-select" multiple data-placeholder="${placeholder}">
                    ${Object.keys(categories.options)
                      .map((value: any) => {
                        let label = categories.options[value];
                        const selected =
                          data.categories && data.categories.indexOf(parseInt(value)) >= 0 ? 'selected' : '';
                        return `<option value="${value}" ${selected}>${label}</option>`;
                      })
                      .join('')}
                </select>
            </div>
            <div class="field-control">
                <label>${condition_label}</label>
                <select class="tcspc-select-condition">
                    ${conditions
                      .map((condition: any) => {
                        const { value, label } = condition;
                        let condOption = data.condition ? data.condition : '>';
                        let selected = condOption === value ? 'selected' : '';
                        return `<option value="${value}" ${selected}>${label}</option>`;
                      })
                      .join('')}
                </select>
            </div>
            <div class="field-control">
                <label>${total_spend}</label>
                <input type="text" class="tcspc-select-spend wc_input_decimal" value="${spend ?? 0}">
            </div>
            
        </div>
    `;
}

/**
 * Validate form values (no null, empty string, undefined, empty array)
 *
 * @since 3.5.6
 * @param data
 * @returns {boolean}
 * */
function validate(data: unknown): boolean {
  if (data == null) {
    return false;
  }
  if (typeof data === 'string' && data.trim() === '') {
    return false;
  }
  if (Array.isArray(data) && data.length === 0) {
    return false;
  }
  if (typeof data === 'object') {
    for (const key in data) {
      if (!validate((data as Record<string, unknown>)[key])) {
        return false;
      }
    }
  }
  return true;
}

/**
 * Field scraper.
 *
 * @since 3.5.6
 *
 * @param condition_field
 */
function scraper(condition_field: HTMLElement) {
  // Data
  let data = {
    categories: $(condition_field).find('.tcspc-select-categories').val(),
    condition: $(condition_field).find('.tcspc-select-condition').val(),
    spend: $(condition_field).find('.tcspc-select-spend').val(),
    type: {
      condition: $(condition_field).find('.tcspc-select-type-condition').val(),
      label: $(condition_field).find('.tcspc-select-type-condition option:selected').text(),
      value: $(condition_field).find('.tcspc-select-type-value').val(),
    },
  };

  // Validate data.
  if (!validate(data)) return false;

  return data;
}
