<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;

route::get('/',[DashbordController::class,'getPage']);
route::get('/dashboard',[DashbordController::class,'getPage']);
route::get('/events',[EventController::class,'index']);
route::get('/events/create',[EventController::class,'create']);
route::post('/events/create',[EventController::class,'store']);
route::get('/events/delete/{id}',[EventController::class,'delete']);
route::get('/events/update/{id}',[EventController::class,'edit']);
route::post('/events/update',[EventController::class,'update']);

// Notification API Routes
Route::prefix('api/notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::post('/', [NotificationController::class, 'store']);
    Route::patch('/{id}/toggle-read', [NotificationController::class, 'toggleRead']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/mark-all-unread', [NotificationController::class, 'markAllAsUnread']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/', [NotificationController::class, 'deleteAll']);
});



