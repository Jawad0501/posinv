<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerRequest;
use App\Http\Resources\Backend\POS\CustomerResource;
use App\Http\Resources\Backend\POS\RiderCollection;
use App\Models\User;

/**
 * @group POS Customer management
 *
 * APIs to POS Customer management
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\CustomerCollection
     *
     * @apiResourceModel App\Models\Order
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
        $customers = User::user()->latest()->get(['id', 'first_name', 'last_name', 'phone', 'customer_id']);

        return new RiderCollection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Customer successfully stored."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(CustomerRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\POS\CustomerResource
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(User $customer)
    {
        $customer->load(['orders' => fn ($q) => $q->latest('id')->first(['user_id', 'created_at'])])->loadCount('orders')->loadSum('payments', 'rewards');

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Customer successfully updated.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)",
     *    "errors": {
     *        "name": [
     *            "The name field is required."
     *        ],
     *        "email": [
     *            "The email field is required."
     *        ],
     *        "phone": [
     *            "The phone field is required."
     *        ]
     *    }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(CustomerRequest $request, User $customer)
    {
        return $request->saved($customer);
    }

    /**
     * Get all rider lists
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\RiderCollection
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function getRider()
    {
        $riders = User::rider()->latest('id')->get(['id', 'first_name', 'phone', 'last_name']);

        return new RiderCollection($riders);
    }
}
