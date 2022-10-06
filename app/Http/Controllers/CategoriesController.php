<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;

class CategoriesController extends Controller
{

    public function index() {
        abort_if(Gate::denies('access_product_categories'), 403);

        return view('admin.categories.index');
    }

    public function destroy($id) {
        abort_if(Gate::denies('access_product_categories'), 403);

        $category = Category::findOrFail($id);

        if ($category->products()->isNotEmpty()) {
            return back()->withErrors('Can\'t delete beacuse there are products associated with this category.');
        }

        $category->delete();

        toast('Product Category Deleted!', 'warning');

        return redirect()->route('product-categories.index');
    }
}
