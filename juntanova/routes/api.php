<?php

use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:100,15')->group(function () {
    Route::post('/webhook/mercadopago', [WebhookController::class, 'mercadopago']);
});
