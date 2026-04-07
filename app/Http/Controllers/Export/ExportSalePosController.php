<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Response;

class ExportSalePosController extends Controller
{
    public function __invoke(int|string $id): Response
    {
        $sale = Sale::query()->where('id', $id)->firstOrFail();

        $data = [
            'sale' => $sale,
        ];

        return response()->view('admin.sale.print-pos', $data);
    }
}
