<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dates = new DatePeriod(new DateTime('2022-06-01'), new DateInterval('P1D'), new DateTime(date('Y-m-d').' +1 day'));

        foreach ($dates as $date) {
            $check_in = date(time_format());
            $check_out = Carbon::parse(date(time_format()))->addHour(rand(1, 4))->addMinute(rand(1, 40))->addSeconds(rand(1, 40))->format(time_format());

            Attendance::create([
                'staff_id' => rand(1, 4),
                'date' => $date->format('Y-m-d'),
                'check_in' => $check_in,
                'check_out' => $check_out,
                'stay' => attendance_stay($check_in, $check_out),
            ]);
        }
    }
}
