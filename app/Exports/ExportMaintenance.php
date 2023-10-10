<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMaintenance implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $building_id;
    protected $building_name;
    protected $date;


    public function __construct($request)
    {
        $this->date = explode('?', $request->date)[0];
        $this->building_id = $request->building_id;
        $this->building_name = explode($request->building_name, '?');
    }

    public function collection()
    {
        $month = date('Y', strtotime($this->date));
        $year = date('m', strtotime($this->date));

        $maintenances = DB::table('maintenances')
            ->Join('buildings', 'buildings.id', '=', 'maintenances.building_id')
            ->select([
                'buildings.name as building_name',
                'maintenances.name as name',
                'maintenances.cost as price',
                'maintenances.note as note',
                'maintenances.invoice_date as invoice_date',
                DB::raw('DATE(maintenances.created_at) as created_at')
            ])
            ->where('maintenances.building_id', $this->building_id)
            ->whereNull('maintenances.deleted_at')
            ->whereMonth('maintenances.created_at', $year)
            ->whereYear('maintenances.created_at', $month)
            ->get();

        return $maintenances;
    }

    public function headings(): array
    {
        return [
            ((__('pages.building_name'))),
            'الأسم',
            'القيمة',
            ((__('pages.note'))),
            ((__('pages.maintenance_invoice_date'))),
            ((__('pages.input_date'))),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->setTitle(((__('pages.fees'))));
                $event->sheet
                    ->getStyle('A1:G1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => true
                        ]
                    ]);
            },
        ];
    }
}
