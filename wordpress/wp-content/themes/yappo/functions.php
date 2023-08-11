<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package yappo
 * @since 1.0.0
 */

/**
 * The theme version.
 *
 * @since 1.0.0
 */
define('YAPPO_VERSION', wp_get_theme()->get('Version'));

/**
 * Add theme support for block styles and editor style.
 *
 * @return void
 * @since 1.0.0
 *
 */
function yappo_setup()
{
    add_editor_style('./assets/css/style-shared.min.css');

    /*
       * Load additional block styles.
       * See details on how to add more styles in the readme.txt.
       */
    $styled_blocks = ['button', 'quote', 'navigation'];
    foreach ($styled_blocks as $block_name) {
        $args = array(
            'handle' => "yappo-$block_name",
            'src' => get_theme_file_uri("assets/css/blocks/$block_name.min.css"),
            'path' => get_theme_file_path("assets/css/blocks/$block_name.min.css"),
        );
        // Replace the "core" prefix if you are styling blocks from plugins.
        wp_enqueue_block_style("core/$block_name", $args);
    }

}

add_action('after_setup_theme', 'yappo_setup');

/**
 * Enqueue the CSS files.
 *
 * @return void
 * @since 1.0.0
 *
 */
function yappo_styles()
{
    wp_enqueue_style(
        'yappo-shared-styles',
        get_theme_file_uri('assets/css/style-shared.min.css'),
        [],
        YAPPO_VERSION
    );
    wp_enqueue_style(
        'yappo-fonts',
        'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap',
        [],
        YAPPO_VERSION
    );
    wp_enqueue_style(
        'yappo-swiper',
        get_theme_file_uri('assets/libs/swiper.min.css'),
        [],
        YAPPO_VERSION
    );
    wp_enqueue_style(
        'yappo-rangestyle',
        get_theme_file_uri('assets/libs/ion.rangeSlider.min.css'),
        [],
        time()
    );
    wp_enqueue_style(
        'yappo-css',
        get_theme_file_uri('assets/css/style.min.css'),
        [],
        time()
    );
    wp_enqueue_style(
        'yappo-style',
        get_stylesheet_uri(),
        [],
        time()
    );

    $deps = ['jquery', 'yappo-rangeslider'];

    if (is_checkout()) {
        wp_enqueue_script('yappo-mask', get_theme_file_uri('assets/libs/jquery.mask.min.js'));
        $deps[] = 'yappo-mask';
    }

    wp_enqueue_script('yappo-swiper', get_theme_file_uri('assets/libs/swiper.min.js'));
    wp_enqueue_script('yappo-rangeslider', get_theme_file_uri('assets/libs/ion.rangeSlider.min.js'));

    wp_enqueue_script('yappo-script', get_theme_file_uri('assets/js/scripts.min.js'), $deps, time());
    wp_enqueue_script('yappo-backend', get_theme_file_uri('assets/js/backend.js'), array('jquery'), time());

}

add_action('wp_enqueue_scripts', 'yappo_styles');

// Block style examples.
require_once get_theme_file_path('inc/register-block-styles.php');

// Block pattern and block category examples.
require_once get_theme_file_path('inc/register-block-patterns.php');

if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

add_theme_support('woocommerce');

add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('primary', 'Головне меню');
    register_nav_menu('primary-mob', 'Головне мобільне меню');
    register_nav_menu('footer1', 'Меню у футері 1');
    register_nav_menu('footer2', 'Меню у футері 2');
}

require_once 'inc/shop-functions.php';
require_once 'inc/functions-sasha.php';

function yappo_lang_opener($classes = '')
{ ?>
  <div class="lang lang-desctop <?= $classes ?>">

    <div class="d-flex align-items-center justify-content-start">
        <?php echo do_shortcode('[wpml_language_switcher]
			<div class="{{ css_classes }} lang-desctop-wrap">
			
			   {% for code, language in languages %}
			           <a href="{{ language.url }}" data-lang="{{language.code}}" class="lang-opener {{ language.css_classes }}">
			           {{ language.display_name  }}
			           </a>
			
			   {% endfor %}
			
			</div>
			[/wpml_language_switcher]'); ?>

      <div class="arrow-wrap">
        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
             xmlns="http://www.w3.org/2000/svg">
          <path d="M1 1L3.8 5L7 1" stroke="black" stroke-linecap="round"
                stroke-linejoin="round"/>
        </svg>
      </div>
    </div>


      <?php echo do_shortcode('[wpml_language_switcher]
								<div class="{{ css_classes }} lang-list">

								   {% for code, language in languages %}
										   <a href="{{ language.url }}" data-lang="{{language.code}}" class="lang-opener {{ language.css_classes }}">
										   {{ language.display_name  }}
										   </a>

								   {% endfor %}

								</div>
								[/wpml_language_switcher]') ?>

  </div>

    <?php
}

/**
 * Create a globally accessible counter for all queries
 * Even custom new WP_Query!
 */

// Initialize your variables
add_action('init', function () {
    global $cqc;
    $cqc = -1;
});

// At loop start, always make sure the counter is -1
// This is because WP_Query calls "next_post" for each post,
// even for the first one, which increments by 1
// (meaning the first post is going to be 0 as expected)
add_action('loop_start', function ($q) {
    global $cqc;
    $cqc = -1;
}, 100, 1);

// At each iteration of a loop, this hook is called
// We store the current instance's counter in our global variable
add_action('the_post', function ($p, $q) {
    global $cqc;
    $cqc = $q->current_post;
}, 100, 2);

// At each end of the query, we clean up by setting the counter to
// the global query's counter. This allows the custom $cqc variable
// to be set correctly in the main page, post or query, even after
// having executed a custom WP_Query.
add_action('loop_end', function ($q) {
    global $wp_query, $cqc;
    $cqc = $wp_query->current_post;
}, 100, 1);

/**
 * Filter the output of Yoast breadcrumbs so each item is an <li> with schema markup
 *
 * @param $link_output
 * @param $link
 *
 * @return string
 */
function doublee_filter_yoast_breadcrumb_items($link_output, $link)
{ ?>
    <?php
    $new_link_output = '<li   itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    $new_link_output .= '<a href="' . $link['url'] . '" title="' . $link['text'] . '" itemprop="item"> <span itemprop="name">' . $link['text'] . '</span>
            <meta itemprop="position" content=""></a>';
    $new_link_output .= '</li>';
    return $new_link_output;
}

add_filter('wpseo_breadcrumb_single_link', 'doublee_filter_yoast_breadcrumb_items', 10, 2);


/**
 * Filter the output of Yoast breadcrumbs to remove <span> tags added by the plugin
 *
 * @param $output
 *
 * @return mixed
 */
function doublee_filter_yoast_breadcrumb_output($output)
{

    $from = '<span>';
    $to = '</span>';
    $output = str_replace($from, $to, $output);

    return $output;
}

add_filter('wpseo_breadcrumb_output', 'doublee_filter_yoast_breadcrumb_output');


/**
 * Shortcut function to output Yoast breadcrumbs
 * wrapped in the appropriate markup
 */
function doublee_breadcrumbs()
{
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<ul class="breadcrumbs"  itemscope itemtype="https://schema.org/BreadcrumbList">', '</ul>');
    }
}

function bootstrap_pagination(\WP_Query $wp_query = null, $echo = true, $params = [])
{
    if (null === $wp_query) {
        global $wp_query;
    }

    $add_args = [];

    //add query (GET) parameters to generated page URLs
    /*if (isset($_GET[ 'sort' ])) {
          $add_args[ 'sort' ] = (string)$_GET[ 'sort' ];
      }*/

    $pages = paginate_links(array_merge([
            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type' => 'array',
            'show_all' => false,
            'end_size' => 3,
            'mid_size' => 1,
            'prev_next' => true,
            'prev_text' => '<img src="' . get_theme_file_uri('assets/img/arrow-left-pagination.svg') . '" alt="prev">',
            'next_text' => '<img src="' . get_theme_file_uri('assets/img/arrow-right-pagination.svg') . '" alt="next">',
            'add_args' => $add_args,
            'add_fragment' => ''
        ], $params)
    );

    if (is_array($pages)) {
        //$current_page = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
        $pagination = '<div class="pagination-wrap"><ul class="pagination">';

        foreach ($pages as $page) {
            $pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
        }

        $pagination .= '</ul></div>';

        if ($echo) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }

    return null;
}

/**
 * Register a 'genre' taxonomy for post type 'book'.
 *
 * Register custom capabilities for taxonomies.
 *
 * @see register_post_type for registering post types.
 */
function wpdocs_create_book_tax()
{
    register_taxonomy('cities', 'page', array(
        'label' => __('Міста UA', 'textdomain'),
        'hierarchical' => false,
        'public' => true,
    ));
    register_taxonomy('cities_ru', 'page', array(
        'label' => __('Міста RU', 'textdomain'),
        'hierarchical' => false,
        'public' => true,
    ));
}

add_action('woocommerce_init', 'wpdocs_create_book_tax', 0);

function custom_rewrite_rule()
{
    add_rewrite_rule('^bucha/([^/]*)/?', 'index.php?pagename=$matches[1]', 'top');
}

add_action('init', 'custom_rewrite_rule', 10, 0);

function load_template_part($template_name, $part_name = null, $args = null)
{
    ob_start();
    get_template_part($template_name, $part_name, $args);
    $var = ob_get_contents();
    ob_end_clean();

    return $var;
}

add_theme_support('title-tag');

add_action('init', 'remove_heartbeat');
function remove_heartbeat()
{
    wp_deregister_script('heartbeat');
}

add_filter('wpseo_robots', 'seo_robots_modify_search');

function seo_robots_modify_search($robots)
{
    if (is_checkout() || is_search() || is_cart() || strpos($_SERVER['REQUEST_URI'], 'add-to-cart') ||  strpos($_SERVER['REQUEST_URI'], '?') || strpos($_SERVER['REQUEST_URI'], '/feed')
    || strpos($_SERVER['REQUEST_URI'], '&pa_ingredients') || strpos($_SERVER['REQUEST_URI'], '&max_price')|| strpos($_SERVER['REQUEST_URI'], '&min_price') ||  strpos($_SERVER['REQUEST_URI'], '&product_tag') ) {
        return "noindex, nofollow";
    } else {
        return $robots;
    }

}

function yappo_faq_row($question, $answer)
{
    ?>
  <div class="slide-wrap">
    <div class="slide-header">
      <span class="glyphicon glyphicon-chevron-down"></span>
      <h4>
          <?= $question ?>
      </h4>

      <span class="span-plus"></span>
    </div>
    <div class="slide-content">
        <?= $answer ?>
    </div>
  </div>
    <?php
}

add_filter('wpml_hreflangs', 'removeDefHreflangs');
function removeDefHreflangs($hreflangs)
{
    foreach ($hreflangs as $key => $lang) {
        if ($key == "x-default") {
            unset ($hreflangs[$key]);
        }
    }
    return $hreflangs;
}


//function add_custom_attr($tag, $handle, $src)
//{
//    $scriptArr = array('yappo-swiper', 'jquery-core');
//
//    if (in_array($handle, $scriptArr)) {
//        $tag = str_replace('src=', 'sync="false" src=', $tag);
//    }
//    return $tag;
//}
//
//add_filter('script_loader_tag', 'add_custom_attr', 10, 3);
