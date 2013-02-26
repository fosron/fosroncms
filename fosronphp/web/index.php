<?php

require_once __DIR__.'../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->before(function (Request $request) {
    print_r($request);
});

$app->get('/hello', function () {
    return 'atlast!';
});

$app->get('/', function () use ($app) {
    return $app->redirect('/hello');
});


$app->run();