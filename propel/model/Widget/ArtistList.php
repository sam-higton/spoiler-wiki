<?php
namespace SpoilerWiki\Widget;

class ArtistList extends BaseWidget {
    
    protected $templatePath = "partials/widgets/ArtistList.twig";
    public function __construct () {
        $artists = \SpoilerWiki\ArtistQuery::create()->find();
        $artistArray = array ();
        foreach($artists as $artist) {
            array_push($artistArray, $artist);
        }
        $this->viewData['artistList'] = $artistArray;
    }
    
}