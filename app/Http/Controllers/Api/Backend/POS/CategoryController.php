<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\POS\CategoryCollection;
use App\Models\IngredientCategory as Category;

/**
 * @group POS Category management
 *
 * APIs to POS Category management
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\CategoryCollection
     *
     * @apiResourceModel App\Models\Category
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index()
    {

        $categories = Category::latest('id')->get();

        return new CategoryCollection($categories);
    }
}
