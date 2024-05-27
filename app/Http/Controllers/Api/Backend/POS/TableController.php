<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\POS\TablelayoutCollection;
use App\Models\Tablelayout;

/**
 * @group POS Table management
 *
 * APIs to POS Table management
 */
class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\TablelayoutCollection
     *
     * @apiResourceModel App\Models\Tablelayout
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
        $tables = Tablelayout::with(['orders' => fn ($q) => $q->whereRelation('order', 'status', 'processing')->orWhereRelation('order', 'status', 'served')], 'orders.order:id,invoice,available_time,created_at')->active()->latest('id')->get();

        return new TablelayoutCollection($tables);
    }
}
