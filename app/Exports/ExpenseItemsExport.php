<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseItemsExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    protected $expense_item_data;

    public function __construct($expense_item_data)
    {
        $this->expense_item_data = $expense_item_data;
        //dd($expense_item_data);
    }

    public function collection()
    {
        // Convert the array of expense items into a Laravel collection
        $collection = new Collection($this->expense_item_data);

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
