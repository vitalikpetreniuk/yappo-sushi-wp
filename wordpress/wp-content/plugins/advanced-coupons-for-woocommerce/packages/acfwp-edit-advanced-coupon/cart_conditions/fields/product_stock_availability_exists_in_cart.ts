/**
 * Product Stock Availability Exists In Cart (PSAEIC)
 *
 * @page Edit Coupons
 * @section Cart Conditions
 * @key product_stock_availability_exists_in_cart
 **/
import TableProduct from "../tables/product_stock_availability_exists_in_cart/table_product";

declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { product_stock_availability_exists_in_cart } =
  acfw_edit_coupon.cart_condition_fields;

/**
 * Register has ordered before custom field.
 *
 * @since 3.5.4
 */
export default function register_product_stock_availability_exists_in_cart() {
  product_stock_availability_exists_in_cart.default_data_value = {};
  product_stock_availability_exists_in_cart.template_callback = template;
  product_stock_availability_exists_in_cart.scraper_callback = scraper;
}

/**
 * Return product quantity condition field template markup.
 *
 * @since 3.5.4
 *
 * @param data
 */
function template(data: any = {}): string {
  const { title, products } = product_stock_availability_exists_in_cart;

  // Set Products data from database
  products.data = data.products;

  // Table Category
  const tableproduct = new TableProduct();
  tableproduct.products = products;
  tableproduct.build();

  return `
        <div class="product-stock-availability-exists-in-cart-field condition-set" data-type="product-stock-availability-exists-in-cart">
            <a class="remove-condition-field" href="javascript:void(0);"><i class="dashicons dashicons-trash"></i></a>
            <h3 class="condition-field-title">${title}</h3>
            
            ${tableproduct.template.table_markup()}
        </div>
    `;
}

/**
 * Product Stock Availability Exists In Cart condition field scraper.
 *
 * @since 3.5.4
 *
 * @param condition_field
 */
function scraper(condition_field: HTMLElement) {
  const rows: NodeListOf<HTMLElement> = condition_field.querySelectorAll(
    ".product-stock-availability-exists-in-cart-table tr td.product"
  );
  const products: string[] = [];

  rows.forEach((r) => products.push($(r).data("product")));

  return { products };
}
