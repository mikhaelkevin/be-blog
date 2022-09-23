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

$router->group(['prefix' => 'category', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'CategoryController@getCategories');
    $router->get('/{categoryId}', 'CategoryController@getCategory');
    $router->post('/', 'CategoryController@createCategory');
    $router->patch('/{categoryId}', 'CategoryController@editCategory');
    $router->delete('/{categoryId}', 'CategoryController@deleteCategory');
});

$router->group(['prefix' => 'tag', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'TagController@getTags');
    $router->get('/{tagId}', 'TagController@getTag');
    $router->post('/', 'TagController@createTag');
    $router->patch('/{tagId}', 'TagController@editTag');
    $router->delete('/{tagId}', 'TagController@deleteTag');
});

$router->get('/user/{userId}', ['middleware' => 'auth', 'uses' => 'AuthController@getUserData']);
$router->get('/articles', 'ArticleController@getArticles');
