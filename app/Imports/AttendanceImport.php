<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $staff = Staff::where('name', $row['name'])->first();

        if ($staff) {
            return new Attendance([
                'staff_id' => $staff->id,
                'date' => date('Y-m-d', strtotime($row['date'])),
                'check_in' => $row['check_in'],
                'check_out' => $row['check_out'],
                'stay' => $row['stay'],
            ]);
        }
    }
}
