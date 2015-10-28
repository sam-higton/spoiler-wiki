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

$app->post('/api/generate-artist', function () use ($app) {
    $name = $app->request->post('name');
    $bio = $app->request->post('bio');

    $artist = new \SpoilerWiki\Artist();
    $artist->setName($name);
    $artist->setBio($bio);
    $artistId = $artist->save();
    echo "id: " . $artistId;
});

$app->post('/api/generate-canon', function () use ($app) {
    $name = $app->request->post('name');
    $description = $app->request->post('description');
    $artistId = $app->request->post('artist_id');

    $canon = new \SpoilerWiki\Canon();
    $canon->setName($name);
    $canon->setDescription($description);
    $canon->setPrimaryArtistId($artistId);
    $canonId = $canon->save();
    echo "id: " . $canonId;
});

$app->post('/api-generate-work', function () use ($app) {
    $name = $app->request->post('name');
    $description = $app->request->post('description');
    $order = $app->request->post('order');
    $artistId = $app->request->post('artist_id');
    $canonId = $app->request->post('artistId');
    $workTypeId = $app->request->post('work_type_id');

});

$app->run();