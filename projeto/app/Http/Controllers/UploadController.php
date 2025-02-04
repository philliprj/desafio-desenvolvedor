<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadHistory;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

use App\Imports\CsvImport;
use App\Imports\ExcelImport;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimetypes:text/csv,text/plain,application/vnd.ms-excel,application/octet-stream,application/csv,application/excel,application/vnd.msexcel',
        ]);


        $file = $request->file('file');
        $fileHash = sha1_file($file->getRealPath());
        $existing = UploadHistory::where('file_hash', $fileHash)->first();
        if ($existing) {
            return response()->json(['message' => 'O arquivo já foi enviado anteriormente.'], 400);
        }

        $uploadHistory = UploadHistory::create([
            'file_name' => $file->getClientOriginalName(),
            'file_hash' => $fileHash,
        ]);

        $extension = $file->getClientOriginalExtension();

        switch ($extension) {
            case 'csv':
                Excel::import(
                    new CsvImport($uploadHistory->id, 2),
                    $file,
                    null,
                    ExcelType::CSV
                );
                break;

            case 'xls':
            case 'xlsx':
                Excel::import(
                    new ExcelImport($uploadHistory->id, 2),
                    $file
                );
                break;

            default:
                return response()->json(['message' => 'Formato não suportado.'], 400);
        }

        return response()->json([
            'message'    => 'Upload realizado com sucesso!',
            'history_id' => $uploadHistory->id,
        ], 200);
    }
}
