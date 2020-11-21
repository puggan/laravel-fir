<?php

/*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | contains the "web" middleware group. Now create something great!
 |
 */

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

/** @var \Illuminate\Routing\Router $router */
$router = Route::getFacadeRoot();

$router->get(
    '/',
    function () {
        return redirect('/game/1');
    }
);

GameController::routes($router);
