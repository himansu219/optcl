<?php

namespace App\Exports;

/* use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\GenerateBill; */
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class BillExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /* public function collection()
    {
        return DB::table('optcl_bill_ben_details')->get();
        //return GenerateBill::all();
    }

    public function map($bill): array
    {
        return [
            $bill->bank_name,
            $bill->branch_name,
            $bill->ifsc_code,
            $bill->branch_address,
            $bill->ben_name
        ];
    }

    public function headings(): array
    {
        return [
            'Bank Name',
            'Branch Name',
            'IFSC Code',
            'Branch Address',
            'Name'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:E1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ];
    } */

    public function sheets(): array
    {
        //dd(123);
        $sheets = [];
        /* 
            * 1 - State Bank of India
            * 10 - Union Bank
            * 999 - Other Banks
        */
        $banks = [1, 10, 999];
        foreach($banks as $bank_data){
            $sheets[] = new BillPerBank($bank_data);
        }
        return $sheets;
    }

}
