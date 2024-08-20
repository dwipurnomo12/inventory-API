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
// Route for Auth Login (Public Route)
$router->post('/login', 'AuthController@login');

// Grouping routes that require JWT authentication
$router->group(['middleware' => 'auth:api'], function () use ($router) {
    // Route for Products
    $router->get('/products', 'ProductController@index');
    $router->post('/products', 'ProductController@store');
    $router->get('/products/{id}', 'ProductController@show');
    $router->put('/products/{id}', 'ProductController@update');
    $router->delete('/products/{id}', 'ProductController@destroy');

    // Route for Categories
    $router->get('/categories', 'CategoryController@index');
    $router->post('/categories', 'CategoryController@store');
    $router->put('/categories/{id}', 'CategoryController@update');
    $router->delete('/categories/{id}', 'CategoryController@destroy');

    // Route for Units
    $router->get('/units', 'UnitController@index');
    $router->post('/units', 'UnitController@store');
    $router->put('/units/{id}', 'UnitController@update');
    $router->delete('/units/{id}', 'UnitController@destroy');

    // Route for Users
    $router->get('/users', 'UserController@index');
    $router->post('/users', 'UserController@store');
    $router->put('/users/{id}', 'UserController@update');
    $router->delete('/users/{id}', 'UserController@destroy');

    // Route for Customers
    $router->get('/customers', 'CustomerController@index');
    $router->post('/customers', 'CustomerController@store');
    $router->put('/customers/{id}', 'CustomerController@update');
    $router->delete('/customers/{id}', 'CustomerController@destroy');

    // Route for Customers
    $router->get('/suppliers', 'SupplierController@index');
    $router->post('/suppliers', 'SupplierController@store');
    $router->put('/suppliers/{id}', 'SupplierController@update');
    $router->delete('/suppliers/{id}', 'SupplierController@destroy');

    // Route for Products Received
    $router->get('/products-received', 'ProductReceivedController@index');
    $router->post('/products-received', 'ProductReceivedController@addProduct');
    $router->put('/products-received/{id}', 'ProductReceivedController@updateProduct');

    // Route for Logout 
    $router->post('/logout', 'AuthController@logout');
});