<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeItemsExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    protected $income_item_data;

    public function __construct($income_item_data)
    {
        $this->income_item_data = $income_item_data;
        //dd($income_data);
    }

    public function collection()
    {
        // Convert the array of expense items into a Laravel collection
        $collection = new Collection($this->income_item_data);

        // Transform the data if needed
        $transformedData = $collection->map(function ($item) {
            return [
                'Item Name' => $item['name'],
                'Total' => $item['total'],
            ];
        });

        return $transformedData;
    }

    public function headings() : array
    {
        return ['Item Name', 'Total'];
    }

}
