<?php

namespace TopSlider;
class TopSlider extends \CG_Default {
	public function __construct() {
		$this->title            = __( 'Слайдер на головній', 'custom-gutenberg' ); //human-readable title
		$this->name             = 'topslider'; //slug
		$this->icon             = 'admin-comments';
		$this->category         = 'theme';
		$this->previewImagePath = __DIR__ . '/screenshot.png';
		$this->renderPath       = __DIR__ . '/template.php';

		parent::__construct();
	}

	public function fields() {
		return [
			array(
				'key'               => 'field_6486f2fc875b0',
				'label'             => 'Слайди',
				'name'              => 'slides',
				'type'              => 'repeater',
				'layout'            => 'block',
				'sub_fields'        => array(
					array(
						'key'             => 'field_6486f313875b1',
						'label'           => 'Зображення',
						'name'            => 'image',
						'return_format'   => 'id',
						'type'            => 'image',
						'parent_repeater' => 'field_6486f2fc875b0',
					),
					array(
						'key'             => 'field_6486f327875b2',
						'label'           => 'Посилання',
						'name'            => 'link',
						'type'            => 'link',
						'return_format'   => 'url',
						'parent_repeater' => 'field_6486f2fc875b0',
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
