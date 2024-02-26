{{-- 

 @extends('layouts.master')
@section('content')


<div class="page-wrapper">
 <!-- Page Content -->
 <div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-lists-center">
            <div class="col">
                <h3 class="page-title">Attendence</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">CSV Report</li>
                </ul>
            </div>

            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_attendance"
                    id="add_attendence"><i class="fa fa-plus"></i> Upload CSV</a>

            </div>
        </div>
    </div>

    <form action="{{ route('csvupload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">CSV File:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        </div>
    
       
    
        <!-- Add more fields as needed -->
    
        <button type="submit">Upload CSV</button>
    </form>
    



    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>user</th>
                            <th>WorkId</th>
                            <th>CardNo</th>
                            <th>Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Production</th>
                            <th>EventCode</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Autofill fields based on CSV data headers -->
                        @if(isset($csvData) && count($csvData) > 0)
                            @foreach($csvData as $record)
                                <tr>
                                    @foreach($record as $header => $value)
                                        <td>
                                            <label for="{{ $header }}">{{ ucfirst($header) }}:</label>
                                            <input type="text" name="{{ $header }}" id="{{ $header }}"
                                                placeholder="{{ ucfirst($header) }}" required
                                                value="{{ old($header, isset($$header) ? $$header : '') }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

    </div>
       
    
</div>
   





@endsection  --}}





@extends('layouts.master')
@section('content')


<div class="page-wrapper">
 <!-- Page Content -->
 <div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-lists-center">
            <div class="col">
                <h3 class="page-title">Attendence</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">CSV Report</li>
                </ul>
            </div>

            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_attendance"
                    id="add_attendence"><i class="fa fa-plus"></i> Upload CSV</a>

            </div>
        </div>
    </div>

    <form action="{{ route('csvupload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">CSV File:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        </div>
    
       
    
        <!-- Add more fields as needed -->
    
        <button type="submit">Upload CSV</button>
    </form>
    



    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table datatable">
                    <thead>
                        <tr>
                           
                            <th>user</th>
                            <th>WorkId</th>
                            <th>CardNo</th>
                            <th>Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>EventCode</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Autofill fields based on CSV data headers -->
                        @if(isset($CsvData) && count($CsvData) > 0)
                            @foreach($CsvData as $data)
                                <tr>
                                    @foreach($data as $header => $value)
                                        <td>{{ $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

    </div>
       
    
</div>
   





@endsection 
