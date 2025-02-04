<?php

namespace App\Jobs;

use App\Models\UploadHistory;
use App\Imports\CsvImport;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1000;
    protected $uploadHistoryId;
    protected $filePath;
    protected $extension;
    protected $skipRows;

    public function __construct($uploadHistoryId, $filePath, $extension, $skipRows = 2)
    {
        $this->uploadHistoryId = $uploadHistoryId;
        $this->filePath = $filePath;
        $this->extension = $extension;
        $this->skipRows = $skipRows;
    }

    public function handle()
    {
        $history = UploadHistory::find($this->uploadHistoryId);
        if ($history) {
            $history->status = 'PROCESSING';
            $history->save();
        }

        switch ($this->extension) {
            case 'csv':
                Excel::queueImport(
                    new CsvImport($this->uploadHistoryId, $this->skipRows),
                    $this->filePath,
                    null,
                    ExcelType::CSV
                )->chain([
                    new \App\Jobs\FinishUploadJob($this->uploadHistoryId),
                ]);
                break;

            case 'xls':
            case 'xlsx':
                Excel::queueImport(
                    new ExcelImport($this->uploadHistoryId, $this->skipRows),
                    $this->filePath
                )->chain([
                    new \App\Jobs\FinishUploadJob($this->uploadHistoryId),
                ]);
                break;

            default:
                break;
        }
    }

    public function failed(\Exception $exception): void
    {
        $history = UploadHistory::find($this->uploadHistoryId);
        if ($history) {
            $history->status = 'FAILED';
            $history->save();
        }
    }

}
