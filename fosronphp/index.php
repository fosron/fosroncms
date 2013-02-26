<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/flaskr.db',
    ),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
));

$app->get('/', function() use ($app) {
    $sql = "SELECT title, text, trumpas FROM entries ORDER BY id DESC";
    $post = $app['db']->fetchAll($sql);

    return $app['twig']->render('show_entries.html', array('entries' => $post));
});

$app->get('/puslapis/{id}', function($id) use ($app) {
    $sql = "SELECT title, text FROM entries WHERE trumpas = ?";
    $post = $app['db']->fetchAssoc($sql, array($id));
    
    return $app['twig']->render('page.html', array('title' => $post['title'], 'text' => $post['text']));
});

$app->get('/prideti', function() use ($app){
    
    return $app['twig']->render('forma.html');
});

$app->post('/prideti', function(Request $request) use ($app){
    $title = $request->get('title');
    $text = $request->get('text');
    $trumpas = str_replace(' ', '-', strtolower($title));
    $sql = "INSERT INTO entries (title,text,trumpas) VALUES (?,?,?)";
    $q = $app['db']->executeQuery($sql, array($title,$text,$trumpas));
    
    if($q)
        return $app->redirect('/');
    else
        return $app->abort(404, "Klaida.");
});

$app->run();
