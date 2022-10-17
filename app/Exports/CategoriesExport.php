<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    { 
        $this->selected = $selected;
    }
    /**
    * @var Category $category
    */

    public function query()
    {
        if($this->selected){
            return Category::query()->whereIn('id', $this->selected);
        } else {
            return Category::query();
        }
    }

    public function map($category): array
    {
        return [
            $category->name,
            $category->code,
            $category->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Code',
            'Created At',
        ];
    }
}