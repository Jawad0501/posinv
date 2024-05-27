<?php

namespace App\Http\Controllers\Api\Backend\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\GiftCardRequest;
use App\Http\Resources\Backend\Client\GiftCardCollection;
use App\Http\Resources\Backend\Client\GiftCardResource;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Client\GiftCardCollection
     *
     * @apiResourceModel App\Models\GiftCard
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index(Request $request)
    {
        $giftCards = GiftCard::query()->when($request->has('keyword'), fn ($q) => $q->whereLike(['amount', 'trx', 'btc_wallet', 'user,first_name', 'user,last_name']))->latest('id')->paginate();

        return new GiftCardCollection($giftCards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GiftCardRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Client\GiftCardResource
     *
     * @apiResourceModel App\Models\GiftCard
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
    public function show(GiftCard $giftCard)
    {
        return new GiftCardResource($giftCard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiftCard $giftCard)
    {
        $giftCard->delete();

        return response()->json(['message' => 'Gift card deleted successfully']);
    }
}
