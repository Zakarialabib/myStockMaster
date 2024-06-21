<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryController extends BaseController
{
    /**
     * Retrieve a list of categories with optional filters and pagination.
     *
     * @param  Request  $request
     *
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('_end') !== null) {
                $limit = $request->get('_end') ? $request->get('_end') : 10;
                $offset = $request->get('_start') ? $request->get('_start') : 0;

                $order = $request->get('_order') ? $request->get('_order') : 'asc';
                $sort = $request->get('_sort') ? $request->get('_sort') : 'id';
                //Filters
                $where_raw = ' 1=1 ';

                //capture brand_id filter
                // $brand_id = $request->get('brand_id') ? $request->get('brand_id')  : '';
                // if ($brand_id !== '') {
                //     $where_raw .= " AND (brand_id =  $brand_id)";
                // }
                //capture sort fields
                $sort_array = explode(',', $sort);

                if (count($sort_array) > 0) {
                    // retireve ordered and limit categories list
                    $categories = Category::whereRaw($where_raw)
                        // ->orderByRaw("COALESCE($sort)")
                        // ->offset($offset)
                        // ->limit($limit)
                        ->get();
                } else {
                    // retireve ordered and limit categories list
                    $categories = Category::orderBy($sort, $order)
                        // ->offset($offset)
                        // ->limit($limit)
                        ->get();
                }
            } else {
                // retireve all categories
                $categories = Category::get();
            }

            return $this->sendResponse($categories, 'Category List');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();
            $category = Category::create($input);
            DB::commit();

            return $this->sendResponse($category, 'Category updated successfully');
        } catch (Exception $e) {
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
            $category = Category::find($id);

            if (is_null($category)) {
                return $this->sendError('Category not found');
            } else {
                return $this->sendResponse($category, 'Category retrieved successfully');
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());

        return new CategoryResource($category);
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
            $category = Category::findorFail($id);
            $category->delete();

            return $this->sendResponse($category, 'Category deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
