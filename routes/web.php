<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', function () {
    return 'Login Endpoint';
});

$router->post('/register', function () {
    return 'Register Endpoint';
});

$router->get('/users', function () {
    return 'Get all users data endpoint';
});

$router->get('/articles', function () {
    return 'Get all articles';
});

$router->post('/article', function () {
    return 'Post some articles';
});

$router->patch('/article/{articleId}', function ($articleId) {
    return 'Patch some article at id: ' . $articleId;
});

$router->delete('/article/{articleId}', function ($articleId) {
    return 'Destroy some article at id: ' . $articleId;
});
