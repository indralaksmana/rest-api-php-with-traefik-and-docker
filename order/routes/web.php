<?php

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

if ( ! defined('CONST_PREFIX')) {
    define('CONST_PREFIX', 'prefix');
}

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group([CONST_PREFIX => 'api/v1'], function () use ($router) {
    /**
     * Cart routes
     */
    $router->group([CONST_PREFIX => 'trolley'], function () use ($router) {
        $router->get('/{user_id}', 'v1\CartController@index');
    });
    $router->group([CONST_PREFIX => 'cart'], function () use ($router) {
        $router->get('/{id}', 'v1\CartController@show');
        $router->post('/', 'v1\CartController@store');
        $router->post('/bulk', 'v1\CartController@storeBulk');
        $router->post('/update/{id}', 'v1\CartController@update');
        $router->delete('/{id}', 'v1\CartController@delete');
    });

    /**
     * Promo routes
     */
    $router->group([CONST_PREFIX => 'promo'], function () use ($router) {
        $router->get('/', 'v1\PromoController@index');
        $router->get('/{code}', 'v1\PromoController@show');
        $router->post('/', 'v1\PromoController@store');
        $router->post('/update/{code}', 'v1\PromoController@update');
        $router->delete('/{code}', 'v1\PromoController@delete');
    });

    /**
     * Book routes
     */
    $router->group([CONST_PREFIX => 'book'], function () use ($router) {
        $router->get('/checkout/{user_id}', 'v1\BookController@checkout');
        $router->post('/commit/{user_id}', 'v1\BookController@commit');
    });

    /**
     * Invoice routes
     */
    $router->group([CONST_PREFIX => 'invoice'], function () use ($router) {
        $router->get('/history/{user_id}', 'v1\InvoiceController@index');
        $router->get('/{id}', 'v1\InvoiceController@show');
    });

    /**
     * Product Review routes
     */
    $router->group([CONST_PREFIX => 'review'], function () use ($router) {
        $router->post('/', 'v1\ProductReviewController@store');
        $router->post('/update/{id}', 'v1\ProductReviewController@update');
        $router->delete('/{id}', 'v1\ProductReviewController@delete');
    });
});
