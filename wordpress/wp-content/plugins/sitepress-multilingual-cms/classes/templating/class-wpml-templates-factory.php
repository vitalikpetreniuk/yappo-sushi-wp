<?php
use WPML\FP\Obj;

use WPML\Core\Twig_Environment;
use WPML\Core\Twig_Error_Syntax;

abstract class WPML_Templates_Factory {
	const NOTICE_GROUP                 = 'template_factory';
	const OTGS_TWIG_CACHE_DISABLED_KEY = '_otgs_twig_cache_disabled';

	/** @var array */
	protected $custom_filters;

	/** @var array */
	protected $custom_functions;

	/** @var string|array */
	protected $template_paths;

	/** @var string|bool */
	protected $cache_directory;

	protected $template_string;

	/** @var WPML_WP_API $wp_api */
	private $wp_api;

	/** @var Twig_Environment */
	protected $twig;

	/**
	 * WPML_Templates_Factory constructor.
	 *
	 * @param array       $custom_functions
	 * @param array       $custom_filters
	 * @param WPML_WP_API $wp_api
	 */
	public function __construct( array $custom_functions = array(), array $custom_filters = array(), $wp_api = null ) {
		$this->init_template_base_dir();
		$this->custom_functions = $custom_functions;
		$this->custom_filters   = $custom_filters;

		if ( $wp_api ) {
			$this->wp_api = $wp_api;
		}
	}

	abstract protected function init_template_base_dir();

	/**
	 * @param ?string $template
	 * @param ?array<string,mixed> $model
	 *
	 * @throws \WPML\Core\Twig\Error\LoaderError
	 * @throws \WPML\Core\Twig\Error\RuntimeError
	 * @throws \WPML\Core\Twig\Error\SyntaxError
	 */
	public function show( $template = null, $model = null ) {
		echo $this->get_view( $template, $model );
	}

	/**
	 * @param ?string $template
	 * @param ?array<string,mixed> $model
	 *
	 * @return string
	 * @throws \WPML\Core\Twig\Error\LoaderError
	 * @throws \WPML\Core\Twig\Error\RuntimeError
	 * @throws \WPML\Core\Twig\Error\SyntaxError
	 */
	public function get_view( $template = null, $model = null ) {
		$output = '';
		$this->maybe_init_twig();

		if ( null === $model ) {
			$model = $this->get_model();
		}
		if ( null === $template ) {
			$template = $this->get_template();
		}

		try {
			$output = $this->twig->render( $template, $model );
		} catch ( RuntimeException $e ) {
			if ( $this->is_caching_enabled() ) {
				$this->disable_twig_cache();
				$this->twig = null;
				$this->maybe_init_twig();
				$output = $this->get_view( $template, $model );
			} else {
				$this->add_exception_notice( $e );
			}
		} catch ( Twig_Error_Syntax $e ) {
			$message = 'Invalid Twig template string: ' . $e->getRawMessage() . "\n" . $template;
			$this->get_wp_api()->error_log( $message );
		}

		return $output;
	}

	protected function maybe_init_twig() {
		if ( ! $this->twig ) {
			$loader = $this->get_twig_loader();

			$environment_args = array();

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$environment_args['debug'] = true;
			}

			if ( $this->is_caching_enabled() ) {
				$wpml_cache_directory  = new WPML_Cache_Directory( $this->get_wp_api() );
				$this->cache_directory = $wpml_cache_directory->get( 'twig' );

				if ( $this->cache_directory ) {
					$environment_args['cache']       = $this->cache_directory;
					$environment_args['auto_reload'] = true;
				} else {
					$this->disable_twig_cache();
				}
			}

			$this->twig = $this->get_wp_api()->get_twig_environment( $loader, $environment_args );
			if ( $this->custom_functions && count( $this->custom_functions ) > 0 ) {
				foreach ( $this->custom_functions as $custom_function ) {
					$this->twig->addFunction( $custom_function );
				}
			}
			if ( $this->custom_filters && count( $this->custom_filters ) > 0 ) {
				foreach ( $this->custom_filters as $custom_filter ) {
					$this->twig->addFilter( $custom_filter );
				}
			}
			if ( Obj::propOr( false, 'debug', $environment_args ) ) {
				$this->twig->addExtension( new \WPML\Core\Twig\Extension\DebugExtension() );
			}
		}
	}

	abstract public function get_template();

	abstract public function get_model();

	/**
	 * @return Twig_Environment
	 */
	protected function get_twig() {
		return $this->twig;
	}

	/**
	 * @param RuntimeException $e
	 */
	protected function add_exception_notice( RuntimeException $e ) {
		if ( false !== strpos( $e->getMessage(), 'create' ) ) {
			/* translators: %s: Cache directory path */
			$text = sprintf( __( 'WPML could not create a cache directory in %s', 'sitepress' ), $this->cache_directory );
		} else {
			/* translators: %s: Cache directory path */
			$text = sprintf( __( 'WPML could not write in the cache directory: %s', 'sitepress' ), $this->cache_directory );
		}
		$notice = new WPML_Notice( 'exception', $text, self::NOTICE_GROUP );
		$notice->set_dismissible( true );
		$notice->set_css_class_types( 'notice-error' );
		$admin_notices = $this->get_wp_api()->get_admin_notices();
		$admin_notices->add_notice( $notice );
	}

	/**
	 * @return WPML_WP_API
	 */
	protected function get_wp_api() {
		if ( ! $this->wp_api ) {
			$this->wp_api = new WPML_WP_API();
		}

		return $this->wp_api;
	}

	protected function disable_twig_cache() {
		update_option( self::OTGS_TWIG_CACHE_DISABLED_KEY, true, 'no' );
	}

	protected function is_caching_enabled() {
		return ! (bool) get_option( self::OTGS_TWIG_CACHE_DISABLED_KEY, false );
	}

	/**
	 * @return bool
	 */
	protected function is_string_template() {
		return isset( $this->template_string );
	}

	/**
	 * @return \WPML\Core\Twig_LoaderInterface
	 */
	protected function get_twig_loader() {
		if ( $this->is_string_template() ) {
			$loader = $this->get_wp_api()->get_twig_loader_string();
		} else {
			$loader = $this->get_wp_api()->get_twig_loader_filesystem( $this->template_paths );
		}

		return $loader;
	}
}
