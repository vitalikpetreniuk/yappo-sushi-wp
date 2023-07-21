<?php
/* Prevent direct access */
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

/**
 * This is the default template for one isotopic result
 *
 * !!!IMPORTANT!!!
 * Do not make changes directly to this file! To have permanent changes copy this
 * file to your theme directory under the "asp" folder like so:
 *    wp-content/themes/your-theme-name/asp/isotopic.php
 *
 * It's also a good idea to use the actions to insert content instead of modifications.
 *
 * You can use any WordPress function here.
 * Variables to mention:
 *      Object() $r - holding the result details
 *      Array[]  $s_options - holding the search options
 *
 * DO NOT OUTPUT ANYTHING BEFORE OR AFTER THE <div class='item'>..</div> element
 *
 * You can leave empty lines for better visibility, they are cleared before output.
 *
 * MORE INFO: https://wp-dreams.com/knowledge-base/result-templating/
 *
 * @since: 4.0
 */
?>

<?php
$prid = (int) $r->id;
$prod = wc_get_product( $prid );
if ( ! $prod ) {
	return;
}
$GLOBALS['product'] = $prod;
?>
<div class="col-xl-4 col-lg-6 col-md-6">
	<div class="product__item">

		<?php if ( function_exists( 'yappo_product_badges' ) ) {
			yappo_product_badges( $prid );
		} ?>

		<div class="product__image">
			<a href="<?= $prod->get_permalink() ?>">
				<img alt="<?= $r->title ?>" src="<?php echo $r->image; ?>">
			</a>
		</div>

		<div class="product__detail">

            <a href="<?= $prod->get_permalink() ?>" >
                <h3 title="<?php echo $r->title; ?>"><?php echo $r->title; ?></h3>
            </a>

			<div class="cart__detail">

				<p title="<?= $prod->get_short_description(); ?>"><?= $prod->get_short_description(); ?></p>

			</div>
		</div>

		<div class="product__cart">

			<div class="product__cart-info-block">
				<ul class="product__cart__info <?php if ( $prod->is_on_sale() ) {
					echo "product__cart__info-discount";
				} ?>">
					<?= $prod->get_price_html() ?>
				</ul>
				<?php if ( $prod->get_weight() ) : ?>
					<span class="weight">
                       <?= wc_format_weight( $prod->get_weight() ) ?>
                    </span>
				<?php endif; ?>
			</div>

			<?php
			woocommerce_template_loop_add_to_cart()
			?>
		</div>
	</div>
</div>
