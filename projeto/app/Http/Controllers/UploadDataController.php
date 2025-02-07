<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;

class UploadDataController extends Controller
{
    public function index(Request $request)
    {
        $query = Upload::query();

        if ($request->filled('history_id')) {
            $query->where('upload_history_id', $request->history_id);
        }

        if ($request->filled('TckrSymb')) {
            $query->where('TckrSymb', $request->TckrSymb);
        }

        if ($request->filled('RptDt')) {
            $query->whereDate('RptDt', $request->RptDt);
        }

        $perPage = $request->get('per_page', 10);
        $results = $query->paginate($perPage);

        $results->getCollection()->transform(function ($item) {
            return [
                'history_id' => $item->upload_history_id,
                'RptDt'      => $item->RptDt,
                'TckrSymb'   => $item->TckrSymb,
                'MktNm'      => $item->MktNm,
                'SctyCtgyNm' => $item->SctyCtgyNm,
                'ISIN'       => $item->ISIN,
                'CrpnNm'     => $item->CrpnNm,
            ];
        });

        return response()->json($results);
    }
}
