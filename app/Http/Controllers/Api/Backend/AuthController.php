<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Request;

/**
 * @group Staff Management
 *
 * APIs to manage authenticated staff information
 */
class AuthController extends Controller
{
    /**
     * Shows authenticated administration information
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\StaffResource
     *
     * @apiResourceModel App\Models\Staff
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function user(Request $request)
    {
        return new StaffResource($request->user());
    }

    /**
     * Administration information
     *
     * @authenticated
     *
     * @response 200
     * {
     *    "message": 'You are logged out successfully'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function staff()
    {
        $staff = Staff::latest('id')->get(['id', 'name']);

        return response()->json($staff);
    }
}
