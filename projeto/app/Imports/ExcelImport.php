<?php

namespace App\Imports;

use App\Models\Upload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use \PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ExcelImport implements ToModel, WithChunkReading, WithStartRow, ShouldQueue, WithBatchInserts
{
    protected $uploadHistoryId;
    protected $skipRows;

    public function __construct($uploadHistoryId, $skipRows = 2)
    {
        $this->uploadHistoryId = $uploadHistoryId;
        $this->skipRows        = $skipRows;
    }

    public function startRow(): int
    {
        return $this->skipRows + 1;
    }

    public function batchSize(): int
    {
        return 20000;
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function model(array $row)
    {
        $rptDt = $row[0] ?? null;
        if (is_numeric($rptDt)) {
            $rptDt = ExcelDate::excelToDateTimeObject($rptDt)->format('Y-m-d');
        }

        return new Upload([
            'upload_history_id' => $this->uploadHistoryId,
            'RptDt'      => $rptDt,
            'TckrSymb'   => $row[1]  ?? null,
            'MktNm'      => $row[5]  ?? null,
            'SctyCtgyNm' => $row[6]  ?? null,
            'ISIN'       => $row[15] ?? null,
            'CrpnNm'     => $row[45] ?? null,
        ]);
    }
}
