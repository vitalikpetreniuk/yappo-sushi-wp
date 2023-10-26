<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test
 */

get_header();
global $post;
$post_id = $post->ID; ?>
  <section class="news-page">
    <div class="container">
      <div class="row align-items-start mb-md-5 mb-3">
        <div class="col-3 pe-0  d-none d-lg-block">
            <?php if (function_exists('yoast_breadcrumb')) {
                if (get_locale() == 'ru_RU') echo str_replace('Головна', 'Главная', yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>', false));
                else echo yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>');
            } ?>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="img-wrap-top">
        <div class="info-wrap">
          <div class="data-span">
              <?= date('d/m/Y', strtotime($post->post_date)) ?>
          </div>
          <h4>
              <?php the_title(); ?>
          </h4>
        </div>
        <div class="img-wrap">
          <img src="<?= get_the_post_thumbnail_url() ?>" alt="<?= get_the_title() ?>">
        </div>
      </div>
        <?php the_content(); ?>
    </div>
    <div class="container-fluid">
      <div class="news-other">
        <h2 class="section__title">
            <?php esc_html_e('НОВИНИ', 'yappo'); ?>
        </h2>
        <div class="row justify-content-center">
            <?php
            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 4,
                'order' => 'DESC',
                'orderby' => 'id'
            ];
            $i = 0;
            $query = new WP_Query($args);
            if ($query->have_posts())
                while ($query->have_posts()) {
                    $query->the_post();
                    $i++;
                    if (get_the_id() == $post_id || $i >= 4) continue;
                    ?>
                  <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                    <a href="<?php the_permalink(); ?>" class="item">
                      <div class="info-wrap">
                        <div class="data-span">
                            <?= date('d/m/Y', strtotime($post->post_date)) ?>
                        </div>
                        <h4>
                            <?php the_title(); ?>
                        </h4>
                      </div>
                      <div class="img-wrap">
                        <img src="<?= get_the_post_thumbnail_url() ?>" alt="<?= get_the_title() ?>">
                      </div>
                    </a>
                  </div>
                <?php } ?>
        </div>
      </div>
    </div>
  </section>
<?php
get_footer();
