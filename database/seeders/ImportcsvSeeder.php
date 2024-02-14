<?php

namespace Database\Seeders;

use App\Models\Importcsv;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ImportcsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Importcsv::truncate();
        $csvData = fopen(base_path('database/csv/recordList.csv'), 'r');
        $csvRaw = true;

        while (($data = fgetcsv($csvData, '555', ',')) !== false) {
            if (!$csvRaw) {
                $time = Carbon::parse($data[4])->format('H:i:s');

                Importcsv::create([
                    'workID' => $data[1],
                    'csv_date' => Carbon::parse($data[3])->format('Y-m-d'),
                    'punch_in' => Carbon::parse($data[3] . ' ' . $time),
                    'punch_out' => Carbon::parse($data[3] . ' ' . $time),
                ]);
            }
            $csvRaw = false;
        }

        fclose($csvData);
    }

    /**
     * Parse time string, handling 'NULL' string gracefully
     *
     * @param string $time
     * @return string|null
     */
    protected function parseTime($time)
    {
        // Check if the time string is 'NULL' or starts with a single quote
        if ($time === 'NULL' || strpos($time, "'") === 0) {
            return null; // or you can set a default time value
        }

        // Check if the time format is 'HH:mm:ss'
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return Carbon::parse($time);
        }

        // Handle other time formats if necessary

        // If none of the formats match, return null or set a default value
        return null;
    }
}
