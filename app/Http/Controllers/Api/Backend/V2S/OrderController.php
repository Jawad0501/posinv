<?php

namespace App\Http\Controllers\Api\Backend\V2S;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\V2S\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @group V2S Order management
 *
 * APIs to V2S Order management
 */
class OrderController extends Controller
{
    /**
     * Get Order list
     *
     * @authenticated
     *
     * @response 200
     * [
     *    {
     *        "status": "pending",
     *        "orders": 7
     *    },
     *    {
     *        "status": "processing",
     *        "orders": 2
     *    },
     *    {
     *        "status": "success",
     *        "orders": 1
     *    },
     *    {
     *        "status": "cancel",
     *        "orders": 0
     *    }
     * ]
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pending = Order::where('type', 'Online')->byStatus('pending')->count();
        $processing = Order::where('type', 'Online')->byStatus('processing')->count();
        $complete = Order::where('type', 'Online')->byStatus('success')->count();
        $cancel = Order::where('type', 'Online')->byStatus('cancel')->count();

        return response()->json([
            ['status' => 'pending', 'orders' => $pending],
            ['status' => 'processing', 'orders' => $processing],
            ['status' => 'success', 'orders' => $complete],
            ['status' => 'cancel', 'orders' => $cancel],
        ]);
    }

    /**
     * Order List by status
     *
     * @authenticated
     *
     * @response 200
     * {
     *    "data": [
     *        {
     *            "id": 27,
     *            "invoice": "KL00027",
     *            "user_name": "User ",
     *            "available_time": "00:01:30",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 46,
     *                        "status": "pending",
     *                        "quantity": 1,
     *                        "processing_time": "00:01:30",
     *                        "menu_name": "Rice",
     *                        "variant_name": "With Meat",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 10",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "name": "Addon 8",
     *                                    "quantity": 1
     *                                }
     *                            ]
     *                        }
     *                    }
     *                ]
     *            }
     *        },
     *        {
     *            "id": 26,
     *            "invoice": "KL00026",
     *            "user_name": "User ",
     *            "available_time": "00:01:30",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 45,
     *                        "status": "pending",
     *                        "quantity": 1,
     *                        "processing_time": "00:01:30",
     *                        "menu_name": "Rice",
     *                        "variant_name": "With Meat",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 10",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "name": "Addon 8",
     *                                    "quantity": 1
     *                                }
     *                            ]
     *                        }
     *                    }
     *                ]
     *            }
     *        },
     *        {
     *            "id": 25,
     *            "invoice": "KL00025",
     *            "user_name": "User ",
     *            "available_time": "00:01:30",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 44,
     *                        "status": "pending",
     *                        "quantity": 1,
     *                        "processing_time": "00:01:30",
     *                        "menu_name": "Rice",
     *                        "variant_name": "With Meat",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 10",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "name": "Addon 8",
     *                                    "quantity": 1
     *                                }
     *                            ]
     *                        }
     *                    }
     *                ]
     *            }
     *        },
     *        {
     *            "id": 24,
     *            "invoice": "KL00024",
     *            "user_name": "User ",
     *            "available_time": "00:01:30",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 43,
     *                        "status": "pending",
     *                        "quantity": 1,
     *                        "processing_time": "00:01:30",
     *                        "menu_name": "Rice",
     *                        "variant_name": "With Meat",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 10",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "name": "Addon 8",
     *                                    "quantity": 1
     *                                }
     *                            ]
     *                        }
     *                    }
     *                ]
     *            }
     *        },
     *        {
     *            "id": 23,
     *            "invoice": "KL00023",
     *            "user_name": "User ",
     *            "available_time": "00:01:30",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 42,
     *                        "status": "pending",
     *                        "quantity": 1,
     *                        "processing_time": "00:01:30",
     *                        "menu_name": "Rice",
     *                        "variant_name": "With Meat",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 10",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "name": "Addon 8",
     *                                    "quantity": 1
     *                                }
     *                            ]
     *                        }
     *                    }
     *                ]
     *            }
     *        },
     *        {
     *            "id": 22,
     *            "invoice": null,
     *            "user_name": "User ",
     *            "available_time": null,
     *            "order_details": {
     *                "data": []
     *            }
     *        },
     *        {
     *            "id": 21,
     *            "invoice": "KL00021",
     *            "user_name": "User ",
     *            "available_time": "00:13:33",
     *            "order_details": {
     *                "data": [
     *                    {
     *                        "id": 40,
     *                        "status": "pending",
     *                        "quantity": 2,
     *                        "processing_time": "00:12:08",
     *                        "menu_name": "Shrimp",
     *                        "variant_name": "Medium",
     *                        "addons": {
     *                            "data": [
     *                                {
     *                                    "name": "Addon 9",
     *                                    "quantity": 1
     *                                },
     *                                {
     *                                    "quantity": 1
     *                               }
     *                           ]
     *                       }
     *                   },
     *                   {
     *                       "id": 41,
     *                       "status": "pending",
     *                       "quantity": 1,
     *                       "processing_time": "00:01:25",
     *                       "menu_name": "Fish",
     *                       "variant_name": "With Meat",
     *                       "addons": {
     *                           "data": [
     *                               {
     *                                   "name": "Addon 8",
     *                                   "quantity": 1
     *                               },
     *                               {
     *                                   "name": "Addon 3",
     *                                   "quantity": 1
     *                               }
     *                           ]
     *                       }
     *                   }
     *               ]
     *           }
     *       }
     *   ]
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @urlParam status string required The status of the order.
     *
     * @return \App\Http\Resources\Backend\V2S\OrderCollection
     */
    public function orderByStatusWise($status)
    {
        $orders = Order::with('user:id,first_name,last_name', 'orderDetails:id,order_id,food_id,variant_id,quantity,status,processing_time', 'orderDetails.addons:order_details_id,addon_id,quantity')->where('type', 'Online')->byStatus($status)->latest('id')->get(['id', 'user_id', 'invoice', 'available_time']);

        return new OrderCollection($orders);
    }

    /**
     * Update online order status
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Customer successfully stored.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *    "message": "The order id field is required. (and 1 more error)",
     *    "errors": {
     *        "order_id": [
     *            "The order id field is required."
     *        ],
     *        "status": [
     *            "The status field is required."
     *        ]
     *    }
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=500 scenario="Internal Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:processing,success,cancel',
        ]);

        $order = Order::where('id', $request->order_id)->where('type', 'Online')->firstOrFail(['id', 'status']);

        if (in_array('processing', [$request->status, $order->status])) {
            $txt = 'accepted';
        }
        if (in_array('cancel', [$request->status, $order->status])) {
            $txt = 'deslined';
        }
        if (in_array('success', [$request->status, $order->status])) {
            $txt = 'complete';
        }

        if (in_array($order->status, ['success', 'cancel'])) {
            return response()->json(['message' => "Order already {$txt}."]);
        }

        $order->update(['status' => $request->status]);

        return response()->json(['message' => "Order {$txt} successfully."]);
    }
}
