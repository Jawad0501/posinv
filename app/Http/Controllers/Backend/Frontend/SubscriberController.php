<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_subscriber');
        if (request()->ajax()) {
            $subscribers = Subscriber::query();

            return DataTables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('frontend.subscriber.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_subscriber'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.frontend.subscriber');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber)
    {
        $this->authorize('delete_subscriber');
        $subscriber->delete();

        return response()->json(['message' => 'Subscriber deleted successfully']);
    }
}
