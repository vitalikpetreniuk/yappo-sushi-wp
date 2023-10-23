<?php
namespace WhatPayReceive;
class WhatPayReceive extends \CG_Default
{
    public function __construct()
    {
        $this->title = __('Яку оплату приймаєте', 'custom-gutenberg'); //human-readable title
        $this->name = 'whatpayreceive'; //slug
        $this->icon = 'admin-comments';
        $this->category = 'theme';
        $this->previewImagePath = __DIR__ . '/screenshot.png';
        $this->renderPath = __DIR__ . '/template.php';

        parent::__construct();
    }

    public function fields()
    {
        return [];
    }

    public function styles()
    {
        //for instance plugin_dir_url(__FILE__) . '/style.css',
        return;
    }
}
