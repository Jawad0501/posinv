<?php

namespace App\Http\Controllers\Backend\HR;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\Staff;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_clock_in');
        if (request()->ajax()) {
            $attendance = Attendance::query()->with('staff:id,name');

            return DataTables::eloquent($attendance)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! auth('staff')->user()->isAdmin()) {
                        $query->where('staff_id', auth('staff')->id());
                    }
                }, true)
                ->editColumn('date', fn ($data) => format_date($data->date))
                ->addColumn('action', function () {
                    return '';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.hr.attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_clock_in');
        $staff = Staff::active()->get();

        return view('pages.hr.attendance.form', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_clock_in');
        $this->validate($request, ['staff' => 'required|integer|exists:staff,id']);

        $attendance = Attendance::where('staff_id', $request->staff)->whereDate('date', date('Y-m-d'))->first();
        if ($attendance) {
            return response()->json(['message' => 'Attendance already exists'], 300);
        }
        Attendance::create([
            'staff_id' => $request->staff,
            'date' => date('Y-m-d'),
            'check_in' => date(time_format()),
        ]);

        return response()->json(['message' => 'Attendance added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $this->authorize('edit_clock_in');

        return view('pages.hr.attendance.form', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('edit_clock_in');
        if (! auth('staff')->user()->isAdmin() && $attendance->staff_id != auth('staff')->id()) {
            return response()->json(['message' => 'Something wrong, please try again leter.'], 302);
        }

        $check_out = date(time_format());

        $attendance->update([
            'check_out' => $check_out,
            'stay' => attendance_stay($attendance->check_in, $check_out),
        ]);

        return response()->json(['message' => 'Employee check out successfully']);
    }

    /**
     * log
     */
    public function log()
    {
        $staff = Staff::query();
        if (! auth()->user()->isAdmin()) {
            $staff = $staff->where('id', auth()->id());
        }
        $staff = $staff->active()->get();

        $total_working_time = null;

        if (request()->has('staff')) {
            $from_date = request()->get('from_date');
            $to_date = request()->get('to_date');

            $attendances = Attendance::query()->with('staff:id,name');
            if ((isset($from_date) && ! empty($from_date)) && (isset($to_date) && ! empty($to_date))) {
                $attendances = $attendances->whereBetween('date', [$from_date, $to_date]);
            }
            $attendances = $attendances->where('staff_id', request()->get('staff'))->get();
            $total_working_time = subtotal_time($attendances->pluck('stay'));
        } else {
            $period = new DatePeriod(new DateTime('2022-06-01'), new DateInterval('P1D'), new DateTime(date('Y-m-d').' +1 day'));

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }

            $attendances = [];
            krsort($dates);
            foreach ($dates as $date) {
                $attendances[$date] = Attendance::with('staff:id,name')->whereDate('date', $date)->get();
            }
        }

        return view('pages.hr.attendance.log', compact('attendances', 'staff', 'total_working_time'));
    }

    /**
     * showUploadForm
     */
    public function showUploadForm()
    {
        return view('pages.hr.attendance.upload-form');
    }

    /**
     * upload
     */
    public function upload(Request $request)
    {
        $this->validate($request, ['file' => 'required|file|mimes:xlsx,csv|max:2048']);

        Excel::import(new AttendanceImport, $request->file);

        return response()->json(['message' => 'Attendance bulk upload successfully']);
    }
}
