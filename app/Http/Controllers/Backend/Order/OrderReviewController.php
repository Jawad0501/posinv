<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderReviewRequest;
use App\Models\Food;
use App\Models\OrderReview;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class OrderReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_order_review');
        if (request()->ajax()) {

            $reviews = OrderReview::query()->with('user:id,first_name,last_name', 'food:id,name');

            return DataTables::of($reviews)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('orders.review.show', $data->id), 'type' => 'show', 'can' => 'show_order_review'],
                        ['url' => route('orders.review.edit', $data->id), 'type' => 'edit', 'can' => 'edit_order_review'],
                        ['url' => route('orders.review.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_order_review'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.order.review.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_order_review');

        $users = User::get(['id', 'first_name', 'last_name']);
        $foods = Food::get(['id', 'name']);

        return view('pages.order.review.form', compact('users', 'foods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderReviewRequest $request)
    {
        $this->authorize('create_order_review');

        $input = $request->all();
        $input['user_id'] = $request->customer;
        $input['food_id'] = $request->menu_food;

        OrderReview::create($input);

        return response()->json(['message' => 'Order review added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize('show_order_review');
        $review = OrderReview::with('user:id,first_name,last_name', 'food:id,name')->findOrFail($id);

        return view('pages.order.review.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('edit_order_review');

        $review = OrderReview::findOrFail($id);
        $users = User::get(['id', 'first_name', 'last_name']);
        $foods = Food::get(['id', 'name']);

        return view('pages.order.review.form', compact('users', 'foods', 'review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderReviewRequest $request, $id)
    {
        $this->authorize('edit_order_review');

        $review = OrderReview::findOrFail($id);

        $input = $request->all();
        $input['user_id'] = $request->customer;
        $input['food_id'] = $request->menu_food;

        $review->update($input);

        return response()->json(['message' => 'Order review updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete_order_review');
        $review = OrderReview::findOrFail($id)->delete();
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
