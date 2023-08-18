<div class="container-fluid">
    <div class="filter-wrap">


        <div class="filter-btn-wrap">
            <button class="filter-btn-open">
                <img src="<?php bloginfo('template_url'); ?>/assets/img/filter.svg" alt="filter">

                <?php esc_html_e('ФІЛЬТР', 'yappo'); ?>
            </button>

            <hr class="line">
        </div>

        <div class="filter-options">

            <button class="close-filter">
                <svg class="hover-effect-svg" width="22" height="22" viewBox="0 0 22 22" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 2L20.5 20.5" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
                          stroke-linecap="round"></path>
                    <path d="M2 20.5005L20.5 2.00051" stroke="rgba(0,0,0, 0.25)" stroke-width="3"
                          stroke-linecap="round"></path>
                </svg>
            </button>

            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="GET" class="filter__slider-form">
                <div class="row">

                    <div class="col-xl-3 col-lg-4  col-md-5 col-12">
                        <div class="column-one">

                            <h5 class="filter__title">
                                <?php esc_html_e('Ціна', 'yappo') ?>
                            </h5>


                            <div class="filter__range-slider">

                                <div class="pc-range-slider">

                                    <div class="pc-range-slider__wrapper">

                                        <!-- <div class="pc-range-slider__control ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">

                                            <div class="ui-slider-range ui-widget-header ui-corner-all"
                                                 style="left: 0%; width: 50.5443%;">
                                            </div>

                                            <span class="ui-slider-handle ui-state-default ui-corner-all"
                                                  tabindex="0" style="left: 0%;">
                                                </span>

                                            <span class="ui-slider-handle ui-state-default ui-corner-all"
                                                  tabindex="0" style="left: 50.5443%;">
                                                </span>

                                        </div> -->

                                        <div class="range" data-min="<?= $args['min'] ?>"
                                             data-minchoosed="<?= $_GET['min_price'] ?: 0 ?>"
                                             data-maxchoosed="<?= $_GET['max_price'] ?: $args['max'] ?>"
                                             data-max="<?= ceil($args['max'] / 10) * 10 ?>">
                                            <!--                                            <div class="range-slider">-->
                                            <!---->
                                            <!--                                                <span class="range-selected"></span>-->
                                            <!---->
                                            <!--                                            </div>-->
                                            <!--                                            <div class="range-input">-->
                                            <!---->
                                            <!--                                                <input type="range" class="min" min="0" max="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--" value="300"-->
                                            <!--                                                       step="10">-->
                                            <!--                                                <input class="range-two" type="range" class="max" min="0" max="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--"-->
                                            <!--                                                       value="700" step="10">-->
                                            <!---->
                                            <!--                                            </div>-->
                                            <!---->
                                            <!--                                            <div class="range-price">-->
                                            <!---->
                                            <!--                                                <input type="number" name="min_price"-->
                                            <!--                                                       value="-->
                                            <?php //= $_GET['min_price'] ?: $args['min'] ?><!--">-->
                                            <!---->
                                            <!--                                                <input style="text-align: right;" type="number" name="max_price"-->
                                            <!--                                                       value="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--">-->
                                            <!---->
                                            <!--                                            </div>-->
                                            <!--                                            <div class="range-input">-->
                                            <!---->
                                            <!--                                                <input type="range" class="min" min="0" max="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--" value="0" step="10">-->
                                            <!--                                                <input class="range-two" type="range" class="max" min="0" max="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--" value="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--" step="10">-->
                                            <!---->
                                            <!--                                            </div>-->
                                            <!---->
                                            <!--                                            <div class="range-price">-->
                                            <!---->
                                            <!--                                                <input type="number" name="min" value="0">-->
                                            <!---->
                                            <!--                                                <input style="text-align: right;" type="number" name="max" value="-->
                                            <?php //= $_GET['max_price'] ?: $args['max'] ?><!--">-->
                                            <!---->
                                            <!--                                            </div>-->

                                            <div class="range-slider">
                                                <input type="text" class="js-range-slider" value=""/>
                                            </div>
                                            <div class="extra-controls">
                                                <input type="text" name="min_price" class="js-input-from" value="0"/>
                                                <input type="text" name="max_price" class="js-input-to" value="0"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!--
                                                                <div class="filter__slider-control-group">

                                                                    <div class="filter__slider-control-column">

                                                                        <input class="filter__slider-control inp-regulation" type="number"
                                                                               name="min_price" value="">
                                                                    </div>

                                                                    <div class="filter__slider-control-column">

                                                                        <input class="filter__slider-control inp-regulation" type="number"
                                                                               name="max_price" value="">
                                                                    </div>

                                                                </div> -->


                            <div class="radio">

                                <label class="radio-button-container">
                                    <?php esc_html_e('Спочатку дешевше', 'yappo'); ?>
                                    <input type="radio"
                                           name="orderby" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'low_to_high') echo 'checked'; ?>
                                           id="low_to_high"
                                           value="low_to_high">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="radio-button-container">
                                    <?php esc_html_e('Спочатку дорожче', 'yappo'); ?>
                                    <input type="radio"
                                           name="orderby" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'high_to_low') echo 'checked'; ?>
                                           id="high_to_low"
                                           value="high_to_low">
                                    <span class="checkmark"></span>
                                </label>

                            </div>


                            <button class="btn-blue d-md-block d-none">
                                <?php esc_html_e('ПОШУК', 'yappo'); ?>
                            </button>


                        </div>
                    </div>


                    <div class="col-xl-9 col-lg-8  col-md-7 col-12">
                        <div class="col-two">

                            <?php
                            $terms = get_terms(array(
                                'taxonomy' => 'pa_ingredients'
                            ));
                            if (count($terms)) : ?>
                                <div class="filter__item filter__item--type-checkbox">

                                    <h5 class="filter__title">
                                        <?php esc_html_e('Інгредієнти', 'yappo'); ?>
                                    </h5>

                                    <div class="filter__inner  ">

                                        <div class="filter__properties-list">
                                            <?php
                                            foreach ($terms as $term) :
                                                $checked = false;
                                                if (in_array($term->slug, $_GET['pa_ingredients'] ?: [])) $checked = true;
                                                ?>
                                                <div class="filter__properties-item ">
                                                    <div class="filter__checkgroup">
                                                        <div class="filter__checkgroup-body">
                                                            <div class="filter__checkgroup-link ">

                                                                <label class="filter__checkgroup-title radio-button-container <?php if ($checked) echo 'label-active'; ?>"
                                                                       for="<?= $term->slug ?>">
                                                                    <input type="checkbox"
                                                                           name="pa_ingredients[]"
                                                                        <?php if ($checked) echo 'checked' ?>
                                                                           id="<?= $term->slug ?>"
                                                                           value="<?= $term->slug ?>">
                                                                    <!-- <span class="checkmark"></span>     -->

                                                                    <span>
                                                                        <?= $term->name ?>
                                                                    </span>

                                                                    <div class="img-wrap">
                                                                        <?= wp_get_attachment_image(get_field('image', $term)) ?>
                                                                    </div>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php
                            $terms = get_terms(array(
                                'taxonomy' => 'product_tag'
                            ));
                            if (count($terms)) :
                                ?>
                                <div class="filter__item filter__item--type-checkbox category-offers">


                                    <h5 class="filter__title">
                                        <?php esc_html_e('Категорії пропозицій', 'yappo'); ?>
                                    </h5>

                                    <div class="filter__inner  category-wrap-filter">

                                        <div class="filter__properties-list">

                                            <?php foreach ($terms as $term) :
                                                $checked = false;
                                                $checked = in_array($term->slug, $_GET['product_tag'] ?: []);
                                                ?>
                                                <div class="filter__properties-item ">
                                                    <div class="filter__checkgroup"
                                                         data-filter-control-checkgroup="">
                                                        <div class="filter__checkgroup-body">
                                                            <div class="filter__checkgroup-link ">

                                                                <label class="filter__checkgroup-title radio-button-container <?= $term->slug ?> <?php if ($checked) echo 'label-active' ?>"
                                                                       for="<?= $term->slug ?>">
                                                                    <input type="checkbox"
                                                                        <?php if ($checked) echo 'checked' ?>
                                                                           name="product_tag[]"
                                                                           value="<?= $term->slug ?>"
                                                                           id="<?= $term->slug ?>">
                                                                    <!-- <span class="checkmark"></span>     -->

                                                                    <span>
                                                                        <?= $term->name ?>
                                                                    </span>

                                                                    <div class="img-wrap">
                                                                        <div class="default-img">
                                                                            <?= wp_get_attachment_image(get_field('image', $term), 'full') ?>
                                                                        </div>

                                                                        <div class="img-active">
                                                                            <?= wp_get_attachment_image(get_field('hover_image', $term), 'full') ?>
                                                                        </div>
                                                                    </div>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <button class="btn-blue d-md-none d-block">
                                <?php esc_html_e('ПОШУК', 'yappo'); ?>
                            </button>


                        </div>
                    </div>


                </div>
            </form>

            <hr class="line mb-md-3 mb-0">


        </div>

    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="cheked-wrap"  style="margin-top: 6rem">
                <?php if (!empty($_GET)) : ?>

                    <?php if (isset($_GET['orderby'])) : ?>
                        <div class="chaked-box" data-sortby="<?= $_GET['orderby'] ?>">
                            <?php switch ($_GET['orderby']) {
                                case 'low_to_high' :
                                    echo __('Спочатку дешевше', 'yappo');
                                    break;
                                case 'high_to_low':
                                    echo __('Спочатку дорожче', 'yappo');
                                    break;
                            } ?>

                            <div class="close-btn-wrap"></div>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (isset($_GET['product_tag'])) :
                        foreach ($_GET['product_tag'] as $tag) :
                            $term = get_term_by('slug', $tag, 'product_tag');
                            if (!$term) return;
                            switch ($term->term_id) {
                                case $term->term_id === apply_filters('wpml_object_id', 61, 'product_tag') :
                                    $class = 'chaked-box-discount';
                                    break;
                                case $term->term_id === apply_filters('wpml_object_id', 63, 'product_tag') :
                                    $class = 'chaked-box-vegaterian';
                                    break;
                                default:
                                    $class = '';
                            }
                            ?>
                            <div class="chaked-box <?= $class ?>" data-slug="<?= $term->slug ?>"
                                 data-tax="product_tag">
                                <?php
                                $postIdLang = apply_filters( 'wpml_object_id', $term->term_id, 'product_tag' );

                                $name = get_term_by('id', $postIdLang, 'product_tag');
                                echo $name->name ;
                                ?>

                                <div class="close-btn-wrap">

                                </div>
                            </div>
                        <?php endforeach;
                    endif;
                    ?>

                    <?php
                    if (isset($_GET['pa_ingredients'])) :
                        foreach ($_GET['pa_ingredients'] as $tag) :
                            $term = get_term_by('slug', $tag, 'pa_ingredients');
                            if (!$term) return;
                            ?>
                            <div class="chaked-box" data-slug="<?= $term->slug ?>"
                                 data-tax="pa_ingredients">
                                <?php
                                $postIdLang = apply_filters( 'wpml_object_id', $term->term_id, 'pa_ingredients' );

                                $name = get_term_by('id', $postIdLang, 'pa_ingredients');
                                echo $name->name ;
                                ?>
                              <div class="close-btn-wrap">

                                </div>
                            </div>
                        <?php endforeach;
                    endif;
                    ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
