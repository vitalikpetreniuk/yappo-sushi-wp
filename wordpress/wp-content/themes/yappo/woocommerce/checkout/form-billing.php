<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-billing-fields">
    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

  <div class="row">
      <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

          <?php do_action('woocommerce_review_order_before_shipping'); ?>

        <div class="col-12 p-0 px-md-3">
          <div class="delivery">
              <?php wc_cart_totals_shipping_html(); ?>
          </div>
        </div>


          <?php do_action('woocommerce_review_order_after_shipping'); ?>

      <?php endif; ?>

    <div class="col-12">
      <div class="title-step">
        <span>1</span>
        <h3>
            <?php esc_html_e('Ваші дані', 'yappo'); ?>
        </h3>
      </div>
    </div>
  </div>
  <div class="woocommerce-billing-fields__field-wrapper row">
      <?php
      $fields = $checkout->get_checkout_fields('billing');

      foreach ($fields as $key => $field) {
          woocommerce_form_field($key, $field, $checkout->get_value($key));
      }
      ?>
  </div>
  <div class="row">
    <!--		<div class="col-md-6">-->
    <!--			<div class="quantity-wrap">-->
    <!--				<p>-->
    <!--					--><?php //esc_html_e( 'Кількість людей', 'yappo' ); ?>
    <!--				</p>-->
    <!---->
    <!--				<div class="quantity">-->
    <!--					<button type="button" class="minus">-->
    <!--						--->
    <!--					</button>-->
    <!---->
    <!--					<input type="number" class="input-text qty text quantity-input" name="number_of_people" value="1"-->
    <!--					       title="К-ть" size="4" min="1" max="" step="1" placeholder="" inputmode="numeric"-->
    <!--					       autocomplete="off" readonly="">-->
    <!---->
    <!--					<button type="button" class="plus">-->
    <!--						+-->
    <!--					</button>-->
    <!--				</div>-->
    <!--			</div>-->
    <!--		</div>-->
    <div class="col-md-6">
      <div class="quantity-wrap">
        <p>
            <?php esc_html_e('Комплектів паличок', 'yappo'); ?>
        </p>

        <div class="quantity">
          <button type="button" class="minus">
            -
          </button>

          <input type="number" class="input-text qty text quantity-input" name="count_of_chopstics" value="1"
                 title="К-ть" size="4" min="1" max="" step="1" placeholder="" inputmode="numeric"
                 autocomplete="off" readonly="">

          <button type="button" class="plus">
            +
          </button>
        </div>
      </div>
    </div>
    <!--		<div class="col-md-6">-->
    <!--			<div class="quantity-wrap">-->
    <!--				<p>-->
    <!--					--><?php //esc_html_e( 'Комплектів навчальних паличок', 'yappo' ); ?>
    <!--				</p>-->
    <!---->
    <!--				<div class="quantity">-->
    <!--					<button type="button" class="minus">-->
    <!--						--->
    <!--					</button>-->
    <!---->
    <!--					<input type="number" class="input-text qty text quantity-input"-->
    <!--					       name="count_of_educational_chopstics" value="0"-->
    <!--					       title="К-ть" size="4" min="0" max="" step="1" placeholder="" inputmode="numeric"-->
    <!--					       autocomplete="off" readonly="">-->
    <!---->
    <!--					<button type="button" class="plus">-->
    <!--						+-->
    <!--					</button>-->
    <!--				</div>-->
    <!--			</div>-->
    <!--		</div>-->
    <div class="col-12">
      <div class="select-wrap">

        <div class="img-wrap-local">
          <img src="<?= get_theme_file_uri('assets/img/local-blue.svg') ?>" alt="local">
        </div>

        <div class="wrap-center ms-0 me-auto">
          <p class="choose-city">
              <?php esc_html_e('Ваше місто', 'yappo'); ?>
          </p>

          <div class="select-dropdown">
            <div role="button" class="select-dropdown__button">
							<span
                  class="city"><?= get_locale() == 'uk' ? checkout_get_billing_city() : get_ru_city_name() ?> (<?php if (function_exists('yappo_get_chosen_adress')) echo yappo_get_chosen_adress() ?>)</span>
              <span
                  class="region"><?= WC()->countries->get_states()['UA'][WC()->customer->get_billing_state()]; ?></span>
            </div>
          </div>
        </div>

        <div class="arrow-rotate">
          <img src="<?= get_theme_file_uri('assets/img/blue-arrow-right.svg') ?>" alt="">
        </div>
      </div>
    </div>
  </div>

    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
  <div class="woocommerce-account-fields">
      <?php if (!$checkout->is_registration_required()) : ?>

        <p class="form-row form-row-wide create-account">
          <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                   id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?>
                   type="checkbox" name="createaccount" value="1"/>
            <span><?php esc_html_e('Create an account?', 'woocommerce'); ?></span>
          </label>
        </p>

      <?php endif; ?>

      <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

      <?php if ($checkout->get_checkout_fields('account')) : ?>

        <div class="create-account">
            <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
            <?php endforeach; ?>
          <div class="clear"></div>
        </div>

      <?php endif; ?>

      <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
  </div>
<?php endif; ?>
