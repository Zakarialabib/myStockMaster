<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{

    /**
     * Retrieve a list of products with optional filters and pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('_end') !== null) {
                //
                $limit = $request->get('_end') ? $request->get('_end') : 10;
                $offset = $request->get('_start') ? $request->get('_start') : 0;
                //
                $order = $request->get('_order') ? $request->get('_order') : 'asc';
                $sort = $request->get('_sort') ?  $request->get('_sort') : 'id';
                //Filters
                $where_raw = ' 1=1 ';

                //capture brand_id filter
                $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
                if ($brand_id !== '') {
                    $where_raw .= " AND (brand_id =  $brand_id)";
                }
                //capture sort fields 
                $sort_array = explode(',', $sort);
                if (count($sort_array) > 0) {
                    // retireve ordered and limit products list
                    $products = Product::with(['category', 'brand'])
                        ->whereRaw($where_raw)
                        // ->orderByRaw("COALESCE($sort)")
                        // ->offset($offset)
                        // ->limit($limit)
                        ->get();
                } else {
                    // retireve ordered and limit products list
                    $products = Product::with(['category', 'brand'])
                        ->orderBy($sort, $order)
                        // ->offset($offset)
                        // ->limit($limit)
                        ->get();
                }
            } else {
                // retireve all products
                $products = Product::with(['category', 'brand'])->get();
            }
            return $this->sendResponse($products, 'Product List');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $product = Product::create($input);
            DB::commit();
            return $this->sendResponse($product, 'Product updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                return $this->sendError('Product not found');
            } else {
                return $this->sendResponse($product, 'Product retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id)
    {
        try {
            $product = Product::findorFail($id);
            $product->delete();
            return $this->sendResponse($product, 'Product deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }
}
