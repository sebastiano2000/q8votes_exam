<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportRevenu implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    
    protected $date;
    protected $building_id;
    protected $building_name;

    public function __construct($request)
    {
        $this->date = explode('?', $request->date)[0];
        $this->building_id = $request->building_id;
        $this->building_name = $request->building_name;
    }

    public function collection()
    {
        $date = explode('-', $this->date);
        $date[1] = date('F', mktime(0, 0, 0, $date[1], 10));

        $apartments = DB::table('apartments')
            ->Join('tenants', 'tenants.apartment_id', '=', 'apartments.id')
            ->whereRaw('tenants.id IN (select max(id) from tenants group by apartment_id)')
            ->Join('payments', 'payments.apartment_id', '=', 'apartments.id')
            ->Join('users', 'users.id', '=', 'payments.tenant_id')
            ->select([
                'apartments.name as apartment_name',
                'users.name as users_name',
                'tenants.start_date as start_date',
                'tenants.end_date as end_date',
                'tenants.price as price',
                'payments.pay_time as pay_time',
                'payments.pay_monthes as pay_monthes'
            ])
            ->whereNull('apartments.deleted_at')
            ->whereNull('tenants.deleted_at')
            ->whereNull('payments.deleted_at')
            ->where('apartments.status', 1)
            ->where('apartments.building_id', $this->building_id)
            ->where('payments.pay_monthes', 'like', '%'.$date[0].'%')
            ->where('payments.pay_monthes', 'like', '%'.substr($date[1], 0, 3).'%')
            ->distinct()
            ->get();
        
        return $apartments;
    }
    public function headings(): array
    {
        return [
            'أسم الوحدة',
            'أسم المستأجر',
            'بداية العقد',
            'نهاية العقد',
            'القيمة الإيجارية',
            (( __('pages.paid_date'))),
            'الشهور المدفوعة',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->setTitle($this->building_name);
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