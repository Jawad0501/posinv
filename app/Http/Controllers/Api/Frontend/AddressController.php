<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Authenticated user address management
 *
 * APIs to Authenticated user address management
 */
class AddressController extends Controller
{
    /**
     * Display specific user address resource.
     *
     * @authenticated
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
        return response()->json($request->user()->address_book);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Address added successfully."
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
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'location' => 'required|string',
        ]);

        $addresss = [];
        $address = $request->user()->address_book;
        if ($address !== null) {
            foreach ($address as $addr) {
                $addresss[] = [
                    'type' => $addr->type,
                    'location' => $addr->location,
                ];
            }
        }
        $addresss[] = [
            'type' => $request->type,
            'location' => $request->location,
        ];

        $request->user()->fill(['address_book' => $addresss])->save();

        return response()->json([
            'message' => 'Address added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
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
    public function show(Request $request, $key)
    {
        return $request->user()->address_book[$key];
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Address successfully updated.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)"
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(Request $request, $key)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'location' => 'required|string',
        ]);

        $addresss = [];
        $address = $request->user()->address_book;
        foreach ($address as $index => $addr) {
            if ($key == $index) {
                $addresss[] = [
                    'type' => $request->type,
                    'location' => $request->location,
                ];
            } else {
                $addresss[] = [
                    'type' => $addr->type,
                    'location' => $addr->location,
                ];
            }
        }
        $request->user()->fill(['address_book' => $addresss])->save();

        return response()->json([
            'message' => 'Address updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Customer deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $addresss = [];
        $address = $request->user()->address_book;
        if ($address !== null) {
            foreach ($address as $index => $addr) {
                if ($id != $index) {
                    $addresss[] = [
                        'type' => $addr->type,
                        'location' => $addr->location,
                    ];
                }
            }
        }
        $request->user()->fill(['address_book' => $addresss])->save();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
