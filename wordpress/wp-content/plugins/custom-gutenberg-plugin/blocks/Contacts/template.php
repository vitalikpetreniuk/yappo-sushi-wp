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
    /* Block Name: Contacts */
    if(!empty(get_fields())) foreach(get_fields() as $key=>$field) {$$key = $field;}
    ?>
<section class="contacts">
            <div class="container">
                <div class="row align-items-start mb-md-5 mb-4">
                    <div class="col-3 pe-0">
                        <?php if ( function_exists( 'yoast_breadcrumb' ) ) {
                            if(get_locale() == 'ru_RU') echo str_replace('Головна','Главная',yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>', false));
                            else echo yoast_breadcrumb( '<ul class="breadcrumbs">', '</ul>');
                        } ?>
                    </div>
                    <div class="col-6">
                        <h2 class="section__title">
                            <?php the_title(); ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="img-wrap-map">
                            <?php if (!empty($image)) { ?>
                                <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?>">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <?php foreach($list_items as $item) { ?>
                            <div class="col-md-4 col-6">
                                <div class="local-wrap-contacts">
                                    <?php if (!empty($item['button'])) { ?>
                                        <a class="local-block-contacts" <?=($item['button']['target']?'target="'.$item['button']['target'].'"':'')?> href="<?=$item['button']['url']?>">
                                            <div class="svg-wrap">
                                                <svg class="hover-effect-svg-local" width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="31" height="31" fill="white"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.9713 26.0619L13.0335 26.1233L13.0384 26.1282C14.1783 27.2474 15.2835 28.0146 16.5336 27.9998C17.778 27.9851 18.8787 27.198 20.0179 26.0614C21.5793 24.5112 23.5995 22.4202 25.0591 19.9396C26.5241 17.4497 27.4696 14.4898 26.7582 11.241C24.3546 0.261385 8.65793 0.248522 6.24178 11.2293C5.55057 14.386 6.42383 17.2736 7.81643 19.7208C9.2038 22.159 11.145 24.2291 12.7019 25.7925C12.7926 25.8836 12.8821 25.973 12.9702 26.0608L12.9713 26.0619ZM16.5 9.81499C14.4626 9.81499 12.8109 11.5074 12.8109 13.595C12.8109 15.6826 14.4626 17.375 16.5 17.375C18.5374 17.375 20.1891 15.6826 20.1891 13.595C20.1891 11.5074 18.5374 9.81499 16.5 9.81499Z" fill="#2A1A5E"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5><?=$item['button']['title']?></h5>
                                                <p><?=$item['address']?></p>
                                            </div>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($phone)) { ?>
                            <div class="tel">
                            <a href="tel:<?=str_replace([' ','(',')','-'],'', $phone)?>">
                                <div class="svg-wrap">
                                    <svg class="hover-effect-svg-local" width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.78604 16.2733C4.10831 12.5956 3.62849 6.79807 6.65157 2.56576C7.60693 1.22825 9.53463 1.06871 10.6969 2.23096L12.9085 4.44254C14.3011 5.83521 14.146 8.13635 12.5791 9.32953C11.0121 10.5227 10.857 12.8239 12.2497 14.2165L14.7559 16.7228C16.1486 18.1154 18.4497 17.9603 19.6429 16.3934C20.8361 14.8264 23.1372 14.6713 24.5299 16.064L26.7415 18.2756C27.9037 19.4378 27.7442 21.3655 26.4067 22.3209C22.1744 25.344 16.3768 24.8641 12.6991 21.1864L7.78604 16.2733Z" fill="#2A1A5E"/>
                                    </svg>
                                </div>
                                <span>
                                    <?=$phone?>
                                </span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
<?php endif; ?>
