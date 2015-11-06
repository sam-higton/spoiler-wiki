<?php
namespace SpoilerWiki\Widget;

class CanonList extends BaseWidget {

    protected $templatePath = "partials/widgets/CanonList.twig";

    public function __construct () {

        $canons = \SpoilerWiki\CanonQuery::create()->find();
        $canonArray = array();
        foreach($canons as $canon) {
            array_push($canonArray, $canon->toArray());
        }

        $this->viewData['canonList'] = $canonArray;
        return $this;
    }

}