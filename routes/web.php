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

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
});

$router->group(['prefix' => 'article', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/{articleId}', 'ArticleController@getArticle');
    $router->post('/', 'ArticleController@createArticle');
    $router->patch('/{articleId}', 'ArticleController@editArticle');
    $router->delete('/{articleId}', 'ArticleController@deleteArticle');
});

$router->get('/user/{userId}', ['middleware' => 'auth', 'uses' => 'AuthController@getUserData']);
$router->get('/articles', 'ArticleController@getArticles');
