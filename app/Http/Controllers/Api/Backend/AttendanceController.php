<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Attendance Management
 *
 * APIs to manage authenticated Attendance information
 */
class AttendanceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(['id_number' => 'required|exists:staff,id_number'], ['id_number.exists' => 'Your given id number is invalid.']);

        $staff = DB::table('staff')->where('id_number', $request->id_number)->first();
        $attendance = Attendance::where('staff_id', $staff->id)->where('date', date('Y-m-d'))->first();

        $current_time = date(time_format());
        $status = 302;
        if ($attendance) {
            if ($attendance->check_out_time != null) {
                $msg = "$staff->name already given your attendance.";
            } else {
                $attendance->update([
                    'check_out' => $current_time,
                    'stay' => attendance_stay($attendance->check_in_time, $current_time),
                ]);
                $msg = "$staff->name you are check out successfully.";
                $status = 200;
            }
        } else {
            Attendance::query()->create([
                'staff_id' => $staff->id,
                'date' => date('Y-m-d'),
                'check_in' => $current_time,
            ]);
            $msg = "$staff->name you are check in successfully.";
            $status = 200;
        }

        return response()->json(['message' => $msg], $status);
    }
}
