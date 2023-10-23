<?php
/**
 *
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */
// Image preview when the block is in the list of blocks
if ( @$block['data']['preview_image_help'] ) : ?>
    <img src="<?= plugin_dir_url(__FILE__) ?>/screenshot.png" alt="">
    <?php
else:
    /* Block Name: News page */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>
    <div class="grid-container">
        <?php foreach($list_items as $key=>$item) { ?>
        <a href="<?=$item['link']?>" class="item item<?=(($key+1)%5 == '0')?'5':($key+1)%5?>">
            <div class="img-wrap">
                <?php if (!empty($item['image'])) { ?>
                    <img src="<?= $item['image']['url'] ?>" alt="<?= $item['image']['alt'] ?>">
                <?php } ?>
            </div>
        </a>
        <?php } ?>
    </div>
<?php endif; ?>
