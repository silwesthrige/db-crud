<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\EventController;

route::get('/',[DashbordController::class,'getPage']);
route::get('/events',[EventController::class,'index']);
route::get('/events/create',[EventController::class,'create']);
route::post('/events/create',[EventController::class,'store']);
route::get('/events/delete/{id}',[EventController::class,'delete']);
route::get('/events/update/{id}',[EventController::class,'edit']);
route::post('/events/update',[EventController::class,'update']);



