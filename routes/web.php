<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/logout', [UserController::class, 'logout']);

Route::middleware(Auth::class)->group(function () {
    Route::get('/home', [HomeController::class, 'home']);

    Route::get('/locations', [HomeController::class, 'location']);
    Route::get('/locations/form', [HomeController::class, 'locationForm']);
    Route::post('/locations', [HomeController::class, 'locationStore']);
    Route::get('/locations/{delivery_locations}', [HomeController::class, 'locationShow']);
    Route::put('/locations/{delivery_locations}', [HomeController::class, 'locationUpdate']);
    Route::delete('/locations/{delivery_locations}', [HomeController::class, 'locationDelete'])->name('locations.delete');
    

    Route::get('/route', [RouteController::class, 'index'])->name('route.index');
    Route::post('/route/shortest', [RouteController::class, 'shortest'])->name('route.shortest');
    Route::get('/locations/search', [RouteController::class, 'searchLocations'])->name('locations.search');
    Route::post('/routes/shortest-multi', [RouteController::class, 'shortestMulti'])->name('routes.shortestMulti');
    Route::get('/routes/map', [RouteController::class, 'mapPage'])->name('routes.map');

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/form', [RoleController::class, 'roleForm']);
    Route::put('/roles/{role}', [RoleController::class, 'roleUpdate']);
    Route::delete('/roles/{role}', [RoleController::class, 'roleDelete']);
    Route::get('/roles/permissions', [RoleController::class, 'rolePermissions']);
    Route::post('/roles/permissions', [RoleController::class, 'assignPermission'])->name('assign.permission');

    Route::post('/assign-role', [RoleController::class, 'assignRole']);

    Route::get('/users', [UserController::class, 'viewUsers']);
    Route::get('/users/form', [UserController::class, 'userForm']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'userShow']);
    Route::put('/users/{user}', [UserController::class, 'userUpdate']);
    Route::delete('/users/{user}', [UserController::class, 'userDelete']);
});