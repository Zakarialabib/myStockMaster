<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromQuery, WithMapping, WithHeadings, WithDrawings
{
    use Exportable;
    use ForModelsTrait;

    /** @var mixed */
    protected $models;

    /** @return string */
    public function title(): string
    {
        return __('Categories');
    }

    public function query()
    {
        if ($this->models) {
            return Category::query()->whereIn('id', $this->models);
        }

        return Category::query();
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->name,
            $row->code,
            $row->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Code'),
            __('Created At'),
        ];
    }

    /** @return array */
    public function drawings(): array
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setCoordinates('A4');
        $drawing->setHeight(90);
        $drawing->setCoordinates('B3');

        return [$drawing];
    }
}
