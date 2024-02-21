<?php

namespace App\Http\Controllers;

use DB;
use Log;
use League\Csv\Reader;
use App\Models\CsvData;
use League\Csv\Statement;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('form/csvupload');
    }

    // public function uploadCsv(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:2048',
    //     ]);

    //     $file = $request->file('csv_file');

    //     // Read CSV file and get headers and records
    //     $csvData = $this->processCsv($file->getPathname());

    //     // Save data to the database
    //     $this->saveToDatabase($csvData);

    //     // Pass CSV data and other fields to the view
    //     return view('form/csvupload')->with([
    //         'csvData' => $csvData,
    //         'user' => $request->input('user'),
    //         'work_id' => $request->input('work_id'),
    //         'card_no' => $request->input('card_no'),
    //         'date' => $request->input('date'),
    //         'punch_in' => $request->input('punch_in'),  // Add other fields as needed
    //         'IN/OUT' => $request->input('IN/OUT'),
    //         'event_code' => $request->input('event_code'),
    //     ]);
    // }


//     public function uploadCsv(Request $request)
// {
//     $request->validate([
//         'csv_file' => 'required|mimes:csv,txt|max:2048',
//     ]);

//     $file = $request->file('csv_file');

//     // Read CSV file and get headers and records
//     $csvData = $this->processCsv($file->getPathname());

//     // Test data for testing purposes
//     $testData = [
//         'user' => 'test_user',
//         'WorkId' => '123',
//         'CardNo' => '456',
//         'Date' => '2022-01-01',
//         'Punch In' => '08:00:00',
//         'Punch Out' => '17:00:00',
//         'EventCode' => 'ABC123',
//     ];

//     // Add test data to CSV data
//     $csvData[] = $testData;

//     // Save data to the database
//     $this->saveToDatabase($csvData);

//     // Pass CSV data and other fields to the view
//     return view('form/csvupload')->with([
//         'csvData' => $csvData,
//         'user' => $request->input('user'),
//         'work_id' => $request->input('work_id'),
//         'card_no' => $request->input('card_no'),
//         'date' => $request->input('date'),
//         'punch_in' => $request->input('punch_in'),  // Add other fields as needed
//         'IN/OUT' => $request->input('IN/OUT'),
//         'event_code' => $request->input('event_code'),
//     ]);
// }

public function uploadCsv(Request $request)
{
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('csv_file');

    // Read CSV file and get headers and records
    $csvData = $this->processCsv($file->getPathname());

    // Output CSV data for debugging
   

    // Save data to the database
    $this->saveToDatabase($csvData);

    // Pass CSV data and other fields to the view
    return view('form/csvupload')->with([
        'csvData' => $csvData,
        'user' => $request->input('user'),
        'work_id' => $request->input('work_id'),
        'card_no' => $request->input('card_no'),
        'date' => $request->input('date'),
        'punch_in' => $request->input('punch_in'),  // Add other fields as needed
        'IN/OUT' => $request->input('IN/OUT'),
        'event_code' => $request->input('event_code'),
    ]);
}



    private function processCsv($filePath)
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0); // assuming the first row contains headers

        $stmt = (new Statement())->offset(0); // starts from the first row (skip headers)

        $records = $stmt->process($csv);

        return iterator_to_array($records); // Convert MapIterator to an array
    }

    // private function saveToDatabase($csvData)
    // {
    //     foreach ($csvData as $record) {
    //         if (array_key_exists('user', $record)) {
    //             try {
    //                 CsvData::create([
    //                     'user' => $record['user'],
    //                     'work_id' => $record['WorkId'],
    //                     'card_no' => $record['CardNo'],
    //                     'date' => $record['Date'],
    //                     'punch_in' => $record['Punch In'],
    //                     'punch_out' => $record['Punch Out'],
    //                     'event_code' => $record['EventCode'],
    //                     // Add other columns as needed
    //                 ]);
    //             } catch (\Exception $e) {
    //                 // Log the error
    //                 \Log::error('Error saving record to database: ' . $e->getMessage());
    //             }
    //         } else {
    //             // Handle the case where 'user' key is not present in the record
    //             // You can log an error, skip the record, or handle it as appropriate
    //             Log::warning('Record does not have a "user" key: ' . json_encode($record));
    //         }
    //     }
    // }


    private function saveToDatabase($csvData)
    {
        \DB::beginTransaction();
    
        try {
            foreach ($csvData as $record) {
                if (array_key_exists('User', $record)) {
                    CsvData::create([
                        'user' => $record['User'],
                        'work_id' => $record['WorkId'],
                        'card_no' => $record['CardNo'],
                        'date' => $record['Date'],
                        'punch_in' => $record['punch_in'],
                        'punch_out' => $record['punch_out'], // Adjust accordingly if there is a 'punch_out' key in your CSV
                        'event_code' => $record['EventCode'],
                        // Add other columns as needed
                    ]);
    
                    // Add a debug statement
                    \Log::debug('Record saved successfully: ' . json_encode($record));
                } else {
                    // Log a warning if 'User' key is not present in the record
                    \Log::warning('Record does not have a "User" key: ' . json_encode($record));
                }
            }
    
            \DB::commit();
            \Log::debug('Transaction committed successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error committing transaction: ' . $e->getMessage());
        }
    }
    
    


    
    
}
