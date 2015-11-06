<?php
require "../vendor/autoload.php";
require "../propel/conf/config.php";
session_start();
$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

$checkAuth = function ($roles = array()) use ($app) {
    return function () use ($app, $roles) {
        if($app->user) {
            //todo: check roles
        } else {
            $app->redirect('/login?forbidden=true');
        }
    };
};

$app->hook('slim.before.dispatch', function () use ($app) {
    //set up user if exists
    if(isset($_SESSION['user_id']) && $_SESSION['user_id']) {
        $user = \SpoilerWiki\UserQuery::create()->findPK($_SESSION['user_id']);
        $app->user = $user;
        $app->view()->appendData(array (
           "user" => $user->toArray()
        ));
    } else {
        $app->user = false;
        $app->view()->appendData(array (
            "user" => false
        ));
    }
});

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

$app->get('/contribute',$checkAuth(), function () use ($app) {
    $app->view()->display('contribute-home.twig', array(
        "modules" => array (
            \SpoilerWiki\Widget\CanonList::create()->view()
        )
    ));
});

$app->get('/logout', function () use ($app) {
    unset($_SESSION['user_id']);
    $app->redirect('/');
});

$app->map('/login', function () use ($app) {
    $error = false;
    if($app->request->isPost()) {
        $userName = $app->request->post('userName');
        $password = $app->request->post('password');
        $user = \SpoilerWiki\User::authenticate($userName,$password);
        if($user) {
            $_SESSION['user_id'] = $user->getId();
            $app->redirect('/contribute');
        } else {
            $error = true;
        }
    }
    $app->view()->display('login.twig', array(
        "register_redirect" => ($app->request->get('registered')),
        "forbidden" => ($app->request->get('forbidden')),
        "error" => $error
    ));
})->via('POST','GET');

$app->map('/register', function () use ($app) {
    $errors = array ();
    if($app->request->isPost()) {

        $userName = $app->request->post('userName');
        $emailAddress = $app->request->post('emailAddress');
        $password = $app->request->post('password');
        $passwordConfirm = $app->request->post('confirm-password');
        //attempt to make user
        $isValid = true;
        if(\SpoilerWiki\User::userNameExists($userName)) {
            $isValid = false;
            $errors['username_exists'] = "An account with this user name already exists";
        }

        if($password !== $passwordConfirm) {
            $isValid = false;
            $errors['password_mismatch'] = "The passwords don't match";
        }

        if($emailAddress != "" && \SpoilerWiki\User::emailExists($emailAddress)) {
            $isValid = false;
            $errors['email_exists'] = "An account is already registered with this email address";
        }

        if($isValid) {
            $newUser = new \SpoilerWiki\User();
            $newUser->setUsername($userName);
            $newUser->setPassword($password);
            $newUser->setEmail($emailAddress);
            $newUser->save();
            $app->redirect('/login?registered=true');
        }



    }

    $app->view()->display('register.twig', array(
        "errors" => $errors
    ));
})->via('POST','GET');


$propelApi = new \PropelToSlim\PropelToSlim($app,'../schema.xml');
$propelApi->generateRoutes();

$artist = new \SpoilerWiki\ArtistQuery();
//$artist->filterBy()

$app->run();