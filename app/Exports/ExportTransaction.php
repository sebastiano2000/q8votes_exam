<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportTransaction implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $date;

    public function __construct($request)
    {
        $this->date = $request->date;
    }

    public function collection()
    {
        $financial_transaction =  DB::table('financial_transactions')
            ->join('users', 'users.id', '=', 'financial_transactions.tenant_id')
            ->join('payments', 'payments.financial_transaction_id', 'financial_transactions.id')
            ->join('apartments','apartments.id', 'payments.apartment_id')
            ->select([
                'financial_transactions.id as id',
                'users.name as name',
                'apartments.name as apartment_name',
                'financial_transactions.total_amount as amount',
                'financial_transactions.orderReferenceNumber as reference_number',
                'financial_transactions.paidOn as paid_on',
                'payments.pay_monthes',
                // 'financial_transactions.created_at',
                'financial_transactions.paid as status'
            ])
            ->whereNull('financial_transactions.deleted_at')
            ->whereNull('payments.deleted_at')
            ->whereNull('payments.deleted_at')
            ->whereNull('apartments.deleted_at')
            ->orderByDesc('financial_transactions.created_at');

        if ($this->date != null) {
            $date = explode('-', $this->date);
            $date[1] = date('F', mktime(0, 0, 0, $date[1], 10));
            $financial_transaction = $financial_transaction->where('financial_transactions.paidOn', 'like', '%' . $date[0] . '%')
                ->where('financial_transactions.paidOn', 'like', '%' . substr($date[1], 0, 3) . '%');
        }
        return $financial_transaction->get();;
    }
    public function headings(): array
    {
        return [
            'الرقم',
            'المستأجر',
            'الشقة',
            'القيمة',
            'رقم العملية',
            'تاريخ الدفع',
            'الشهور المدفوعة',
            // 'تاريخ الإنشاء',
            'الحالة'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet
                    ->getStyle('A1:I1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => true
                        ]
                    ]);
            },
        ];
    }
}
