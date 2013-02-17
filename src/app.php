<?php

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new SessionServiceProvider(), array(
    'session.storage.save_path' => __DIR__.'/../cache/session'
));

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
    return $twig;
}));

$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'nl',
));
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/locales/en.yml', 'en');
    $translator->addResource('yaml', __DIR__.'/locales/nl.yml', 'nl');

    return $translator;
}));

/*
 * Event functions
 */
$app['event.findAll'] = $app->protect(function () use ($app) {
    $q = '%' . $app['session']->get('q') . '%';
    $statement = $app['db']->executeQuery('SELECT * FROM event WHERE tags LIKE :q', array( 'q' => $q));
    return $statement->fetchAll();
});
$app['event.find'] = $app->protect(function ($id) use ($app) {
    $statement = $app['db']->executeQuery('SELECT * FROM event WHERE id = ?', array($id));
    return $statement->fetch();
});
$app['event.delete'] = $app->protect(function ($id) use ($app) {
    return $app['db']->delete('event',array('id' => $id));
});
$app['event.insert'] = $app->protect(function ($data) use ($app) {
    return $app['db']->insert('event', $data);
});


return $app;
