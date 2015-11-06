<?php
namespace SpoilerWiki\Widget;

class CanonList extends BaseWidget {


    public function __construct () {

        $this->templatePath = "partials/widgets/CanonList.twig";
        $this->viewData['foo'] = "bar";
        return $this;
    }

}