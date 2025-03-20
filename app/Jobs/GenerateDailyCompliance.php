<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Compliance;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\ReportController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateDailyCompliance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dateFormatted = strtoupper(Carbon::now()->translatedFormat('d M Y')) . '.pdf';
        $compliance = Compliance::where('name', 'Pronto - ' . $dateFormatted)->first();

        if (!$compliance) {
            $reportController = new ReportController();
            $reportController->generateComplianceReport();
        }
    }
}
