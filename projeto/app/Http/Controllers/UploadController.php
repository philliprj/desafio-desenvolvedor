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

        $originalName = $file->getClientOriginalName();
        $pattern = '/^(.+)_(\d{8})_(.{1})\.(csv|xls|xlsx)$/i';

        if (!preg_match($pattern, $originalName, $matches)) {
            return response()->json([
                'message' => 'Nome do arquivo inválido. Formato esperado: titulo_YYYYMMDD_caracter.csv|xls|xlsx'
            ], 400);
        }

        $rawDate = $matches[2];

        try {
            $referenceDate = \Carbon\Carbon::createFromFormat('Ymd', $rawDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Data inválida no nome do arquivo'], 400);
        }

        $fileHash = sha1_file($file->getRealPath());
        $existing = UploadHistory::where('file_hash', $fileHash)->first();
        if ($existing) {
            return response()->json(['message' => 'O arquivo já foi enviado anteriormente.'], 400);
        }

        $filePath = $file->store('uploads');

        $uploadHistory = UploadHistory::create([
            'file_name'      => $originalName,
            'file_hash'      => $fileHash,
            'reference_date' => $referenceDate,
        ]);

        $extension = $file->getClientOriginalExtension();
        \App\Jobs\ProcessUploadJob::dispatch($uploadHistory->id, $filePath, $extension);

        return response()->json([
            'message'    => 'Upload recebido. O processamento ocorrerá em breve.',
            'history_id' => $uploadHistory->id,
        ], 200);
    }
}
