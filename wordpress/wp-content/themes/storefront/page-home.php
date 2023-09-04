<?php /* Template Name: Home */

get_header();

// function get_ecommerce_excerpt(){
//     $excerpt = get_the_excerpt();
//     $excerpt = preg_replace(" ([.*?])",'',$excerpt);
//     $excerpt = strip_shortcodes($excerpt);
//     $excerpt = strip_tags($excerpt);
//     $excerpt = substr($excerpt, 0, 100);
//     $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
//     $excerpt = trim(preg_replace( '/s+/', ' ', $excerpt));
//     if (strlen($excerpt) >= 1) {
//         return '<ul> <p>Склад: ' . get_the_excerpt() . '</p></ul>';
//     }
// }
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
		<?php //echo do_shortcode('[smartslider3 slider="2"]') ?>
        <div class="banner-slider swiper">
            <div class="banner-slider__wrapper swiper-wrapper">
                <div class="banner-slider__slide swiper-slide">
                    <a href="/product-category/business_lanch/">
                        <img src='<?php bloginfo( 'template_url' ); ?>/assets/images/main/circle-slider-1.png'
                             alt='Бізнес Ланчі'>
                    </a>
                </div>
                <div class="banner-slider__slide swiper-slide">
                    <a href="/product-category/sale/we-opened/">
                        <img src='<?php bloginfo( 'template_url' ); ?>/assets/images/main/circle-slider-2.png'
                             alt='Ми Відкрились'>
                    </a>
                </div>

                <!-- <div class="banner-slider__slide swiper-slide">
                    <img src='/assets/images/main/circle-slider-3.png'
                         alt='Рол філадельфія'>
                </div> -->

                <div class="banner-slider__slide swiper-slide">
                    <a href="/product-category/sale/month-set/">
                        <img src='<?php bloginfo( 'template_url' ); ?>/assets/images/main/circle-slider-4.png'
                             alt='Сет Місяця'>
                    </a>
                </div>
                <!-- </div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div> -->
            </div>

    </main>
    <marquee>
        <span>У</span><span>НАС</span><span>ЗАВЖДИ</span><span>СМАЧНО</span>
    </marquee>

    <section>
        <div class="container">
            <div class="section__title">
                <h2>новинки</h2>
            </div>
            <div class="product__flex">
				<?php
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 12,
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug', // Or 'name' or 'term_id'
							'terms'    => array( 'additional' ),
							'operator' => 'NOT IN', // Excluded
						)
					),
					'orderby'        => array(
						'date'       => 'DESC',
						'menu_order' => 'ASC',
					)
				);
				$i    = 0;

				$loop = new WP_Query( $args );
				$seo  = [];

				while ( $loop->have_posts() ) : $loop->the_post();
					$product = wc_get_product( get_the_ID() );
					$price   = $product->price;
					$seo[]   = load_template_part( 'template-parts/seo/product', 'item', [
						'i'         => $i,
						'quantity'  => 1,
						'list_name' => 'New',
						'price'     => $price
					] );

					get_template_part( 'template-parts/product', 'home-item', [
						'i'      => $i,
						'hidden' => $i >= 4
					] );
					get_template_part( "inc/theme/cart", null, [ 'id' => get_the_ID() ] );
					$i ++;
				endwhile;

				wp_reset_query();
				?>
            </div>
            <script>
                window.dataLayer = window.dataLayer || [];
                // Measure product views / impressions
                dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
                dataLayer.push({
                    event: "view_item_list",
                    ecommerce: {
                        items: [
							<?= implode( ',', $seo ) ?>
                        ]
                    }
                });
            </script>

            <div class="btn__wrapper">
                <button onclick="showMore(this)" class="btn"><?php esc_html_e( 'ПОКАЗАТИ БІЛЬШЕ', 'yappo' ); ?></button>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="section__title">
                <h2>УЛЮБЛЕНЕ </h2>
            </div>
            <div class="product__flex">
				<?php
				$tax_query[] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN', // or 'NOT IN' to exclude feature products
				);

				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 12,
					'orderby'        => 'date',
					'tax_query'      => $tax_query
				);
				$i    = 0;

				$loop = new WP_Query( $args );
				$seo  = [];

				while ( $loop->have_posts() ) : $loop->the_post();
					$product = wc_get_product( get_the_ID() );
					$price   = $product->price;
					$seo[]   = load_template_part( 'template-parts/seo/product', 'item', [
						'i'         => $i,
						'quantity'  => 1,
						'list_name' => 'New',
						'price'     => $price
					] );

					get_template_part( 'template-parts/product', 'home-item', [
						'i'      => $i,
						'hidden' => $i >= 4
					] );

					get_template_part( "inc/theme/cart", null, [ 'id' => get_the_ID() ] );
					$i ++;
				endwhile;

				wp_reset_query();
				?>
            </div>
            <script>
                window.dataLayer = window.dataLayer || [];
                // Measure product views / impressions
                dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
                dataLayer.push({
                    event: "view_item_list",
                    ecommerce: {
                        items: [
							<?= implode( ',', $seo ) ?>
                        ]
                    }
                });
            </script>
            <div class="btn__wrapper">
                <button onclick="showMore(this)" class="btn"><?php esc_html_e( 'ПОКАЗАТИ БІЛЬШЕ', 'yappo' ); ?></button>
            </div>
        </div>
    </section>
	<?php yappo_about(); ?>
	<?php yappo_delivery(); ?>
	<?php yappo_pay(); ?>
</div>
<?php get_footer(); ?>
