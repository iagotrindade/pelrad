<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware(['auth'])->group(function () {
    Route::get('gerar/pronto', [ReportController::class, 'generateComplianceReport'])->name('report.compliance');

    Route::get('gerar/relatorio/usuário', [ReportController::class, 'generateUserReport'])->name('report.users');

    Route::get('gerar/relatorio/categorias', [ReportController::class, 'generateCategoriesReport'])->name('report.categories');

    Route::get('gerar/relatorio/material', [ReportController::class, 'generateMaterialReport'])->name('report.material');

    Route::get('gerar/relatorio/cautelas', [ReportController::class, 'generateLoansReport'])->name('report.loans');

    Route::get('gerar/relatorio/configuracoes', [ReportController::class, 'generateConfigReport'])->name('report.configuration');

    Route::get('gerar/relatorio/auditoria', [ReportController::class, 'generateAuditReport'])->name('report.audit');
});

Route::get('teste', function () {
    return view('reports.generate-compliance-pdf');
});


