<?php
/**
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if (@$block['data']['preview_image_help']) : ?>
    <img src="<?= plugin_dir_url(__FILE__) ?>/screenshot.png" alt="">
<?php else:
    // Your block html goes here
    ?>
    <section class="accordion-section">
        <div class="container-fluid">

            <div class="accordeon">
                <?php
                if (have_rows('questions')):
                    while (have_rows('questions')) : the_row();
                        yappo_faq_row(get_sub_field('question'), get_sub_field('answer'));
                    endwhile;
                endif; ?>
                <?php ?>
                <?php
                foreach (FILTERED_TAXONOMIES as $tax) {
                    if (isset($_GET[$tax])) {
                        foreach ($_GET[$tax] as $item) {
                            $term = get_term_by('slug', $item, $tax);
                            yappo_faq_row(get_field('seo_title', $term), get_field('seo_text', $term));
                        }
                    }
                }
                ?>
            </div>
        </div>
    </section>

<?php endif; ?>
