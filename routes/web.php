<?php

use Illuminate\Support\Facades\Route;
use \Osen\Updater\UpdaterManager;

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
    return [
        'app'     => settings('general', 'name'),
        'email'   => settings('general', 'email'),
        'version' => settings('general', 'version'),
        'system'  => $router->app->version(),
    ];
});

$router->get('settings', function ($section = null, $x = null) {
    return settings(null);
});

$router->get("validate", ['as' => 'validate', 'uses' => "PaymentController@validation"]);
$router->post("confirm", ['as' => 'confirm', 'uses' => "PaymentController@store"]);
$router->post("results", ['as' => 'results', 'uses' => "PaymentController@results"]);
$router->post("register", ['as' => 'register', 'uses' => "PaymentController@register"]);
$router->post("timeout", ['as' => 'timeout', 'uses' => "PaymentController@timeout"]);
$router->post("reconcile", ['as' => 'reconcile', 'uses' => "PaymentController@reconcile"]);
$router->post("reverse", ['as' => 'reverse', 'uses' => "PaymentController@reverse"]);
$router->post("status", ['as' => 'status', 'uses' => "PaymentController@status"]);

$router->get('upgrade', function (UpdaterManager $updater) {
    if ($updater->source()->isNewVersionAvailable()) {
        echo $updater->source()->getVersionInstalled();
        $upgradable = $updater->source()->getVersionAvailable();
        $release    = $updater->source()->fetch($upgradable);
        $updater->source()->update($release);
        return redirect('updates?upgrade=success');
    } else {
        return redirect('updates?upgrade=unavailable');
    }
});

$router->post('customers', ['as' => 'customers.store', 'uses' => 'CustomerController@store']);
$router->get('customers[/{id}]', ['as' => 'customers.view', 'uses' => 'CustomerController@show']);
$router->put('customers[/{id}]', ['as' => 'customers.update', 'uses' => 'CustomerController@update']);
$router->delete('customers[/{id}]', ['as' => 'customers.delete', 'uses' => 'CustomerController@delete']);

$router->post('payments', ['as' => 'payments.store', 'uses' => 'PaymentController@store']);
$router->get('payments[/{id}]', ['as' => 'payments.view', 'uses' => 'PaymentController@show']);
$router->put('payments[/{id}]', ['as' => 'payments.update', 'uses' => 'PaymentController@update']);
$router->delete('payments[/{id}]', ['as' => 'payments.delete', 'uses' => 'PaymentController@delete']);

$router->get('search/payments', ['as' => 'payments.search', 'uses' => 'PaymentController@search_through']);
$router->post('request/payment', ['as' => 'payments.request', 'uses' => 'PaymentController@request_payment']);
$router->post('kopokopo', ['as' => 'kopkopo', 'uses' => 'PaymentController@kopokopo']);
