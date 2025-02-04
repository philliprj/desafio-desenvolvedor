<?php

namespace App\Http\Controllers;

use App\Models\UploadHistory;
use Illuminate\Http\Request;

class UploadHistoryController extends Controller
{
    public function status($id)
    {
        $history = UploadHistory::findOrFail($id);

        return response()->json([
            'id'     => $history->id,
            'status' => $history->status,
        ]);
    }
}
