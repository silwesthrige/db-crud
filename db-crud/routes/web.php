<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;

route::get('/',[DashbordController::class,'getPage'])->name('dashboard');
route::get('/dashboard',[DashbordController::class,'getPage'])->name('dashboard');
route::get('/events',[EventController::class,'index'])->name('events.index');
route::get('/events/create',[EventController::class,'create'])->name('events.create');
route::post('/events/create',[EventController::class,'store'])->name('events.store');
route::delete('/events/delete/{id}',[EventController::class,'delete'])->name('events.delete');
route::get('/events/update/{id}',[EventController::class,'edit'])->name('events.edit');
route::post('/events/update',[EventController::class,'update'])->name('events.update');

// Calendar Routes
route::get('/events/calendar',[EventController::class,'calendar'])->name('events.calendar');
route::get('/events/calendar-data',[EventController::class,'calendarData'])->name('events.calendar-data');

// API Routes for Charts
route::get('/api/events/timeline',[EventController::class,'timelineData'])->name('api.events.timeline');
route::get('/api/events/monthly-trend',[EventController::class,'monthlyTrendData'])->name('api.events.monthly-trend');
route::get('/api/events/activity',[EventController::class,'activityData'])->name('api.events.activity');

// Export/Import Routes
route::get('/events/export',[EventController::class,'export'])->name('events.export');
route::get('/events/import',[EventController::class,'showImport'])->name('events.import');
route::post('/events/import',[EventController::class,'import'])->name('events.import.store');

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



