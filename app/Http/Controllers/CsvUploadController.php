<?php

namespace App\Http\Controllers;

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

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');

        // Read CSV file and get headers and records
        $csvData = $this->processCsv($file->getPathname());

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
}
