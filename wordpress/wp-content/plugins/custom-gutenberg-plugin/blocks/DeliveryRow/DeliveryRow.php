<?php
namespace DeliveryRow;
class DeliveryRow extends \CG_Default
{
    public function __construct()
    {
        $this->title = __('Доставка на головній', 'custom-gutenberg'); //human-readable title
        $this->name = 'deliveryrow'; //slug
        $this->icon = 'admin-comments';
        $this->category = 'theme';
        $this->previewImagePath = __DIR__ . '/screenshot.png';
        $this->renderPath = __DIR__ . '/template.php';

        parent::__construct();
    }

    public function fields()
    {
        return [
	        array(
		        'key' => 'field_64886144b8bd6',
		        'label' => 'Заголовок',
		        'name' => 'title',
		        'aria-label' => '',
		        'type' => 'textarea',
		        'instructions' => '',
		        'required' => 0,
		        'conditional_logic' => 0,
		        'wrapper' => array(
			        'width' => '',
			        'class' => '',
			        'id' => '',
		        ),
		        'default_value' => '',
		        'maxlength' => '',
		        'rows' => 3,
		        'placeholder' => '',
		        'new_lines' => '',
	        ),
	        array(
		        'key' => 'field_64886153b8bd7',
		        'label' => 'Текст кнопки',
		        'name' => 'btn_text',
		        'aria-label' => '',
		        'type' => 'text',
		        'instructions' => '',
		        'required' => 0,
		        'conditional_logic' => 0,
		        'wrapper' => array(
			        'width' => '55',
			        'class' => '',
			        'id' => '',
		        ),
		        'default_value' => '',
		        'maxlength' => '',
		        'placeholder' => '',
		        'prepend' => '',
		        'append' => '',
	        ),
	        array(
		        'key' => 'field_64886162b8bd8',
		        'label' => 'Посилання кнопки',
		        'name' => 'btn_link',
		        'aria-label' => '',
		        'type' => 'link',
		        'instructions' => '',
		        'required' => 0,
		        'conditional_logic' => 0,
		        'wrapper' => array(
			        'width' => '45',
			        'class' => '',
			        'id' => '',
		        ),
		        'return_format' => 'url',
	        ),
	        array(
		        'key' => 'field_6488619d9081c',
		        'label' => 'Зображення',
		        'name' => 'image',
		        'aria-label' => '',
		        'type' => 'image',
		        'instructions' => '',
		        'required' => 0,
		        'conditional_logic' => 0,
		        'wrapper' => array(
			        'width' => '',
			        'class' => '',
			        'id' => '',
		        ),
		        'return_format' => 'id',
		        'library' => 'all',
		        'min_width' => '',
		        'min_height' => '',
		        'min_size' => '',
		        'max_width' => '',
		        'max_height' => '',
		        'max_size' => '',
		        'mime_types' => '',
		        'preview_size' => 'medium',
	        ),
        ];
    }

    public function styles()
    {
        //for instance plugin_dir_url(__FILE__) . '/style.css',
        return;
    }
}
