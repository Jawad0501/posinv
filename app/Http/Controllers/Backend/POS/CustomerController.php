<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Api\Backend\POS\CustomerController as ApiCustomerController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerRequest;
use App\Models\User;

class CustomerController extends Controller
{
    public $customer;

    /**
     * assigned api customer controller
     */
    public function __construct()
    {
        $this->customer = new ApiCustomerController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->customer->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pos.customer.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        return $request->saved();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(User $customer)
    {
        $customer->load(['orders' => fn ($q) => $q->latest('id')->first(['user_id', 'created_at'])])->loadCount('orders')->loadSum('payments', 'rewards');

        return view('pages.pos.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        $customer->load(['orders' => fn ($q) => $q->latest('id')->first(['user_id', 'created_at'])])->loadCount('orders')->loadSum('payments', 'rewards');

        return view('pages.pos.customer.form', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, User $customer)
    {
        return $request->saved($customer);
    }
}
