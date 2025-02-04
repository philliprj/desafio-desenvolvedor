<?php

namespace App\Jobs;

use App\Models\UploadHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uploadHistoryId;

    public function __construct($uploadHistoryId)
    {
        $this->uploadHistoryId = $uploadHistoryId;
    }

    public function handle()
    {
        $history = UploadHistory::find($this->uploadHistoryId);
        if ($history) {
            $history->status = 'FINISHED';
            $history->save();
        }
    }
}
