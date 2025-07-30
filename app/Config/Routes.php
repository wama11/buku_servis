<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Enable OPTIONS requests for CORS preflight
$routes->options('(:any)', function () {
    return service('response')
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->setStatusCode(200);
});

$routes->get('/', 'Home::index');
// Manual route
// $routes->group('api', ['filter' => 'jwt'], function ($routes) {
//     $routes->post('authenticate', 'Api\AuthController::authenticate');
//     $routes->get('user', 'Api\AuthController::getUser');
//     $routes->post('auth/verify-phone-number', 'Api\AuthController::verifyPhone');
//     $routes->post('request-otp', 'Api\AuthController::requestOtp');
//     // app/Config/Routes.php
//     $routes->post('verify-otp', 'Api\AuthController::verifyOtp');
//     $routes->post('set-pin', 'Api\AuthController::setPin');
//     $routes->post('auth/login-pin', 'Api\AuthController::loginPin');
//     $routes->get('user/vehicles', 'Api\UserController::vehicles');
//     $routes->post('user/vehicles/(:num)/update-odometer', 'Api\UserController::updateOdometer/$1');
//     $routes->get('user/vehicles/(:num)/service-recommendations', 'Api\UserController::serviceRecommendations/$1');
//     $routes->get('user/vehicles/(:num)/warranties', 'Api\UserController::warranty/$1');
//     $routes->get('user/vehicles/(:num)/transactions', 'Api\UserController::transactions/$1');
//     $routes->get('user/transactions/(:num)', 'Api\UserController::transactionDetail/$1');

// });



// ✅ Endpoint publik tanpa JWT
$routes->group('api', function ($routes) {
    $routes->post('authenticate', 'Api\AuthController::authenticate');
    $routes->post('auth/verify-phone-number', 'Api\AuthController::verifyPhone');
    $routes->post('request-otp', 'Api\AuthController::requestOtp');
    $routes->post('verify-otp', 'Api\AuthController::verifyOtp');
    $routes->post('auth/login-pin', 'Api\AuthController::loginPin');
    $routes->get('content', 'Api\ContentController::index');


});

// 🔒 Endpoint yang butuh JWT (terproteksi)
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    $routes->post('auth/set-pin', 'Api\AuthController::setPin');
    $routes->get('user', 'Api\AuthController::getUser');
    $routes->get('user/vehicles', 'Api\UserController::vehicles');
    $routes->get('user/vehicles/(:num)', 'Api\UserController::vehicles_detail/$1');
    $routes->post('user/vehicles/(:num)/update-odometer', 'Api\UserController::updateOdometer/$1');
    $routes->get('user/vehicles/(:num)/service-recommendations', 'Api\UserController::serviceRecommendations/$1');
    $routes->get('user/vehicles/(:num)/warranties', 'Api\UserController::warranty/$1');
    $routes->get('user/vehicles/(:num)/transactions', 'Api\UserController::transactions/$1');
    $routes->get('user/transactions/(:num)/(:num)', 'Api\UserController::transactionDetail/$1/$2');
    $routes->post('user/transactions/(:num)/(:num)/review', 'Api\UserController::ratingSubmit/$1/$2');
    $routes->get('promos/active', 'Api\UserController::promos');
    $routes->get('user/vehicles/(:num)/point-history', 'Api\UserController::pointHistory/$1');


});
$routes->get('scrape-promo', 'PromoScraper::index');
$routes->get('testdb', 'TestDatabase::index');



// $routes->group('', ['namespace' => 'App\Controllers\Api', 'filter' => 'jwt'], function ($routes) {

//     // Auth
//     $routes->post('auth/set-pin', 'AuthController::setPin');

//     // User
//     $routes->get('user', 'AuthController::getUser');
//     $routes->get('user/vehicles', 'UserController::vehicles');
//     $routes->post('user/vehicles/(:num)/update-odometer', 'UserController::updateOdometer/$1');
//     $routes->get('user/vehicles/(:num)/service-recommendations', 'UserController::serviceRecommendations/$1');
//     $routes->get('user/vehicles/(:num)/warranties', 'UserController::warranty/$1');
//     $routes->get('user/vehicles/(:num)/transactions', 'UserController::transactions/$1');
//     $routes->get('user/transactions/(:num)/(:num)', 'UserController::transactionDetail/$1/$2');
//     $routes->post('user/transactions/(:num)/(:num)/review', 'UserController::ratingSubmit/$1/$2');
//     $routes->get('user/vehicles/(:num)/point-history', 'UserController::pointHistory/$1');

//     // Promo
//     $routes->get('promos/active', 'UserController::promos');

// });




// $routes->group('api', function ($routes) {
//     $routes->post('authenticate', 'Api\AuthController::authenticate');
//     $routes->post('setPin', 'Api\AuthController::setPin');
//     $routes->post('verifyPin', 'Api\AuthController::verifyPin');
// });
