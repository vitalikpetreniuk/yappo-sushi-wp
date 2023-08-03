<?php
/**
 * Yoast SEO: Local plugin file.
 *
 * @package WPSEO_Local\Admin\
 * @since   4.0
 */

use Yoast\WP\SEO\Presenters\Admin\Help_Link_Presenter;

if ( ! defined( 'WPSEO_LOCAL_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'WPSEO_Local_Admin_General_Settings' ) ) {

	/**
	 * WPSEO_Local_Admin_General_Settings class.
	 *
	 * Build the WPSEO Local admin form.
	 *
	 * @since   4.0
	 */
	class WPSEO_Local_Admin_General_Settings {

		/**
		 * Holds the slug for this settings tab.
		 *
		 * @var string
		 */
		private $slug = 'general';

		/**
		 * Holds the API keys repository.
		 *
		 * @var WPSEO_Local_Api_Keys_Repository
		 */
		private $api_repository;

		/**
		 * Hold the Google Maps API key set in the Local SEO Options.
		 *
		 * @var string
		 */
		private $api_key;

		/**
		 * Stores the options for this plugin.
		 *
		 * @var mixed
		 */
		private $options;

		/**
		 * WPSEO_Local_Admin_General_Settings constructor.
		 */
		public function __construct() {
			$this->get_options();

			add_filter( 'wpseo_local_admin_tabs', [ $this, 'create_tab' ] );
			add_filter( 'wpseo_local_admin_help_center_video', [ $this, 'set_video' ] );
			$this->api_repository = new WPSEO_Local_Api_Keys_Repository();

			$this->get_api_key();

			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'introductory_copy' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'multiple_locations' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'single_location_settings' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'multiple_locations_settings' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'generic_location_settings' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'address_format' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'woocommerce_setting' ], 10 );
			add_action( 'wpseo_local_admin_' . $this->slug . '_content', [ $this, 'local_config' ], 10 );

			add_action( 'wp_ajax_multiple_locations_location_data', [ $this, 'multiple_locations_location_data' ], 10 );
		}

		/**
		 * Fetches data required for multiple locations select to allow user to select a primary location
		 */
		public function multiple_locations_location_data() {
			$repo = new WPSEO_Local_Locations_Repository();

			$query = $repo->get_filter_locations( [] );
			$posts = $query->posts;

			$results = [];

			foreach ( $posts as $post_id ) {
				$results[] = [
					'id'   => $post_id,
					'text' => esc_html( get_the_title( $post_id ) ),
				];
			}

			wp_send_json_success( $results );
		}

		/**
		 * Get wpseo_local options.
		 */
		private function get_options() {
			$this->options = get_option( 'wpseo_local' );
		}

		/**
		 * Get the API key set in Local SEO Options.
		 */
		private function get_api_key() {
			$this->api_key = $this->api_repository->get_api_key( 'browser' );
		}

		/**
		 * @param array $tabs Array holding the tabs.
		 *
		 * @return mixed
		 */
		public function create_tab( $tabs ) {
			$tabs[ $this->slug ] = [
				'tab_title'     => __( 'Business info', 'yoast-local-seo' ),
				'content_title' => __( 'Business info', 'yoast-local-seo' ),
			];

			return $tabs;
		}

		/**
		 * @param array $videos Array holding the videos for the help center.
		 *
		 * @return mixed
		 */
		public function set_video( $videos ) {
			$videos[ $this->slug ] = 'https://yoa.st/screencast-local-settings';

			return $videos;
		}

		/**
		 * Add local config action.
		 */
		public function local_config() {
			do_action( 'wpseo_local_config' );
		}

		/**
		 * Introductory copy before starting about the multiple locations settings.
		 */
		public function introductory_copy() {
			WPSEO_Local_Admin_Page::section_before( 'introductory-copy' );
			echo '<p>';
			esc_html_e( 'Set up the location of your business with the form below. This information will be used in the search results, and can be used to add blocks with contact information or a map to a page or post on your website.', 'yoast-local-seo' );
			echo '</p>';
			echo '<p>';
			printf(
			/* translators: 1: link open tag; 2: link close tag. %3$s expands to Yoast SEO */
				esc_html__( 'If you have multiple locations, %3$s will create a new Custom Post Type where you can manage your locations. %1$sRead more about managing multiple locations with CPTs%2$s.', 'yoast-local-seo' ),
				'<a href="' . WPSEO_Shortlinker::get( 'https://yoa.st/4fi' ) . '" target="_blank">',
				WPSEO_Admin_Utils::get_new_tab_message() . '</a>',
				'Yoast SEO'
			);
			echo '</p>';
			WPSEO_Local_Admin_Page::section_after(); // End introductory-copy section.
		}

		/**
		 * Multiple locations checkbox.
		 */
		public function multiple_locations() {
			WPSEO_Local_Admin_Page::section_before( 'select-multiple-locations' );
			//WPSEO_Local_Admin_Wrappers::checkbox( 'use_multiple_locations', '', __( 'Use multiple locations', 'yoast-local-seo' ) );

			Yoast_Form::get_instance()->light_switch(
				'use_multiple_locations',
				__( 'My business has multiple locations', 'yoast-local-seo' ),
				[
					__( 'No', 'yoast-local-seo' ),
					__( 'Yes', 'yoast-local-seo' ),
				]
			);

			WPSEO_Local_Admin_Page::section_after(); // End select-multiple-locations section.
		}

		/**
		 * Generic locations settings section.
		 *
		 * @return void
		 */
		public function generic_location_settings() {
			$should_not_use_address_fields = wpseo_has_multiple_locations();
			$should_hide_form              = wpseo_has_multiple_locations() && ! wpseo_multiple_location_one_organization();
			$should_disable_form           = wpseo_has_multiple_locations() && ! wpseo_may_use_multiple_locations_shared_business_info();

			WPSEO_Local_Admin_Page::section_before( 'business-info-settings', ( $should_hide_form ) ? 'display: none;' : '', ( $should_disable_form ) ? 'wpseo-local-form-elements-disabled' : '' );

			$business_types_repo      = new WPSEO_Local_Business_Types_Repository();
			$flattened_business_types = $business_types_repo->get_business_types();
			$business_types_help      = new WPSEO_Local_Admin_Help_Panel(
				'business_types_help',
				__( 'Help with: Business types', 'yoast-local-seo' ),
				sprintf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag. */
					__( 'If your business type is not listed, please read %1$sthe FAQ entry%2$s.', 'yoast-local-seo' ),
					'<a href="' . WPSEO_Shortlinker::get( 'https://yoa.st/business-listing' ) . '" target="_blank">',
					WPSEO_Admin_Utils::get_new_tab_message() . '</a>'
				),
				'has-wrapper'
			);

			$price_indication_help = new WPSEO_Local_Admin_Help_Panel(
				'price_indication_help',
				__( 'Help with: Price indication', 'yoast-local-seo' ),
				esc_html__( 'Select the price indication of your business, where $ is cheap and $$$$$ is expensive.', 'yoast-local-seo' ),
				'has-wrapper'
			);

			$area_served_help = new WPSEO_Local_Admin_Help_Panel(
				'area_served_help',
				__( 'Help with: Area served', 'yoast-local-seo' ),
				esc_html__( 'The geographic area where a service or offered item is provided.', 'yoast-local-seo' ),
				'has-wrapper'
			);

			echo '<div class="wpseo-local-help-wrapper">';
			WPSEO_Local_Admin_Wrappers::select( 'business_type', apply_filters( 'yoast-local-seo-admin-label-business-type', __( 'Business type', 'yoast-local-seo' ) . $business_types_help->get_button_html() ), $flattened_business_types, '', [ 'disabled' => $should_disable_form ] );

			echo $business_types_help->get_panel_html();
			echo '</div> <!-- .wpseo-local-help-wrapper -->';

			WPSEO_Local_Admin_Page::section_before( 'non-shared-business-info', ( $should_not_use_address_fields ) ? 'display: none;' : '', ( $should_not_use_address_fields ) ? 'wpseo-local-form-elements-disabled' : '' );

			WPSEO_Local_Admin_Wrappers::textinput(
				'location_address',
				apply_filters( 'yoast-local-seo-admin-label-business-address', __( 'Business address', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_address_input',
					'disabled' => $should_not_use_address_fields,
				]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_address_2',
				apply_filters( 'yoast-local-seo-admin-label-business-address-2', __( 'Business address line 2', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_not_use_address_fields ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_city',
				apply_filters( 'yoast-local-seo-admin-label-business-city', __( 'Business city', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_city_input',
					'disabled' => $should_not_use_address_fields,
				]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_state',
				apply_filters( 'yoast-local-seo-admin-label-business-state', __( 'Business state', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_state_input',
					'disabled' => $should_not_use_address_fields,
				]
			);

			if ( empty( trim( $this->options['location_zipcode'] ) ) || empty( trim( $this->options['location_country'] ) ) ) {
				echo WPSEO_Local_Admin::get_missing_zipcode_country_alert();
			}
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_zipcode',
				apply_filters( 'yoast-local-seo-admin-label-business-zipcode', __( 'Business zipcode', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_zipcode_input',
					'disabled' => $should_not_use_address_fields,
				]
			);
			WPSEO_Local_Admin_Wrappers::select(
				'location_country',
				apply_filters( 'yoast-local-seo-admin-label-business-country', __( 'Business country', 'yoast-local-seo' ) ),
				WPSEO_Local_Frontend::get_country_array(),
				'',
				[ 'disabled' => $should_not_use_address_fields ]
			);

			// Lat/Lng section.
			WPSEO_Local_Admin_Page::section_before( 'location-coordinates-settings-wrapper', 'clear: both;' );
			if ( $this->api_key !== '' && empty( $this->options['location_coords_lat'] ) && empty( $this->options['location_coords_long'] ) ) {
				WPSEO_Local_Admin::display_notification( esc_html__( 'You\'ve set a Google Maps API Key. By using the button below, you can automatically calculate the coordinates that match the entered business address', 'yoast-local-seo' ), 'info' );
			}
			if ( $this->api_key === '' ) {
				echo '<p>';
				printf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag; 3: HTML <a> open tag; 4: <a> close tag. */
					esc_html__( 'To determine the exact location of your business, search engines need to know its latitude and longitude coordinates. You can %1$smanually enter%2$s these coordinates below. If you\'ve entered a %3$sGoogle Maps API Key%4$s the coordinates will automatically be calculated.', 'yoast-local-seo' ),
					'<a href="https://support.google.com/maps/answer/18539?co=GENIE.Platform%3DDesktop&hl=en" target="_blank">',
					WPSEO_Admin_Utils::get_new_tab_message() . '</a>',
					'<a href="' . esc_url( admin_url( 'admin.php?page=wpseo_local#top#api_keys' ) ) . '" data-action="link-to-tab" data-tab-id="api_keys">',
					'</a>'
				);
				echo '</p>';
			}
			echo '<div id="location-coordinates-settings-lat-lng-wrapper">';
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_coords_lat',
				apply_filters( 'yoast-local-seo-admin-label-business-lat', __( 'Latitude', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_lat_input',
					'disabled' => $should_not_use_address_fields,
				]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_coords_long',
				apply_filters( 'yoast-local-seo-admin-label-business-long', __( 'Longitude', 'yoast-local-seo' ) ),
				'',
				[
					'class'    => 'wpseo_local_lng_input',
					'disabled' => $should_not_use_address_fields,
				]
			);
			if ( ! empty( $this->api_key ) ) {
				echo '<button class="button calculate_lat_lng_button" id="calculate_lat_lng_button" type="button">' . esc_html__( 'Calculate coordinates', 'yoast-local-seo' ) . '</button>';
			}
			echo '</div>';
			WPSEO_Local_Admin_Page::section_after(); // End location-coordinates-settings-wrapper section.

			WPSEO_Local_Admin_Page::section_after(); // End non-shared-business-info section.

			WPSEO_Local_Admin_Wrappers::textinput(
				'location_phone',
				apply_filters( 'yoast-local-seo-admin-label-business-phone', __( 'Business phone', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_phone_2nd',
				apply_filters( 'yoast-local-seo-admin-label-business-phone-2', __( '2nd Business phone', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_fax',
				apply_filters( 'yoast-local-seo-admin-label-business-fax', __( 'Business fax', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_email',
				apply_filters( 'yoast-local-seo-admin-label-business-email', __( 'Business email', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_url',
				apply_filters( 'yoast-local-seo-admin-label-business-url', __( 'URL', 'yoast-local-seo' ) ),
				'',
				[
					'placeholder' => WPSEO_Sitemaps_Router::get_base_url( '' ),
					'disabled'    => $should_disable_form,
				]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_vat_id',
				apply_filters( 'yoast-local-seo-admin-label-business-vat-id', __( 'VAT ID', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_tax_id',
				apply_filters( 'yoast-local-seo-admin-label-business-tax-id', __( 'Tax ID', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);
			WPSEO_Local_Admin_Wrappers::textinput(
				'location_coc_id',
				apply_filters( 'yoast-local-seo-admin-label-business-coc-id', __( 'Chamber of Commerce ID', 'yoast-local-seo' ) ),
				'',
				[ 'disabled' => $should_disable_form ]
			);

			echo '<div class="wpseo-local-help-wrapper">';
			WPSEO_Local_Admin_Wrappers::select( 'location_price_range', apply_filters( 'yoast-local-seo-admin-label-business-price-range', __( 'Price indication', 'yoast-local-seo' ) . $price_indication_help->get_button_html() ), $this->get_pricerange_array(), '', [ 'disabled' => $should_disable_form ] );
			echo $price_indication_help->get_panel_html();
			echo '</div>';

			WPSEO_Local_Admin_Wrappers::textinput( 'location_currencies_accepted', apply_filters( 'yoast-local-seo-admin-label-business-currencies-accepted', __( 'Currencies accepted', 'yoast-local-seo' ) ), '', [ 'disabled' => $should_disable_form ] );
			WPSEO_Local_Admin_Wrappers::textinput( 'location_payment_accepted', apply_filters( 'yoast-local-seo-admin-label-business-payment-accepted', __( 'Payment methods accepted', 'yoast-local-seo' ) ), '', [ 'disabled' => $should_disable_form ] );

			echo '<div class="wpseo-local-help-wrapper">';
			WPSEO_Local_Admin_Wrappers::textinput( 'location_area_served', apply_filters( 'yoast-local-seo-admin-label-business-area-served', __( 'Area served', 'yoast-local-seo' ) . $area_served_help->get_button_html() ), '', [ 'disabled' => $should_disable_form ] );
			echo $area_served_help->get_panel_html();
			echo '</div>';

			$this->add_coordinates_settings();

			WPSEO_Local_Admin_Page::section_after(); // End business-info-settings section.
		}

		/**
		 * Single locations settings section.
		 *
		 * @return void
		 */
		public function single_location_settings() {
			WPSEO_Local_Admin_Page::section_before( 'single-location-settings', 'clear: both; ' . ( wpseo_has_multiple_locations() ? 'display: none;' : '' ) );
			$company_name = WPSEO_Options::get( 'company_name' );
			$company_logo = WPSEO_Options::get( 'company_logo' );

			echo '<p>';
			if ( ! empty( $company_name ) || ! empty( $company_logo ) ) {

				printf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag; 3: "Yoast SEO". */
					esc_html__( 'You can change your current company name and logo within the general settings of the Search Appearance of %3$s. %1$sGo to the settings%2$s.', 'yoast-local-seo' ),
					'<a href="' . esc_url( admin_url( 'admin.php?page=wpseo_titles#top#general' ) ) . '">',
					'</a>',
					'Yoast SEO'
				);
			}
			else {
				printf(
				/* translators: 1: HTML <a> open tag; 2: <a> close tag; 3: "Yoast SEO". */
					esc_html__( 'You can set up your company name and logo within the general settings of the Search Appearance of %3$s. %1$sChange your company settings%2$s.', 'yoast-local-seo' ),
					'<a href="' . esc_url( admin_url( 'admin.php?page=wpseo_titles#top#general' ) ) . '">',
					'</a>',
					'Yoast SEO'
				);
			}
			echo '</p>';

			WPSEO_Local_Admin_Page::section_after(); // End single-location-settings section.
		}

		/**
		 * Multiple locations settings section.
		 *
		 * @return void
		 */
		public function multiple_locations_settings() {
			$post_type = PostType::get_instance()->get_post_type();

			WPSEO_Local_Admin_Page::section_before( 'multiple-locations-settings', 'clear: both; ' . ( wpseo_has_multiple_locations() ? '' : 'display: none;' ) );

			echo '<p>';
			printf(
			/* translators: 1: link open tag; 2: link close tag. */
				esc_html__( 'You have selected the multiple locations option, so we added the Locations Post Type for you in the menu on the left. Now you can start adding the locations %1$sright here%2$s.', 'yoast-local-seo' ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=' . $post_type ) ) . '" target="_blank">',
				'</a>'
			);
			echo '</p>';

			echo '<p>' . esc_html__( 'You can find some advanced settings regarding multiple locations under the Advanced tab', 'yoast-local-seo' ) . '</p>';

			Yoast_Form::get_instance()->light_switch(
				'multiple_locations_same_organization',
				__( 'All locations are part of the same business', 'yoast-local-seo' ),
				[
					__( 'No', 'yoast-local-seo' ),
					__( 'Yes', 'yoast-local-seo' ),
				]
			);

			WPSEO_Local_Admin_Page::section_before( 'multiple-locations-same-organization-settings', 'clear: both; ' . ( wpseo_multiple_location_one_organization() ? '' : 'display: none;' ) );

			$repo      = new WPSEO_Local_Locations_Repository();
			$locations = $repo->get( [ 'post_status' => 'publish' ], false );

			$select_options     = [];
			$select_options[''] = ''; // Add empty item so first option doesn't get selected automatically and so that select2's clear input works as expected.

			if ( wpseo_has_multiple_locations() ) {
				foreach ( $locations as $location_id ) {
					$select_options[ $location_id ] = esc_html( get_the_title( $location_id ) );
				}
			}

			$primary_location_help_link = new Help_Link_Presenter(
				WPSEO_Shortlinker::get( 'https://yoa.st/local-new-2' ),
				__( 'Learn more about the primary location', 'yoast-local-seo' )
			);
			echo '<div class="wpseo-local-form-element-wrapper wpseo-local-form-element-wrapper--has-help">';
			Yoast_Form::get_instance()->select(
				'multiple_locations_primary_location',
				esc_html__( 'Primary location', 'yoast-local-seo' ),
				$select_options,
				'unstyled',
				true,
				[],
				$primary_location_help_link
			);
			echo '</div>';

			$shared_business_info_help_link = new Help_Link_Presenter(
				WPSEO_Shortlinker::get( 'https://yoa.st/local-new-1' ),
				__( 'Learn more about shared business info', 'yoast-local-seo' )
			);
			Yoast_Form::get_instance()->light_switch(
				'multiple_locations_shared_business_info',
				__( 'Locations inherit shared business info', 'yoast-local-seo' ),
				[
					__( 'No', 'yoast-local-seo' ),
					__( 'Yes', 'yoast-local-seo' ),
				],
				true,
				$shared_business_info_help_link
			);

			WPSEO_Local_Admin_Page::section_after(); // End multiple-locations-same-organization-settings section.

			WPSEO_Local_Admin_Page::section_after(); // End multiple-locations-settings section.
		}

		/**
		 * Retrieves array of the 5 pricerange steps.
		 *
		 * @return array Array of pricerange.
		 */
		private function get_pricerange_array() {
			return [
				''      => __( 'Select your price indication', 'yoast-local-seo' ),
				'$'     => '$',
				'$$'    => '$$',
				'$$$'   => '$$$',
				'$$$$'  => '$$$$',
				'$$$$$' => '$$$$$',
			];
		}

		/**
		 * Show the dropdown to select an address format.
		 */
		public function address_format() {
			WPSEO_Local_Admin_Page::section_before( 'wpseo-local-address-format' );
			echo '<h3>' . esc_html__( 'Address format', 'yoast-local-seo' ) . '</h3>';

			$select_options = [
				'address-state-postal'       => '{address} {city}, {state} {zipcode} &nbsp;&nbsp;&nbsp;&nbsp; (New York, NY 12345 )',
				'address-state-postal-comma' => '{address} {city}, {state}, {zipcode} &nbsp;&nbsp;&nbsp;&nbsp; (New York, NY, 12345 )',
				'address-postal-city-state'  => '{address} {zipcode} {city}, {state} &nbsp;&nbsp;&nbsp;&nbsp; (12345 New York, NY )',
				'address-postal'             => '{address} {city} {zipcode} &nbsp;&nbsp;&nbsp;&nbsp; (New York 12345 )',
				'address-postal-comma'       => '{address} {city}, {zipcode} &nbsp;&nbsp;&nbsp;&nbsp; (New York, 12345 )',
				'address-city'               => '{address} {city} &nbsp;&nbsp;&nbsp;&nbsp; (New York)',
				'postal-address'             => '{zipcode} {state} {city} {address} &nbsp;&nbsp;&nbsp;&nbsp; (12345 NY New York)',
			];
			WPSEO_Local_Admin_Wrappers::select(
				'address_format',
				__( 'Address format', 'yoast-local-seo' ),
				$select_options
			);

			/* translators: %s extends to <a href="mailto:pluginsupport@yoast.com">pluginsupport@yoast.com</a> */
			echo '<p style="border:none;">' . sprintf( esc_html__( 'A lot of countries have their own address format. Please choose one that matches yours. If you have something completely different, please let us know via %s.', 'yoast-local-seo' ), '<a href="mailto:pluginsupport@yoast.com">pluginsupport@yoast.com</a>' ) . '</p>';
			WPSEO_Local_Admin_Page::section_after(); // End wpseo-local-address-format section.
		}

		/**
		 * Add a hidden input field to save the local pickup setting in.
		 */
		public function woocommerce_setting() {
			WPSEO_Local_Admin_Wrappers::hidden( 'woocommerce_local_pickup_setting' );
		}

		/**
		 * Adds the maps settings with single location setup.
		 *
		 * @return void
		 */
		public function add_coordinates_settings() {
			WPSEO_Local_Admin_Page::section_before( 'location-coordinates-settings', 'clear: both; ' . ( wpseo_has_multiple_locations() ? 'display: none;' : '' ) );
			echo '<h3>' . esc_html__( 'Location coordinates', 'yoast-local-seo' ) . '</h3>';

			echo '<p>' . esc_html__( 'Here you can enter the latitude and longitude coordinates yourself. If you\'ve entered a Google Maps API Key these coordinates will be automatically calculated. This API Key is also needed for the map to show on your site.', 'yoast-local-seo' ) . '</p>';
			if ( empty( $this->options['location_coords_lat'] ) || empty( $this->options['location_coords_long'] ) ) {
				echo '<p>' . esc_html__( 'In order for automatic lat/long calculation to work, you first need to enter an API code under the API tab at the top of this page', 'yoast-local-seo' ) . '</p>';
			}

			if ( ! empty( $this->api_key ) && ( $this->options['location_coords_lat'] != '' && $this->options['location_coords_long'] != '' ) ) {
				echo '<p>' . esc_html__( 'If the marker is not in the right location for your store, you can drag the pin to the location where you want it.', 'yoast-local-seo' ) . '</p>';

				$args = [
					'echo'       => true,
					'show_route' => false,
					'map_style'  => 'roadmap',
					'draggable'  => true,
				];
				wpseo_local_show_map( $args );

				echo '<br />';
			}
			WPSEO_Local_Admin_Page::section_after(); // End location-coordinates-settings section.
		}
	}
}
