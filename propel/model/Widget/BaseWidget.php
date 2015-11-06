<?php
namespace SpoilerWiki\Widget;

class BaseWidget {
    protected $templatePath = "partials/widgets/BaseWidget.twig";
    protected $viewData = array ();

    public function setWidgetPath ($path) {
        $this->templatePath = $path;
    }

    public function getWidgetPath () {
        return $this->templatePath;
    }


    public function getViewData () {
        return $this->viewData;
    }

    public function view () {

        return array (
           "templatePath" => $this->templatePath,
           "viewData" => $this->viewData
        );
    }

    static function create () {
        $class = get_called_class();
        return new $class();
    }


}