declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $ = jQuery;

/**
 * Cashback coupon JS events.
 *
 * @since 3.5.2
 */
export default function cashbackCouponEvents() {
  $("#general_coupon_data").on("change acfw_load", "#discount_type", updateCouponAmountLabel);
  $("#general_coupon_data").on("change acfw_load", "#discount_type", toggleCashbackWaitingPeriodField);
  $("#discount_type").trigger("acfw_load");
}

/**
 * Update the coupon amount label based on the selected coupon type.
 *
 * @since 3.5.2
 */
function updateCouponAmountLabel() {
  // @ts-ignore
  const $couponType = $(this);
  const $couponAmountLabel = $("p.coupon_amount_field label");
  const { cashback_coupon } = acfw_edit_coupon;
  let defaultLabel = $couponAmountLabel.data("default_label");

  if (!defaultLabel) {
    $couponAmountLabel.data("default_label", $couponAmountLabel.text());
    defaultLabel = $couponAmountLabel.text();
  }

  switch ($couponType.val()) {
    case "acfw_percentage_cashback":
      $couponAmountLabel.text(cashback_coupon.cashback_percentage_label);
      break;
    case "acfw_fixed_cashback":
      $couponAmountLabel.text(cashback_coupon.cashback_amount_label);
      break;
    default:
      $couponAmountLabel.text(defaultLabel);
      break;
  }
}

/**
 * Toggle the cashback waiting period field based on the coupon discount type selected.
 *
 * @since 3.5.2
 */
function toggleCashbackWaitingPeriodField() {
  // @ts-ignore
  const $couponType = $(this);
  const $waitPeriodField = $("#_acfw_cashback_waiting_period");

  switch ($couponType.val()) {
    case "acfw_percentage_cashback":
    case "acfw_fixed_cashback":
      $waitPeriodField.closest("p.form-field").show();
      $waitPeriodField.prop("disabled", false);
      break;
    default:
      $waitPeriodField.closest("p.form-field").hide();
      $waitPeriodField.prop("disabled", true);
      break;
  }
}
