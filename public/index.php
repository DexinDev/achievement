<?php
/**
 * @author Dmitry Groza <boxfrommars@gmail.com>
 */


$loader = require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->get('/', function() use ($app) {
    return $app['twig']->render('layout.twig', array(
        'content' => 'Главная'
    ));
});

$users = array(
    1 => array('name' => 'Maxim Buyanov'),
    2 => array('name' => 'Alex Li'),
    3 => array('name' => 'Yana Kidalova'),
    4 => array('name' => 'Sofa Efimova'),
);

$app->get('/people', function() use ($app) {
    return $app['twig']->render('layout.twig', array('content' => 'Список людей'));
});

$app->get('/people/{id}', function($id) use ($users, $app)  {

    if (array_key_exists($id, $users)) {
        return $app['twig']->render('layout.twig', array(
            'content' => $app['twig']->render('people/view.twig', array('user' => $users[$id]))
        ));
    } else {
        $app->abort(404, 'Такого пользователя не существует');
    }

});

$app->run();
