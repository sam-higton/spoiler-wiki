<?php
require "../vendor/autoload.php";
require "../propel/conf/config.php";

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

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

$propelApi = new \SpoilerWiki\PropelApi($app,'../schema.xml');
$propelApi->generateRoutes();

$app->run();