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

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/development.log',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'achievments',
        'host' => 'localhost',
        'user' => 'root',
        'password' => null,
    ),
));


$app->get('/', function() use ($app) {
    $app['monolog']->addDebug('Log-log - monolog');
    return $app['twig']->render('layout.twig', array(
        'content' => 'Главная'
    ));
});

$app->get('/people', function() use ($app) {
    return $app['twig']->render('layout.twig', array('content' => 'Список людей'));
});

$app->get('/people/{id}', function($id) use ($app)  {
    $sql = "SELECT count(id) FROM users WHERE id = ?";
    $count = $app['db']->fetchAssoc($sql, array((int) $id));
    if ($count['count(id)'] > 0) {

        $sql = "SELECT name FROM users WHERE id = ?";
        $user = $app['db']->fetchAssoc($sql, array((int) $id));

        return $app['twig']->render('layout.twig', array(
            'content' => $app['twig']->render('people/view.twig', array('user' => $user))
        ));
    } else {
        $app->abort(404, 'Такого пользователя не существует');
    }

});

$app->run();
