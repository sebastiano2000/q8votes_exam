<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ExportTransactionSheets implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;


    public function __construct($request)
    {
        $this->request = $request;
    }


    public function sheets(): array
    {
        return [

        ];
    }
}
