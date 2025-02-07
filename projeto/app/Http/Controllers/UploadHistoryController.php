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

    public function index(Request $request)
    {
        $query = UploadHistory::query();

        if ($request->filled('file_name')) {
            $query->where('file_name', 'like', '%'.$request->file_name.'%');
        }

        if ($request->filled('reference_date')) {
            $query->whereDate('reference_date', $request->reference_date);
        }

        $perPage = $request->get('per_page', 10);
        $histories = $query->paginate($perPage);

        return response()->json($histories);
    }
}
