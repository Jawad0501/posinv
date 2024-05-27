<?php

namespace App\Http\Controllers\Backend\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;

class ClockInController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $this->authorize('create_attendance');

        return view('pages.hr.clock-in.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_clock_in');
        $this->validate($request, ['id_number' => 'required|string|exists:staff,id_number']);

        $staff = Staff::where('id_number', $request->id_number)->first(['id']);
        $attendance = Attendance::where('staff_id', $staff->id)->whereDate('date', date('Y-m-d'))->first();
        $current_time = date(time_format());
        if ($attendance) {
            $attendance->update([
                'check_out' => $current_time,
                'stay' => attendance_stay($attendance->check_in, $current_time),
            ]);

            return response()->json(['message' => 'You are attendance out successfully']);
        }

        Attendance::create([
            'staff_id' => $staff->id,
            'date' => date('Y-m-d'),
            'check_in' => $current_time,
        ]);

        return response()->json(['message' => 'You are attendance is successfully']);
    }

    /**
     * Check a newly created resource in storage.
     */
    public function check(Request $request)
    {
        $this->authorize('create_clock_in');
        $this->validate($request, ['id_number' => 'required|string|exists:staff,id_number']);

        $staff = Staff::where('id_number', $request->id_number)->first(['id']);
        $attendance = Attendance::where('staff_id', $staff->id)->whereDate('date', date('Y-m-d'))->first();

        return response()->json($attendance ? true : false);
    }
}
