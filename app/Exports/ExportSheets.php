<?php

namespace App\Exports;

use App\Exports\ExportMaintenance;
use App\Exports\ExportRevenu;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ExportSheets implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;
    protected $building_id;

    public function __construct($request)
    {
        $this->request = $request;
        $this->building_id = $request->building_id;
    }


    public function sheets(): array
    {
        return [
            'Revenu' => new ExportRevenu($this->request),
            'Maintenance' => new ExportMaintenance($this->request),
            'Annual' => new ExportAnnualReport($this->request)
        ];
    }
}
