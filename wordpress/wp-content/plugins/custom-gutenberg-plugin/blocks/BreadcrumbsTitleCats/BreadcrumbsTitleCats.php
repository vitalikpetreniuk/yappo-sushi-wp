<?php
namespace BreadcrumbsTitleCats;
class BreadcrumbsTitleCats extends \CG_Default
{
    public function __construct()
    {
        $this->title = __('Заголовок і бредкрамбс для категорій', 'custom-gutenberg'); //human-readable title
        $this->name = 'breadcrumbstitlecats'; //slug
        $this->icon = 'admin-comments';
        $this->category = 'theme';
        $this->previewImagePath = __DIR__ . '/screenshot.png';
        $this->renderPath = __DIR__ . '/template.php';

        parent::__construct();
    }

    public function fields()
    {
        return [

        ];
    }

    public function styles()
    {
        //for instance plugin_dir_url(__FILE__) . '/style.css',
        return;
    }
}
