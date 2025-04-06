<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;

Route::post('/payment', [PaymentController::class, 'process']); 
