<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\CsvFile;
use App\Models\Attendance;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function showForm(){
        return view('form.attendanceemployee');
    }

    public function upload(Request $request){
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240',

        ]);
        $file = $request->file('csv_file');
       // dd($file);

        $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
        //dd($path);

        CsvFile::create(['file_path' => $path]);


        // Process the uploaded file as needed

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }


    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:10240',
    //     ]);
    
    //     $file = $request->file('csv_file');
    //     $csvData = array_map('str_getcsv', file($file->path()));
    
    //     // foreach ($csvData as $row) {
    //     //     // Assuming CSV structure: id,employee_id,date,punch_in,punch_out
    //     //     Attendance::create([
    //     //         'id' => $row[0],
    //     //         'employee_id' => $row[1],
    //     //         'date' => $row[2],
    //     //         'punch_in' => $row[3],
    //     //         'punch_out' => $row[4],
    //     //     ]);
    //     // }

    //     foreach ($csvData as $row) {
    //         // Replace 'NULL' with null
    //         $row = array_map(function($value) {
    //             return ($value === 'NULL') ? null : $value;
    //         }, $row);
        
    //         // Assuming 'Date' is in 'm/d/Y' format
    //         $date = null;
        
    //         if ($row[3] !== null) {
    //             $dateTime = DateTime::createFromFormat('m/d/Y', $row[3]);
        
    //             if ($dateTime !== false) {
    //                 $date = $dateTime->format('Y-m-d');
    //             } else {
                  
    //             }
    //         }
        
            // Assuming CSV structure: User,WorkId,CardNo,Date,Time,IN/OUT,EventCode
            Attendance::create([
                'user' => $row[0],
                'work_id' => $row[1],
                'card_no' => $row[2],
                'date' => $date,
                'time' => $row[4],
                'in_out' => $row[5],
                'event_code' => $row[6],
            ]);
        }
        
    
    //     // Fetch attendance data
    //     $attendances = Attendance::all();
    
    //     // Save the file details in the database
    //     $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
    //     CsvFile::create(['file_path' => $path]);
    
    //     return view('form.attendanceemployee', compact('attendances'))->with('success', 'CSV data imported successfully!');
    // }




    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|mimes:csv,txt|max:10240',
    //     ]);

    //     $file = $request->file('csv_file');
    //     $csvData = array_map('str_getcsv', file($file->path()));

    //    //dd($csvData);


    //     // Mapping between WorkId and employee_id
    //     $workIdToEmployeeIdMapping = [
    //         '1' => 1001, // Replace with actual mappings
    //         '2' => 1002, // Replace with actual mappings
    //         // Add more mappings as needed
    //     ];

    //     foreach ($csvData as $row) {
    //         // Replace 'NULL' with null
    //         $row = array_map(function ($value) {
    //             return ($value === 'NULL') ? null : $value;
    //         }, $row);
        
    //         // Assuming 'Date' is in 'm/d/Y' format
    //         $date = null;
    //         if ($row[3] !== null) {
    //             $dateTime = DateTime::createFromFormat('m/d/Y', $row[3]);
    //             if ($dateTime !== false) {
    //                 $date = $dateTime->format('Y-m-d');
    //             } else {
    //                 // Handle the case where the date cannot be parsed
    //                 // For example, log an error or set $date to a default value
    //                 // $date = 'default_value';
    //             }
    //         }
        
    //         // Parse punch_in time
    //         $punchIn = null;
    //         if ($row[4] !== null) {
    //             $punchInDateTime = DateTime::createFromFormat('H:i:s', $row[4]);
    //             if ($punchInDateTime !== false) {
    //                 $punchIn = $punchInDateTime->format('H:i:s');
    //             } else {
    //                 // Handle the case where punch_in time cannot be parsed
    //                 // For example, log an error or set $punchIn to a default value
    //                 // $punchIn = 'default_value';
    //             }
    //         }
        
    //         // Assuming 'EmployeeId' is the column in your CSV file for employee_id
    //         $employeeId = $row[7]; // Adjust the index based on your CSV structure
        
    //         // Assuming CSV structure: User,WorkId,CardNo,Date,Time,IN/OUT,EventCode,EmployeeId
    //         Attendance::create([
    //             'user' => $row[0],
    //             'work_id' => $row[1],
    //             'card_no' => $row[2],
    //             'date' => $date,
    //             'punch_in' => $row[4],
    //             'in_out' => $row[5],
    //             'event_code' => $row[6],
    //             'employee_id' => $employeeId,
    //         ]);
    //     }
        

    //     // Save the file details in the database
    //     $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
    //     CsvFile::create(['file_path' => $path]);

    //     // Fetch attendance data
    //     $attendances = Attendance::all();

    //     return view('form.attendanceemployee', compact('attendances'))->with('success', 'CSV data imported successfully!');
    // }
}
