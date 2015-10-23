<?php
require "vendor/autoload.php";

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

$app->get('/', function () use ($app) {
   $app->view()->display('index.twig', array ());
});

$app->get('/series/:id', function ($seriesId) use ($app) {
    $app->view()->display('series.twig', array ());
});

$app->get('/topic/:id', function ($topicId) use ($app) {
    $app->view()->display('topic.twig', array());
});

$app->run();