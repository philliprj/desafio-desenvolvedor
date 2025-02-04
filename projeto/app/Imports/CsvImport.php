<?php

namespace App\Imports;

use App\Models\Upload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CsvImport implements ToModel, WithChunkReading, ShouldQueue, WithCustomCsvSettings, WithStartRow, WithBatchInserts
{
    protected $historyId;
    protected $skipRows;

    public function __construct($historyId, $skipRows = 2)
    {
        $this->historyId = $historyId;
        $this->skipRows = $skipRows;
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
        return 20000;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter'        => ';',
            'enclosure'        => '"',
            'escape_character' => '\\',
            'use_bom'          => false,
            'input_encoding'   => 'UTF-8',
        ];
    }

    public function model(array $row)
    {
        Upload::create([
            'upload_history_id' => $this->historyId,
            'RptDt'             => $row[0] ?? null,
            'TckrSymb'          => $row[1] ?? null,
            'MktNm'             => $row[5] ?? null,
            'SctyCtgyNm'        => $row[6] ?? null,
            'ISIN'              => $row[15] ?? null,
            'CrpnNm'            => $row[45] ?? null,
        ]);

    }
}
