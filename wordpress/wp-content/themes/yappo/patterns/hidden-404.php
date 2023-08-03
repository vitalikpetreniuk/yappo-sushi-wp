<?php
/**
 * Title: 404
 * Slug: yappo/404
 * Categories: text
 * Inserter: no
 *
 * @package yappo
 * @since 1.0.0
 */

?>
<!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
<h2 class="has-text-align-center has-x-large-font-size">
	<?php esc_html_e( 'Page not found', 'yappo' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph --><p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'yappo' ); ?></p><!-- /wp:paragraph -->
<!-- wp:search {"label":"Search","showLabel":false,"buttonText":"Search"} /-->
<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
