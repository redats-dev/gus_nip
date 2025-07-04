<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GusController;

Route::get('/gus/{nip}', [GusController::class, 'show']);

