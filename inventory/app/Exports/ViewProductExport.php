<?php

namespace App\Exports;

use App\Models\ViewProduct;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ViewProductExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithTitle
{
    use Exportable;

    public function __construct(int $store_id)
    {
        $this->store_id = $store_id;
    }

    public function query()
    {
        return ViewProduct::query()->where('store_id', $this->store_id);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Stock',
            'Total Sold',
            'Total Review',
            'Created At',
            'Updated At',
        ];
    }

    /**
    * @var ViewProduct $viewProduct
    */
    public function map($viewProduct): array
    {
        return [
            $viewProduct->name,
            $viewProduct->description,
            $viewProduct->harga,
            $viewProduct->stock,
            $viewProduct->total_sold,
            $viewProduct->total_review,
            Date::dateTimeToExcel($viewProduct->created_at),
            Date::dateTimeToExcel($viewProduct->updated_at),
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $now = date("y-m-d H.i");

        return "Inventory Report " . $now;
    }

}
