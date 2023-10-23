<?php
/**
 * Template Name: Successful payment
 */

get_header();
?>

    <main>
        <section class="text-center block_pdngs">
            <div class="container">
                <h2 class="section__title"><?php esc_html_e('Дякую за покупку', 'yappo') ?></h2>
            </div>
            <div class="text-center">
                <a href="<?= home_url(); ?>" class="btn-blue"><?php esc_html_e('Перейти на головну', 'yappo'); ?></a>
            </div>
        </section>
    </main><!-- #main -->

<?php
get_footer();
