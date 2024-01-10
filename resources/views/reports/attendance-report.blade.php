@extends('layouts.master')
@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- Remove duplicate import -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Include Bootstrap Datepicker CSS and JS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Attendance Report</h3>


                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Report</a></li>
                        <li class="breadcrumb-item active">Attendance Report</li>

                    </ul>
                    {{-- <div class="dash-widget-info">

                        <span>Attendances </span>
                        <h2>{{ $attendanceCount }}</h2>
                    </div> --}}
                </div>

            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        <form method="GET">
            <div class="row filter-row">
                
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                       
                        <select class="select form-control floating" name="department">
                            <option value=""> -- Select Department-- </option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department') == $department->id)>{{ $department->department }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <label class="focus-label">Year</label>
                        <input type="text" class="form-control form-control-1 input-sm from-year" placeholder="Year">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="form-control floating" id="monthDropdown" name="month">
                            <option value="">----</option>
                            <option value="1" @selected(request('month') == 1)>January</option>
                            <option value="2" @selected(request('month') == 2)>February</option>
                            <option value="3" @selected(request('month') == 3)>March</option>
                            <option value="4" @selected(request('month') == 4)>April</option>
                            <option value="5" @selected(request('month') == 5)>May</option>
                            <option value="6" @selected(request('month') == 6)>June</option>
                            <option value="7" @selected(request('month') == 7)>July</option>
                            <option value="8" @selected(request('month') == 8)>August</option>
                            <option value="9" @selected(request('month') == 9)>September</option>
                            <option value="10" @selected(request('month') == 10)>October</option>
                            <option value="11" @selected(request('month') == 11)>November</option>
                            <option value="12" @selected(request('month') == 12)>December</option>
                        </select>
                        <label class="focus-label">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="submit" class="btn btn-danger btn-block rounded">Search</button>
                </div>
            </div>
        </form>
        <!-------------------------------------------------------------------------------->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Number of days</th>
                                <th>Absent days</th>
                                <th>Week Off</th>
                                <th>Holidays</th>
                                <th>Working days</th>
                                <th>OT</th>
                                <th>Extra days</th>
                                <th class="text-right">Action</th>
                          
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->full_name }}</td>
                                <td>Number of days</td>
                                <td>Absent days</td>
                                <td>WO</td>
                                <td>Holidays</td>
                                <td>Working days</td>
                                <td>OT</td>
                                <td>Extra days</td>

                                <td class="text-right">Action</td>
                            </tr>
                            @endforeach
                        </tbody> --}}

                        <tbody>
                            {{-- @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->employee->full_name }}</td>
                                <td>{{ $attendanceCounts->where('employee_id', $attendance->employee->id)->first()->attendance_count ?? 0 }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->employee_id }}</td>
                                <td>
                                    @php
                                        $holiday = $holidays->firstWhere('date', $attendance->date);
                                    @endphp
                        
                                    {{ $holiday ? $holiday->name_holiday : '' }}
                                </td>
                                <td>{{ $attendance->punch_in }}
                                    @if ($attendance->status == 1)
                                        <span class="badge badge-primary badge-pill float-right">On Time</span>
                                    @else
                                        <span class="badge badge-danger badge-pill float-right">Late</span>
                                    @endif
                                </td>
                              

                                <td>{{ $attendance->date }}</td>
                            </tr>
                        @endforeach --}}

                        @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->employee->full_name }}</td>
                            <td>{{ $attendanceCounts->where('employee_id', $attendance->employee->id)->first()->attendance_count ?? 0 }}</td>
                            <td>{{ $attendance->date }}</td>
                            <td>{{ $attendance->employee_id }}</td>

                            <td>
                                @if ($attendance->holiday)
                                    <span class="badge badge-warning badge-pill float-right">Holiday</span>
                                @endif
                            </td>

                            <td>
                                @foreach ($attendance->holiday as $holiday)
                                    @if ($holiday->holiday_date == $attendance->date)
                                        <span class="badge badge-warning badge-pill float-right">Holiday</span>
                                    @endif
                                @endforeach
                            </td>
                    
                    

                           
                            <td>{{ $attendance->punch_in }}
                                @if ($attendance->status == 1)
                                    <span class="badge badge-primary badge-pill float-right">On Time</span>
                                @else
                                    <span class="badge badge-danger badge-pill float-right">Late</span>
                                @endif
                            </td>
                            <td>{{ $attendance->date }}</td>

                 

                            
                        </tr>
                    @endforeach
                    
                        
                        </tbody>
                      


                    </table>

                </div>
            </div>
        </div>
    </div>
</div>



@endsection



</div>
            </div>
            
<!------------------------------/date and month pickup ----------------------------->
{{-- 
<script>
    $('.from-year').datepicker({
        autoclose: true,
        minViewMode: 'years',
        format: 'yyyy'
        });

        $('.from-month').datepicker({
        autoclose: true,
        minViewMode: 'months',
        format: 'MM'
        });

</script> --}}


<script>
    $(document).ready(function () {
        console.log("Script is running."); // Check if script is running

        $('.from-year').datepicker({
            autoclose: true,
            minViewMode: 'years',
            format: 'yyyy'
        });
    });
</script>
