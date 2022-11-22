<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\AdjustedProduct;
use App\Models\Adjustment;
use App\Models\Product;
use App\Notifications\NotifyQuantityAlert;

class AdjustmentController extends Controller
{
    
    public function index()
    {
        abort_if(Gate::denies('access_adjustments'), 403);

        return view('admin.adjustment.index');
    }


    public function create()
    {
        abort_if(Gate::denies('create_adjustments'), 403);

        return view('admin.adjustment.create');
    }

    // use livewire ---------> 
    public function store(Request $request)
    {
        abort_if(Gate::denies('create_adjustments'), 403);

        $request->validate([
            'reference'   => 'required|string|max:255',
            'date'        => 'required|date',
            'note'        => 'nullable|string|max:1000',
            'product_ids' => 'required',
            'quantities'  => 'required',
            'types'       => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'date' => $request->date,
                'note' => $request->note
            ]);

            foreach ($request->product_ids as $key => $id) {
                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id'    => $id,
                    'quantity'      => $request->quantities[$key],
                    'type'          => $request->types[$key]
                ]);

                $product = Product::findOrFail($id);

                if ($request->types[$key] == 'add') {
                    $product->update([
                        'quantity' => $product->quantity + $request->quantities[$key]
                    ]);
                } elseif ($request->types[$key] == 'sub') {
                    $product->update([
                        'quantity' => $product->quantity - $request->quantities[$key]
                    ]);
                }
            }
        });

        toast('Adjustment Created!', 'success');

        return redirect()->route('adjustments.index');
    }


    public function show(Adjustment $adjustment)
    {
        abort_if(Gate::denies('show_adjustments'), 403);

        return view('admin.adjustment.show', compact('adjustment'));
    }


    public function edit(Adjustment $adjustment)
    {
        abort_if(Gate::denies('edit_adjustments'), 403);

        return view('admin.adjustment.edit', compact('adjustment'));
    }


    public function update(Request $request, Adjustment $adjustment)
    {
        abort_if(Gate::denies('edit_adjustments'), 403);

        $request->validate([
            'reference'   => 'required|string|max:255',
            'date'        => 'required|date',
            'note'        => 'nullable|string|max:1000',
            'product_ids' => 'required',
            'quantities'  => 'required',
            'types'       => 'required'
        ]);

        DB::transaction(function () use ($request, $adjustment) {
            $adjustment->update([
                'reference' => $request->reference,
                'date'      => $request->date,
                'note'      => $request->note
            ]);

            foreach ($adjustment->adjustedProducts as $adjustedProduct) {
                $product = Product::findOrFail($adjustedProduct->product->id);

                if ($adjustedProduct->type == 'add') {
                    $product->update([
                        'quantity' => $product->quantity - $adjustedProduct->quantity
                    ]);
                } elseif ($adjustedProduct->type == 'sub') {
                    $product->update([
                        'quantity' => $product->quantity + $adjustedProduct->quantity
                    ]);
                }

                $adjustedProduct->delete();
            }

            foreach ($request->product_ids as $key => $id) {
                AdjustedProduct::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id'    => $id,
                    'quantity'      => $request->quantities[$key],
                    'type'          => $request->types[$key]
                ]);

                $product = Product::findOrFail($id);

                if ($request->types[$key] == 'add') {
                    $product->update([
                        'quantity' => $product->quantity + $request->quantities[$key]
                    ]);
                } elseif ($request->types[$key] == 'sub') {
                    $product->update([
                        'quantity' => $product->quantity - $request->quantities[$key]
                    ]);
                }
            }
        });

        toast('Adjustment Updated!', 'info');

        return redirect()->route('adjustments.index');
    }


    public function destroy(Adjustment $adjustment)
    {
        abort_if(Gate::denies('delete_adjustments'), 403);

        $adjustment->delete();

        toast('Adjustment Deleted!', 'warning');

        return redirect()->route('adjustments.index');
    }
}
