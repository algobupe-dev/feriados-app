<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HolidayController;

Route::apiResource('holidays', HolidayController::class);