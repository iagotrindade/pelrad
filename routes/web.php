<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('gerar/pronto', [ReportController::class, 'index']);
    Route::get('gerar/relatorio/disponibilidade', [ReportController::class, 'availabilityReport']);
    Route::get('gerar/relatorio/cautelas', [ReportController::class, 'loansReport']);
});


