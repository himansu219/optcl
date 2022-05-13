<?php

namespace App\Imports;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Request;
use Auth;

class DataImport implements ToModel, WithBatchInserts, WithStartRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    //use Importable;

    public function model(array $row)
    {

        //print_r($row);
        $request = request()->all();
        //dd($request);
        //return 1234;
        return new Contacts([
            'district_id'           => $row[1],
            'block_id'              => $row[2],
            'society_name'          => $row[3],
            'department_id'         => $row[4],
            'state_nodal_type_id'   => $row[5],
        ]);
    }

   /* public function headingRow(): int
    {
        return 1;
    }*/

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            //'1' => 'required|max:15',
        ];
    }

}
