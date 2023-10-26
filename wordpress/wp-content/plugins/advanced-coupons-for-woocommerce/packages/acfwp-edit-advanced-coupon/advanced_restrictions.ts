declare var jQuery: any;

const $: any = jQuery;

export default function advancedRestrictionsEvents() {
  $("#woocommerce-coupon-data").on("change acfwp_load", "#discount_type", togglePercentageDiscountCapField);

  $("#woocommerce-coupon-data").on("change acfw_load", "select#discount_type", persistDisplayCouponLabelField);

  $("#discount_type").trigger("acfwp_load");
  $("#_acfw_coupon_label").trigger("acfw_load");
}

function togglePercentageDiscountCapField() {
  // @ts-ignore
  const $discountType = $(this);
  const $discountCapField = $discountType
    .closest(".woocommerce_options_panel")
    .find("._acfw_percentage_discount_cap_field");

  if ("percent" === $discountType.val() || "acfw_percentage_cashback" === $discountType.val()) {
    $discountCapField.addClass("show");
    $discountCapField.find("input").prop("disabled", false);
  } else {
    $discountCapField.removeClass("show");
    $discountCapField.find("input").prop("disabled", true);
  }
}

function persistDisplayCouponLabelField() {
  const $couponLabel = $("#_acfw_coupon_label");
  $couponLabel.prop("disabled", false);
}
