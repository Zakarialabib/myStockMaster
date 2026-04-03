<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Routing\Attributes\Get;
use Illuminate\Routing\Attributes\Middleware;
use PDF;
use Symfony\Component\HttpFoundation\Response;

class ExportSalePosController extends Controller
{
    #[Get('/admin/sales/pos/pdf/{id}', name: 'sales.pos.pdf')]
    #[Middleware(['auth', 'auth.session', 'role:admin'])]
    public function __invoke(int|string $id): Response
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $data = [
            'sale' => $sale,
        ];

        $pdf = PDF::loadView('admin.sale.print-pos', $data, [], [
            'format' => 'a5',
        ]);

        return $pdf->stream(__('Sale') . $sale->reference . '.pdf');
    }
}
