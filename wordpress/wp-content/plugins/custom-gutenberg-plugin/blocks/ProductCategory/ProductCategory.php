<?php

namespace ProductCategory;
class ProductCategory extends \CG_Default {
	public function __construct() {
		$this->title            = __( 'Товари по категорії', 'custom-gutenberg' ); //human-readable title
		$this->name             = 'productcategory'; //slug
		$this->icon             = 'admin-comments';
		$this->category         = 'theme';
		$this->previewImagePath = __DIR__ . '/screenshot.png';
		$this->renderPath       = __DIR__ . '/template.php';

		parent::__construct();
	}

	public function fields() {
		return [
			array(
				'key'           => 'field_649a9670e2b8a',
				'label'         => 'Категорія',
				'name'          => 'category',
				'aria-label'    => '',
				'type'          => 'taxonomy',
				'instructions'  => '',
				'required'      => 0,
				'wrapper'       => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'taxonomy'      => 'product_cat',
				'add_term'      => 0,
				'save_terms'    => 0,
				'load_terms'    => 0,
				'return_format' => 'id',
				'field_type'    => 'select',
				'allow_null'    => 1,
				'multiple'      => 0,
			),
		];
	}

	public function styles() {
		//for instance plugin_dir_url(__FILE__) . '/style.css',
		return;
	}
}
