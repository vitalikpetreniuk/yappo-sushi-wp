<?php

namespace Menu;
class Menu extends \CG_Default {
	public function __construct() {
		$this->title            = __( 'Меню', 'custom-gutenberg' ); //human-readable title
		$this->name             = 'menu'; //slug
		$this->icon             = 'admin-comments';
		$this->category         = 'theme';
		$this->previewImagePath = __DIR__ . '/screenshot.png';
		$this->renderPath       = __DIR__ . '/template.php';

		parent::__construct();
	}

	public function fields() {
		return [
			array(
				'key'                 => 'field_649c33e59e18c',
				'label'               => 'Категорії',
				'name'                => 'categories',
				'aria-label'          => '',
				'type'                => 'repeater',
				'instructions'        => '',
				'required'            => 0,
				'conditional_logic'   => 0,
				'wrapper'             => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'wpml_cf_preferences' => 1,
				'layout'              => 'block',
				'pagination'          => 0,
				'min'                 => 0,
				'max'                 => 0,
				'collapsed'           => '',
				'button_label'        => 'Додати рядок',
				'rows_per_page'       => 20,
				'sub_fields'          => array(
					array(
						'key'                 => 'field_649c2fade9553',
						'label'               => 'Категорія',
						'name'                => 'category',
						'aria-label'          => '',
						'type'                => 'taxonomy',
						'instructions'        => '',
						'required'            => 0,
						'conditional_logic'   => 0,
						'wrapper'             => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'wpml_cf_preferences' => 1,
						'taxonomy'            => 'product_cat',
						'add_term'            => 0,
						'save_terms'          => 0,
						'load_terms'          => 0,
						'return_format'       => 'object',
						'field_type'          => 'select',
						'allow_null'          => 1,
						'multiple'            => 0,
						'parent_repeater'     => 'field_649c33e59e18c',
					),
					array(
						'key'                 => 'field_649c32f0e9554',
						'label'               => 'Зображення',
						'name'                => 'image',
						'aria-label'          => '',
						'type'                => 'textarea',
						'instructions'        => '',
						'required'            => 0,
						'conditional_logic'   => 0,
						'wrapper'             => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'wpml_cf_preferences' => 2,
						'default_value'       => '',
						'maxlength'           => '',
						'rows'                => '',
						'placeholder'         => '',
						'new_lines'           => '',
						'parent_repeater'     => 'field_649c33e59e18c',
					),
					array(
						'key'                 => 'field_649c34d859e03',
						'label'               => 'Розмір',
						'name'                => 'size',
						'aria-label'          => '',
						'type'                => 'radio',
						'instructions'        => '',
						'required'            => 0,
						'conditional_logic'   => 0,
						'wrapper'             => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'wpml_cf_preferences' => 1,
						'choices'             => array(
							'third' => '1/3',
							'half'  => '1/2',
						),
						'default_value'       => '',
						'return_format'       => 'value',
						'allow_null'          => 0,
						'other_choice'        => 0,
						'layout'              => 'vertical',
						'save_other_choice'   => 0,
						'parent_repeater'     => 'field_649c33e59e18c',
					),
				),
			),
		];
	}

	public function styles() {
		//for instance plugin_dir_url(__FILE__) . '/style.css',
		return;
	}
}
