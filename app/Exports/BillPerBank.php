<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\GenerateBill;

use DB;

class BillPerBank implements WithTitle, ShouldAutoSize, FromView
{
    private $bank_id;
    private $bank_value;
    private $i;
    private $bill_id;
    private $month_value;
    private $year_value;

    public function __construct(int $bank_id, int $bill_id, int $month_value, int $year_value, string $bank_value = 'bank name'){
        $this->bank_id = $bank_id;
        $this->bank_value = $bank_value;
        $this->i = 1;
        $this->bill_id = $bill_id;
        $this->month_value = $month_value;
        $this->year_value = $year_value;
    }

    public function view(): View
    {
        DB::enableQueryLog();
        if($this->bank_id == 1){
            $view_file = 'export_billing.sbi';
            $data_get = DB::table('optcl_bill_bank_wise')
                            ->join('optcl_bill_ben_details', 'optcl_bill_ben_details.bill_bank_id', '=', 'optcl_bill_bank_wise.id')
                            ->select('optcl_bill_ben_details.*')
                            ->where('optcl_bill_bank_wise.bill_gen_id', $this->bill_id)
                            ->where('optcl_bill_bank_wise.bank_id', $this->bank_id)
                            ->get();
                            //dd(1);
        }else if($this->bank_id == 10){
            $view_file = 'export_billing.union';
            $data_get = DB::table('optcl_bill_bank_wise')
                        ->join('optcl_bill_ben_details', 'optcl_bill_ben_details.bill_bank_id', '=', 'optcl_bill_bank_wise.id')
                        ->select('optcl_bill_ben_details.*')
                        ->where('optcl_bill_bank_wise.bill_gen_id', $this->bill_id)
                        ->where('optcl_bill_bank_wise.bank_id', $this->bank_id)
                        ->get();
                        //dd(2);
        }else{
            $view_file = 'export_billing.other';
            $data_get = DB::table('optcl_bill_bank_wise')
                            ->join('optcl_bill_ben_details', 'optcl_bill_ben_details.bill_bank_id', '=', 'optcl_bill_bank_wise.id')
                            ->select('optcl_bill_ben_details.*')
                            ->where('optcl_bill_bank_wise.bill_gen_id', $this->bill_id)
                            ->whereNotIn('optcl_bill_bank_wise.bank_id', [1,10])
                            ->get();
                            //dd(3);
        }
        //dd($data_get, DB::getQueryLog());
        //return $data_get;
        return view($view_file, ['bills' => $data_get, 'month_value' => $this->month_value, 'year_value' => $this->year_value]);
        //return GenerateBill::all();
    }

   /*  public function map($bill): array
    {
        if($this->bank_id == 1){
            $sheet_data = [
                $this->i++,
                $bill->ben_ppo_no,
                $bill->ben_name,
                $bill->ben_acc_no,
                $bill->pension_amount
            ];
        }else if($this->bank_id == 10){
            $sheet_data = [
                $this->i++,
                $bill->ben_ppo_no,
                $bill->ben_name,
                $bill->ben_acc_no,
                $bill->pension_amount
            ];
        }else{
            $sheet_data = [
                $this->i++,
                $bill->ben_ppo_no,
                $bill->ben_name,
                $bill->ifsc_code,
                $bill->ben_acc_no,
                $bill->pension_amount,
                $bill->bank_name
            ];
        }
        //  SBI
        return $sheet_data;
    }

    public function headings(): array
    {   
        if($this->bank_id == 1){
            // SBI
            $header_array = [
                'Sl No',
                'PPO No',
                'Name',
                'A/C No',
                'Amount',
            ];
        }else if($this->bank_id == 10){
            // Union
            $header_array = [
                'Sl No',
                'PPO No',
                'Name',
                'A/C No',
                'Amount',
            ];
        }else{
            // Other
            $header_array = [
                'Sl No',
                'PPO No',
                'Name of Pensioner',
                'IFSC Code',
                'Account No',
                'Net Pension',
                'Bank Name',
            ];
        }
        return $header_array;
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Patrick');
            },
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:G1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ];
    } */

    public function title(): string
    {
        if($this->bank_id == 1){
            $sheet_name = "SBI";
        }else if($this->bank_id == 10){
            $sheet_name = "Union";
        }else{
            $sheet_name = "NEFT";
        }
        return $sheet_name;
    }

}
