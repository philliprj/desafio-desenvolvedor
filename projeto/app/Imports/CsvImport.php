<?php

namespace App\Imports;

use App\Models\Upload;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CsvImport implements ToCollection, WithCustomCsvSettings
{
    protected $historyId;
    protected $skipRows;

    public function __construct($historyId, $skipRows = 2)
    {
        $this->historyId = $historyId;
        $this->skipRows = $skipRows;
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

    public function collection(Collection $rows)
    {
        if ($this->skipRows > 0) {
            $rows = $rows->slice($this->skipRows);
        }

        foreach ($rows as $row) {
            $rptDt = $row[0] ?? null;
            $tckrSymb = $row[1] ?? null;
            $mktNm = $row[5] ?? null;
            $sctyCtgy = $row[6] ?? null;
            $isin = $row[15] ?? null;
            $crpnNm = $row[45] ?? null;

            Upload::create([
                'upload_history_id' => $this->historyId,
                'RptDt'             => $rptDt,
                'TckrSymb'          => $tckrSymb,
                'MktNm'             => $mktNm,
                'SctyCtgyNm'        => $sctyCtgy,
                'ISIN'              => $isin,
                'CrpnNm'            => $crpnNm,
            ]);
        }
    }
}
