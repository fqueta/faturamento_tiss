<?php

namespace App\Exports;

use App\Models\Familia;
use Maatwebsite\Excel\Concerns\FromCollection;

class FamiliasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Familia::all();
    }
}
