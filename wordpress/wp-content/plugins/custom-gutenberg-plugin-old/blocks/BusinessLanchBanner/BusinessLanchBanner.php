<?php
namespace BusinessLanchBanner;
class BusinessLanchBanner extends \CG_Default
{
    public function __construct()
    {
        $this->title = __('Business Lanch Banner', 'custom-gutenberg'); //human-readable title
        $this->name = 'business-lanch-banner'; //slug
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
                'key' => 'field_6495a21da5c232',
                'label' => 'Текст 1',
                'name' => 'text_1',
                'aria-label' => '',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
        );
    }

    public function styles()
    {
        //for instance plugin_dir_url(__FILE__) . '/style.css',
        return;
    }
}

