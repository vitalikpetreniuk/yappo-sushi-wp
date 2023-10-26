declare var jQuery: any;

const $ = jQuery;

/**
 * Virtual Coupon related events.
 *
 * @since 3.0
 */
export default function events() {
  $("body").on("change load_virtual_coupon", "#_acfw_enable_virtual_coupons", toggleVirtualCouponsFeature);

  $("#_acfw_enable_virtual_coupons").trigger("load_virtual_coupon");
}

/**
 * Toggle Virtual Coupons.
 *
 * @since 3.0
 */
function toggleVirtualCouponsFeature(e: any) {
  // @ts-ignore
  const $checkbox = jQuery(this);
  const $inside = $checkbox.closest(".inside");
  const $allowedCustomersField = $("select.acfw-allowed-customers");

  if ("change" === e.type) {
    $inside.find(".save-notice").show();
  }

  if ($checkbox.is(":checked")) {
    $inside.removeClass("disabled");
    $allowedCustomersField.prop("disabled", true);
    $allowedCustomersField.closest(".options_group").hide();
    toggleCouponUrlField(true);
  } else {
    $inside.addClass("disabled");
    $allowedCustomersField.closest(".options_group").show();
    $allowedCustomersField.prop("disabled", false);
    toggleCouponUrlField(false);
  }
}

/**
 * Hide the coupon URL input field and texts and also hide the coupon URL override field when virtual coupon feature is enabled.
 * This replaces the coupon URL input field with the text "URL managed on individual Virtual Coupons."
 *
 * @since 3.5.2
 *
 * @param {boolean} toggle True if hide, false otherwise.
 */
function toggleCouponUrlField(toggle: boolean) {
  const $couponUrlField = jQuery(".form-field._acfw_coupon_url_field");
  const $urlOverrideField = jQuery(".form-field._acfw_code_url_override_field");

  if (toggle) {
    const labels = $("#acfw-virtual-coupon .feature-control").data("labels") ?? {};
    $couponUrlField.find("input,button,span").hide();
    $couponUrlField.append(`<span class="vc-manage-url-coupon">${labels.url_coupon_message}</span>`);
    $urlOverrideField.hide();
  } else {
    $couponUrlField.find(".vc-manage-url-coupon").remove();
    $couponUrlField.find("input,button,span").show();
    $urlOverrideField.show();
  }
}
