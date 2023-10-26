import "./index.scss";

declare var slmw_args: any;
declare var ajaxurl: string;
declare var vex: any;

jQuery(document).ready(function ($) {
  $(".acfwp-license-settings-block button[type='submit']")
    .text(slmw_args.i18n_activate_license)
    .val(slmw_args.i18n_activate_license)
    .click(function (e) {
      // Internationalize

      e.preventDefault();

      var $this = $(this),
        $info_block = $this.closest(".license-info"),
        $indicator = $info_block.find(".active-indicator"),
        activation_email = $.trim($("#acfw_slmw_activation_email").val()?.toString() ?? ""),
        license_key = $.trim($("#acfw_slmw_license_key").val()?.toString() ?? "");

      $info_block.find(".overlay").css("display", "flex");

      $this.val(slmw_args.i18n_activating_license).attr("disabled", "disabled");

      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "acfw_activate_license",
          "activation-email": activation_email,
          "license-key": license_key,
          "ajax-nonce": slmw_args.nonce_activate_license,
        },
        dataType: "json",
      })
        .done(function (data) {
          if (data.status === "success") {
            if ($(".tap-activate-license-notice").length > 0)
              $(".tap-activate-license-notice").closest("div.error").remove();

            $indicator.addClass("license-active dashicons-before dashicons-yes-alt");
            $indicator.text(slmw_args.i18n_license_activated);
            vex.dialog.alert(data.success_msg);
          } else {
            $indicator.removeClass("license-active dashicons-before dashicons-yes-alt");
            $indicator.text(slmw_args.i18n_license_not_active);
            vex.dialog.alert(data.error_msg);
            $this.removeClass("grayed");
          }
        })
        .fail(function (jqxhr) {
          $indicator.text(slmw_args.i18n_license_not_active);
          vex.dialog.alert(slmw_args.i18n_failed_to_activate_license);
          $this.removeClass("grayed");
        })
        .always(function () {
          $this.val(slmw_args.i18n_activate_license).removeAttr("disabled");
          $info_block.find(".overlay").css("display", "none");
        });
    });
});
