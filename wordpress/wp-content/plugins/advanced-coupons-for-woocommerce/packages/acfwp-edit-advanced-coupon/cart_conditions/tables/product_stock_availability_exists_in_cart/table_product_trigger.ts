declare var acfw_psaeic: any;
declare var jQuery: any;
const $: any = jQuery;
declare var vex: any;

/**
 * Table Product Trigger class to register trigger.
 *
 * @since 3.5.4
 *
 * @page Edit Coupons
 * @section Cart Conditions
 * */
export default class TableProductTrigger {
  /**
   * Trigger Button (Add Category).
   *
   * @since 3.5.4
   * */
  trigger_btn_add_category(event: any) {
    const { tableproduct } = acfw_psaeic;
    const $this = event.currentTarget; // Equal to this in jQuery
    tableproduct.row.element.$button = $($this);
    tableproduct.row.data = tableproduct.row.data_initial(); // Re-initiate data
    tableproduct.row.add();
  }

  /**
   * Trigger Button Action (Add).
   *
   * @since 3.5.4
   * */
  trigger_btn_action_add(event: any) {
    const { tableproduct } = acfw_psaeic;
    const $this = event.currentTarget; // Equal to this in jQuery
    tableproduct.row.element.$button = $($this);
    tableproduct.row.view();
  }

  /**
   * Trigger Button Action (Edit).
   *
   * @since 3.5.4
   * */
  trigger_btn_action_edit(event: any) {
    const { tableproduct } = acfw_psaeic;
    const $this = event.currentTarget; // Equal to this in jQuery
    tableproduct.row.element.$button = $($this);
    tableproduct.row.edit();
  }

  /**
   * Trigger Button Action (Cancel).
   *
   * @since 3.5.4
   * */
  trigger_btn_action_cancel(event: any) {
    const { tableproduct } = acfw_psaeic;
    const $this = event.currentTarget; // Equal to this in jQuery
    tableproduct.row.element.$button = $($this);
    tableproduct.row.cancel();
  }

  /**
   * Trigger Button Action (Remove).
   *
   * @since 3.5.4
   * */
  trigger_btn_action_remove(event: any) {
    const { tableproduct } = acfw_psaeic;
    const $this = event.currentTarget; // Equal to this in jQuery
    tableproduct.row.element.$button = $($this);
    tableproduct.row.detect();
    tableproduct.row.remove();
  }
}
