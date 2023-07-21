<?php
namespace Gallery;
class Gallery extends \CG_Default
{
    public function __construct()
    {
        $this->title = __('Галерея з посиланнями', 'custom-gutenberg'); //human-readable title
        $this->name = 'gallery'; //slug
        $this->icon = 'admin-comments';
        $this->category = 'theme';
        $this->previewImagePath = __DIR__ . '/screenshot.png';
        $this->renderPath = __DIR__ . '/template.php';

        parent::__construct();
    }

    public function fields()
    {
        return array(
            array(
                'key' => 'field_649bf26b7aa32',
                'label' => 'Галерея',
                'name' => 'list_items',
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'wpml_cf_preferences' => 1,
                'layout' => 'table',
                'pagination' => 0,
                'min' => 0,
                'max' => 5,
                'collapsed' => '',
                'button_label' => 'Додати рядок',
                'rows_per_page' => 20,
                'sub_fields' => array(
                    array(
                        'key' => 'field_649bf2ad7aa33',
                        'label' => 'Посилання',
                        'name' => 'link',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'wpml_cf_preferences' => 2,
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_649bf26b7aa32',
                    ),
                    array(
                        'key' => 'field_649bf2cc7aa34',
                        'label' => 'Зображення',
                        'name' => 'image',
                        'aria-label' => '',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'wpml_cf_preferences' => 1,
                        'return_format' => 'array',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'preview_size' => 'thumbnail',
                        'parent_repeater' => 'field_649bf26b7aa32',
                    ),
                ),
            ),
        );
    }

    public function styles()
    {
        //for instance plugin_dir_url(__FILE__) . '/style.css',
        return;
    }
}

