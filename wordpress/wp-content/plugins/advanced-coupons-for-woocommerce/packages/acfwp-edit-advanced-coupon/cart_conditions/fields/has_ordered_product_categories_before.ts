/**
 * Has ordered before condition field template.
 * - hopcb stands for Has Ordered Product Categories Before
 *
 * @since 3.5.4
 * @page Edit Coupons
 * @section Cart Conditions
 * @key has_ordered_product_categories_before
 **/
import { selected, selected_multiple } from "../../helper";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { has_ordered_product_categories_before: has_ordered_product_categories_before } = acfw_edit_coupon.cart_condition_fields;

/**
 * Register has ordered product category before custom field.
 *
 * @since 3.5.4
 */
export default function register_has_ordered_product_categories_before() {
    has_ordered_product_categories_before.default_data_value = {};
    has_ordered_product_categories_before.template_callback = template;
    has_ordered_product_categories_before.scraper_callback = scraper;
}

/**
 * Return has ordered before condition field template markup.
 *
 * @since 3.5.4
 *
 * @param data
 */
function template(data: any = {}): string {

    // Data
    const { condition , quantity } = data;
    const {
        condition_label,
        cart_condition_fields: {
            cart_quantity: {
                field: quantityLabel
            },
            has_ordered_product_categories_before: {
                title,
                type,
                within_a_period,
                number_of_orders,
                num_prev_days,
                categories
            },
            product_category: {
                placeholder
            }
        },
        condition_field_options: {
            exactly,
            anyexcept,
            morethan,
            lessthan,
        },
    } = acfw_edit_coupon;

    // Local Variables.
    let types = [
        { value: "within-a-period" , label: within_a_period },
        { value: "number-of-orders" , label: number_of_orders },
    ]
    let conditions = [
        { value: "=" , label: exactly },
        { value: "!=" , label: anyexcept },
        { value: ">" , label: morethan },
        { value: "<" , label: lessthan },
    ]

    return `
        <div class="has-ordered-product-categories-before-field condition-field condition-set" data-type="has-ordered-product-categories-before">
            <a class="remove-condition-field" href="javascript:void(0);"><i class="dashicons dashicons-trash"></i></a>
            <h3 class="condition-field-title">${title}</h3>
            
            <div class="field-control">
                <label>${type}</label>
                <select class="hopcb-select-type-condition">                    
                    ${ types.map( ( type: any ) => {
                        const { value , label } = type;
                        return `<option value="${ value }" ${selected( condition, value )}>${ label }</option>`;
                    }).join("") }
                </select>
            </div>
            <div class="field-control">
                <label>${num_prev_days}</label>
                <input class="hopcb-select-type-value" type="number" min="0" value="${ (data.type && data.type.value) ? data.type.value : 0 }">
            </div>
            <div class="field-control categories">
                <label></label>
                <select class="hopcb-select-categories wc-enhanced-select" multiple data-placeholder="${placeholder}">
                    ${ Object.keys( categories.options ).map( ( value: any ) => {
                        let label = categories.options[value];
                        const selected = data.categories && data.categories.indexOf( parseInt(value) ) >= 0 ? "selected" : "";
                        return `<option value="${ value }" ${selected}>${ label }</option>`;
                    }).join("") }
                </select>
            </div>
            <div class="field-control">
                <label>${ condition_label }</label>
                <select class="hopcb-select-condition">
                    ${ conditions.map( ( condition: any ) => {
                        const { value , label } = condition;
                        let condOption = data.condition ? data.condition : ">";
                        let selected = condOption === value ? "selected" : "";
                        return `<option value="${ value }" ${selected}>${ label }</option>`;
                    }).join("") }
                </select>
            </div>
            <div class="field-control">
                <label>${ quantityLabel }</label>
                <input type="number" class="hopcb-select-quantity" value="${ typeof quantity ? quantity : 0 }" min="${ condition == "<" ? 1 : 0 }">
            </div>
            
        </div>
    `;
}

/**
 * Validate form values (no null, empty string, undefined, empty array)
 *
 * @since 3.5.4
 * @param data
 * @returns {boolean}
 * */
function validate(data: unknown): boolean {
    if (data == null) { return false; }
    if (typeof data === "string" && data.trim() === "") { return false; }
    if (Array.isArray(data) && data.length === 0) { return false; }
    if (typeof data === "object") {
        for (const key in data) {
            if (!validate((data as Record<string, unknown>)[key])) {
                return false;
            }
        }
    }
    return true;
}

/**
 * Has ordered product categories before condition field scraper.
 *
 * @since 3.5.4
 *
 * @param condition_field
 */
function scraper(condition_field: HTMLElement) {
    const rows: NodeListOf<HTMLElement> = condition_field.querySelectorAll(
        ".has-ordered-product-categories-before-table tr td.category"
    );

    // Data
    let data = {
        categories: $(condition_field).find(".hopcb-select-categories").val(),
        condition: $(condition_field).find(".hopcb-select-condition").val(),
        quantity: $(condition_field).find(".hopcb-select-quantity").val(),
        type: {
            condition: $(condition_field).find(".hopcb-select-type-condition").val(),
            label: $(condition_field).find(".hopcb-select-type-condition option:selected").text(),
            value: $(condition_field).find(".hopcb-select-type-value").val(),
        },
    };

    // Validate data.
    if (!validate(data)) return false;

    return data;
}
