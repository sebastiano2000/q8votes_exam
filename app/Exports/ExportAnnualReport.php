<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportAnnualReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $apartments;
    private $monthSums = [];

    private $building_id;
    private $year;
    private $allMonths = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    public function __construct($request)
    {
        $this->building_id = $request->building_id;
        $this->year = date('Y',strtotime(explode('?', $request->date)[0]));
        $this->apartments = $this->collection();
    }

    public function collection()
    {
        // make annual excel sheets with column for each month
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
                DB::raw('GROUP_CONCAT(DISTINCT payments.pay_monthes SEPARATOR \',\') as paid_months'),
            ])
            ->whereNull('apartments.deleted_at')
            ->whereNull('tenants.deleted_at')
            ->whereNull('payments.deleted_at')
            ->where('apartments.status', 1)
            ->where('apartments.building_id', $this->building_id)
            ->groupBy('tenants.id', 'apartments.name', 'users.name', 'tenants.start_date', 'tenants.end_date', 'tenants.price')
            ->distinct()
            ->get();

        foreach ($this->allMonths as $month) {
            $this->monthSums[$month] = 0;
        }

        // Iterate through apartments
        foreach ($apartments as $apartment) {
            // Split the pay_monthes string into an array
            $pay_monthes = explode(',', $apartment->paid_months);

            // Create properties for all 12 months and set the price
            foreach ($this->allMonths as $month) {
                $yearMonth = $month . '-' . $this->year;
                $apartment->$yearMonth = in_array($yearMonth, $pay_monthes) ? $apartment->price : 0;

                $this->monthSums[$month] += $apartment->$yearMonth;
            }
        }

        // sum the apartment revenu
        $totalSum = 0;
        foreach ($apartments as $apartment) {
            $apartment->sum = 0;

            foreach ($this->allMonths as $month) {
                $yearMonth = $month . '-' . $this->year;
                $apartment->sum += $apartment->$yearMonth;
            }
            $totalSum += $apartment->sum;
        }

        $sumRow = [
            'المجموع',
            '', // An empty cell
            '', // An empty cell
            '', // An empty cell
            '', // An empty cell
            '', // An empty cell
        ];
        foreach ($this->allMonths as $month) {
            $sumRow[] = $this->monthSums[$month];
        }
        array_push($sumRow, $totalSum);
        $apartments->push($sumRow);

        return $apartments;
    }

    public function headings(): array
    {
        // set title
        return [
            'أسم الوحدة',
            'أسم المستأجر',
            'بداية العقد',
            'نهاية العقد',
            'القيمة الإيجارية',
            'الشهور المدفوعة',
            ...$this->allMonths,
            'المجموع'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->setTitle("التقرير السنوي لعام " . $this->year);
                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
                // merge the last row and make it bold
                $event->sheet->mergeCells('A' . ($this->apartments->count() + 1) . ':F' . ($this->apartments->count() + 1));
                $event->sheet->getStyle('A' . ($this->apartments->count() + 1) . ':S' . ($this->apartments->count() + 1))->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    // merge
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                // make the last column bold
                $event->sheet->getStyle('S2:S' . ($this->apartments->count() + 1))->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },          
        ];
    }
}
