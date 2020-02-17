<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelImport implements ToArray
{

    /**
     * @inheritDoc
     */
    public function array(array $array)
    {
        return $array;
    }
}
