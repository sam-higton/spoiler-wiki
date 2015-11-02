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

/*$app->post('/api/generate-artist', function () use ($app) {
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
    $workTypeId = $app->request->post('work_type_id');

    $canon = new \SpoilerWiki\Canon();
    $canon->setName($name);
    $canon->setDescription($description);
    $canon->setPrimaryArtistId($artistId);
    $canon->setWorkTypeId($workTypeId);
    $canonId = $canon->save();
    echo "id: " . $canonId;
});

$app->post('/api/generate-work', function () use ($app) {
    $name = $app->request->post('name');
    $description = $app->request->post('description');
    $order = $app->request->post('order');
    $artistId = $app->request->post('artist_id');
    $canonId = $app->request->post('canon_id');


    $work = new \SpoilerWiki\Work();
    $work->setName($name);
    $work->setDescription($description);
    $work->setRank($order);
    $work->setPrimaryArtistId($artistId);
    $work->setCanonId($canonId);

    $workId = $work->save();
    echo "id: " . $workId;
});

$app->post('/api/generate-work-type', function () use ($app) {
    $name = $app->request->post('name');
    $workLabel = $app->request->post('work_label');
    $milestoneLabel = $app->request->post('milestone_label');

    $workType = new \SpoilerWiki\WorkType();
    $workType->setName($name);
    $workType->setWorkLabel($workLabel);
    $workType->setmilestoneLabel($milestoneLabel);
    $workTypeId = $workType->save();
    echo "id: " . $workTypeId;

});

$app->post('/api/generate-topic', function () use ($app) {

});

$app->post('/api/generate-milestone', function () use ($app) {

});*/

$propelApi = new \SpoilerWiki\PropelApi($app,'../schema.xml');
$propelApi->generateRoutes();

$app->run();