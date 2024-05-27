<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Api\Backend\POS\CategoryController as ApiCategoryController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $category = new ApiCategoryController;
        
        $categories = $category->index();

        return view('pages.pos.partials.category', compact('categories'));
    }
}
