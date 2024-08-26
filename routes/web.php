<?php

use Illuminate\Support\Facades\File;

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

$router->group(['prefix' => 'api'], function ($router) {
    // Route for Auth Login (Public Route)
    $router->post('/login', 'AuthController@login');

    // Grouping routes that require JWT authentication
    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->group(['middleware' => 'CheckRole:Superadmin'], function () use ($router) {
            // Route for Roles
            $router->get('/roles', 'RoleController@index');
        });

        $router->group(['middleware' => 'CheckRole:Superadmin,Kepala Gudang'], function () use ($router) {
            // Route for Users
            $router->get('/users', 'UserController@index');
            $router->post('/users', 'UserController@store');
            $router->put('/users/{id}', 'UserController@update');
            $router->delete('/users/{id}', 'UserController@destroy');
        });

        $router->group(['middleware' => 'CheckRole:Superadmin,Kepala Gudang,Admin Gudang'], function () use ($router) {
            // Route for Product Stock
            $router->get('/filter-data-product-stock', 'ProductStokReportController@filterDataProductStock');

            // Route for Product Received Report
            $router->get('/filter-data-product-received', 'ProductReceivedReportController@filterDataProductReceived');

            // Route for Product Received Report
            $router->get('/filter-data-product-out', 'ProductOutReportController@filterDataProductOut');

            // Route for Logout 
            $router->post('/logout', 'AuthController@logout');
        });

        $router->group(['middleware' => 'CheckRole:Superadmin,Admin Gudang'], function () use ($router) {
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

            // Route for Products Out
            $router->get('/products-out', 'ProductOutController@index');
            $router->post('/products-out', 'ProductOutController@addProduct');
            $router->put('/products-out/{id}', 'ProductOutController@updateProduct');
        });
    });
});