<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHistory;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:text/csv,text/plain,application/vnd.ms-excel,application/octet-stream,application/csv,application/excel,application/vnd.msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $file = $request->file('file');
        $fileHash = sha1_file($file->getRealPath());

        $existing = UploadHistory::where('file_hash', $fileHash)->first();
        if ($existing) {
            return response()->json(['message' => 'O arquivo já foi enviado anteriormente.'], 400);
        }

        $filePath = $file->store('uploads');

        $uploadHistory = UploadHistory::create([
            'file_name' => $file->getClientOriginalName(),
            'file_hash' => $fileHash,
        ]);

        $extension = $file->getClientOriginalExtension();
        \App\Jobs\ProcessUploadJob::dispatch($uploadHistory->id, $filePath, $extension);

        return response()->json([
            'message'    => 'Upload recebido. O processamento ocorrerá em breve.',
            'history_id' => $uploadHistory->id,
        ], 200);
    }

}
