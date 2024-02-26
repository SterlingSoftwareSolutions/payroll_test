<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Carbon\Carbon;
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
            'csv_file' => 'required|file'
        ]);
    
        // Parse the CSV
        $data = array_map('str_getcsv', file($request->csv_file->getRealPath()));
        $headings = array_shift($data);
    
        foreach ($data as $row) {
            if (array_key_exists('Date', $row) && array_key_exists('punch_in', $row) && array_key_exists('punch_out', $row)) {
                $dateTimeStringIn = $row['Date'] . ' ' . $row['punch_in'];
                $dateTimeStringOut = $row['Date'] . ' ' . $row['punch_out'];
    
                // Use Carbon to parse and format punch_in and punch_out
                $row['punch_in'] = Carbon::createFromFormat('n/j/Y H:i:s', $dateTimeStringIn)->format('Y-m-d H:i:s');
                $row['punch_out'] = Carbon::createFromFormat('n/j/Y H:i:s', $dateTimeStringOut)->format('Y-m-d H:i:s');
            }
    
            $csv_data[] = array_combine($headings, $row);
        }
        // Use insert method to insert multiple records
        CsvData::insert($csv_data);
        dd("success");
        // Additional processing or validation if needed
    
        return view('form/csvupload')->with([
            'CsvData' => $csv_data,
            'user' => $request->input('User'),
            'work_id' => $request->input('WorkId'),
            'card_no' => $request->input('CardNo'),
            'date' => $request->input('Date'),
            'punch_in' => $request->input('punch_in'),
            'punch_out' => $request->input('punch_out'),
        ]);
    }
    




    private function processCsv($filePath)
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0); 

        $stmt = (new Statement())->offset(0); 

     
        $data = $stmt->process($csv);

       
        \Log::debug('Processed CSV data: ' . json_encode(iterator_to_array($data)));

        return iterator_to_array($data); 
    }

  
}
