<?php

namespace ProductsBlock;
class ProductsBlock extends \CG_Default {
	public function __construct() {
		$this->title            = __( ' Блок з товарами', 'custom-gutenberg' ); //human-readable title
		$this->name             = 'productsblock'; //slug
		$this->icon             = 'admin-comments';
		$this->category         = 'theme';
		$this->previewImagePath = __DIR__ . '/screenshot.png';
		$this->renderPath       = __DIR__ . '/template.php';

		parent::__construct();
	}

	public function fields() {
		return [
			array(
				'key'               => 'field_648836469022b',
				'label'             => 'Заголовок',
				'name'              => 'title',
				'aria-label'        => '',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'maxlength'         => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
			),
			array(
				'key'               => 'field_648836259022a',
				'label'             => 'Група товарів',
				'name'              => 'product_group',
				'aria-label'        => '',
				'type'              => 'select',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'choices'           => array(
					'popular'  => 'Популярне',
					'news'     => 'Новинки',
					'category' => 'Категорія',
				),
				'default_value'     => false,
				'return_format'     => 'value',
				'multiple'          => 0,
				'allow_null'        => 0,
				'ui'                => 0,
				'ajax'              => 0,
				'placeholder'       => '',
			),
			array(
				'key'               => 'field_64885d8de71e5',
				'label'             => 'Категорія',
				'name'              => 'category',
				'aria-label'        => '',
				'type'              => 'taxonomy',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_648836259022a',
							'operator' => '==',
							'value'    => 'category',
						),
					),
				),
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'taxonomy'          => 'product_cat',
				'add_term'          => 1,
				'save_terms'        => 0,
				'load_terms'        => 0,
				'return_format'     => 'id',
				'field_type'        => 'select',
				'allow_null'        => 0,
				'multiple'          => 0,
			),
		];
	}

	public function styles() {
		//for instance plugin_dir_url(__FILE__) . '/style.css',
		return;
	}
}
