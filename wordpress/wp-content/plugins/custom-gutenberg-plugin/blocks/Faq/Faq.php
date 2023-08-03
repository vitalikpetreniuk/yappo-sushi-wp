<?php

namespace Faq;
class Faq extends \CG_Default {
	public function __construct() {
		$this->title            = __( 'Faq', 'custom-gutenberg' ); //human-readable title
		$this->name             = 'faq'; //slug
		$this->icon             = 'admin-comments';
		$this->category         = 'theme';
		$this->previewImagePath = __DIR__ . '/screenshot.png';
		$this->renderPath       = __DIR__ . '/template.php';

		parent::__construct();
	}

	public function fields() {
		return [
			array(
				'key'               => 'field_64885f63c850a',
				'label'             => 'Питання-відповідь',
				'name'              => 'questions',
				'aria-label'        => '',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'block',
				'pagination'        => 0,
				'min'               => 0,
				'max'               => 0,
				'collapsed'         => '',
				'button_label'      => 'Додати рядок',
				'rows_per_page'     => 20,
				'sub_fields'        => array(
					array(
						'key'               => 'field_64885f8cc850b',
						'label'             => 'Запитання',
						'name'              => 'question',
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
						'parent_repeater'   => 'field_64885f63c850a',
					),
					array(
						'key'               => 'field_64885f97c850c',
						'label'             => 'Відповідь',
						'name'              => 'answer',
						'aria-label'        => '',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
						'parent_repeater'   => 'field_64885f63c850a',
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
