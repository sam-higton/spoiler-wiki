<?php
require "../vendor/autoload.php";
require "../propel/conf/config.php";
session_start();
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

$checkAuth = function () {
    return function () {
        if(isset($_SESSION['user_id'])) {

        }
    };
};

$app->view->setTemplatesDirectory('../templates');

$app->get('/', function () use ($app) {
    $canonList = \SpoilerWiki\Canon::fetchAll();
   $app->view()->display('index.twig', array (
       "canonList" => $canonList
   ));
});

$app->get('/series/:id', function ($seriesId) use ($app) {
    $app->view()->display('series.twig', array ());
});

$app->get('/topic/:id', function ($topicId) use ($app) {
    $app->view()->display('topic.twig', array());
});

$app->get('/contribute', function () use ($app) {

});

$app->map('/login', function () use ($app) {
    $app->view()->display('login.twig', array());
})->via('POST','GET');

$app->map('/register', function () use ($app) {

})->via('POST','GET');

$propelApi = new \PropelToSlim\PropelToSlim($app,'../schema.xml');
$propelApi->generateRoutes();

//$artist = new \SpoilerWiki\ArtistQuery();
//$artist->filterBy()

$app->run();