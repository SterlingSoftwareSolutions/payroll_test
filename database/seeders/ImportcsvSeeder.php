<?php

namespace Database\Seeders;

use App\Models\Importcsv;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                // Check for 'NULL' string in time values and handle accordingly
                $punchIn = $this->parseTime($data[2]);
                $punchOut = $this->parseTime($data[3]);

                Importcsv::create([
                    'workID' => $data[0],
                    'csv_date' => Carbon::parse($data[3])->format('Y-m-d'),
                    'punch_in' => $punchIn ?? now(), // Set a default value or handle null appropriately
                    'punch_out' => $punchOut ?? now(), // Set a default value or handle null appropriately
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

        // Use Carbon to parse the valid time string
        return Carbon::parse($time);
    }

}
