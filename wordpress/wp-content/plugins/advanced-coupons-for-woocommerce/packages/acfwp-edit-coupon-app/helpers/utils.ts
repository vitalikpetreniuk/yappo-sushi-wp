// #region [Variables] =================================================================================================

declare var woocommerce_admin_meta_boxes: any;
declare var acfw_edit_coupon: any;

let labels: any = null;

// #endregion [Variables]

// #region [Functions] =================================================================================================

/**
 * Get the coupon ID.
 *
 * @returns {int} Coupon ID.
 */
export function getCouponId() {
  return parseInt(woocommerce_admin_meta_boxes.post_id);
}

/**
 * Get the main coupon code.
 *
 * @returns {string} Coupon code.
 */
export function getMainCouponCode() {
  const title: HTMLInputElement | null = document.querySelector("input#title");
  return title?.value ?? "";
}

/**
 * Get labels used in the virtual coupon app.
 *
 * @returns {object} Labels key/value pair.
 */
export function getAppLabels() {
  if (!labels) {
    const wrapper: HTMLDivElement | null = document.querySelector(`#acfw-virtual-coupon .feature-control`);
    labels = JSON.parse(wrapper?.dataset.labels ?? "");
  }

  return labels;
}

/**
 * Check if URL Coupons feature is enabled for the coupon.
 *
 * @returns {boolean} True if enabled, false otherwise.
 */
export function isUrlCoupons() {
  if (!acfw_edit_coupon.modules.includes("acfw_url_coupons_module")) {
    return false;
  }

  const couponUrlField: HTMLInputElement | null = document.querySelector("#_acfw_enable_coupon_url");

  if (!couponUrlField) {
    return false;
  }

  return couponUrlField?.checked ?? false;
}

// #endregion [Functions]
