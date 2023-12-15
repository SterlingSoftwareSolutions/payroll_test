@extends('layouts.master')
@section('content')

<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Include Bootstrap Datepicker CSS and JS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


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
                    </div>  --}}
                </div>

            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        {{-- <form action="{{ route('all/employee/search') }}" method="POST"> --}}
            @csrf
            <div class="row filter-row">
<!---------------------------------------------------------------->
 
                <!---------------------------------------------------------------------------->
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <select class="select form-control floating" name="department">
                            <option value=""> -- Select Department-- </option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department }}</option>
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
                        <select class="form-control floating" id="monthDropdown">
                            <option value=""></option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>

                        </select>
                        <label class="focus-label">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <button type="button" class="btn btn-danger btn-block rounded" onclick="searchAttendance()">Search</button>
                    {{-- <button type="sumit" class="btn btn-success btn-block"> Search </button>   --}}
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
                                        <th>WO</th>
                                        <th>Holidays</th>
                                        <th>Working days</th>
                                        <th>OT</th>
                                        <th>Extra days</th>
                                        <th class="text-right">Action</th>
                                        {{-- <th class="text-right">
                                            <a href="" class="btn btn-success">
                                                <i class="fa fa-download"></i> Download PDF
                                            </a>
                                        </th> --}}   
                                        {{-- <td class="text-right">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    PDF
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                                    <a class="dropdown-item" href="#" onclick="handleActionSelection('view')">View PDF</a>
                                                    <a class="dropdown-item" href="#" onclick="handleActionSelection('download')">Download PDF</a>
                                                </div>
                                            </div>
                                        </td>                                  --}}
                                    </tr>
                                </thead>
                          
                            </table>


<!-------------------------->
                            {{-- <div class="col-sm border border-danger">
                                <div class="form-group">
                                    <label class="col-form-label">Select Employee <span
                                            class="text-danger">*</span></label>
                                    <datalist id="employees">
                                        @for($i = 0; $i < $employees->count(); $i++)
                                            <option value="{{$employees[$i]->id}}">{{$employees[$i]->full_name}}
                                            </option>
                                            @endfor
                                    </datalist>
                                    <input autoComplete="on" list="employees" class="form-control" style="width: 100%;"
                                        tabindex="-1" aria-hidden="true" id="empolyee_name" type="text"
                                        name="employee_id">
                                    @error('employee_id')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                 <div class="dash-widget-info">

                    {{-- <span>department </span>
                    <h2>{{ $departments }}</h2>
                </div>   --}}
                
                    {{-- <span>Employees count</span>
                    <h2>{{  $employeesCount }}</h2> --}}
                </div>  
    </div>


    <div class="form-group form-focus border border-info">
        <form action="{{ route('all/employee/list/department') }}" method="post">
            @csrf
            <select class="select form-control floating" name="department">
                <option value=""> -- Select Department -- </option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->department }}</option>
                @endforeach
            </select>
            <button type="submit">Show Employees</button>
        </form>
    </div>
    
    {{-- @if(isset($selectedDepartment))
        <h2>Employees  {{ $selectedDepartment->department }} </h2>
        <ul>
            @foreach($employees as $employee)
          
                @if($employee->department_id == $selectedDepartment->id)
                    <li>{{ $employee->name }} - {{ $employee->position }}</li>
                @endif
            @endforeach
        </ul>
    @endif --}}

{{-- 
    @if(isset($selectedDepartment))
    <h2>Employees {{ $selectedDepartment->department }} Department</h2>
    <ul>
        @foreach($employees as $employee)
            @if($employee->department_id == $selectedDepartment->id)
                <li>{{ $employee->name }} - {{ $employee->position }}</li>
            @endif
        @endforeach
    </ul>

   
    <pre>
        {{ dd($selectedDepartment, $employees) }}
    </pre>
@endif  --}}


{{-- 
@if(isset($selectedDepartment))
    <h2>Employees {{ $selectedDepartment->department }} Department</h2>
    <ul>
        @foreach($employees as $employee)
            @if($employee->department_id == $selectedDepartment->id)
                <li>Employee ID: {{ $employee->employee_id }} - Name: {{ $employee->name }}</li>
            @endif
        @endforeach
    </ul> --}}

    {{-- Debugging --}}
    {{-- <pre> --}}
        {{-- {{ dd($selectedDepartment, $employees) }} --}}
        {{-- {{ dd($employees) }} --}}
        {{-- {{ dd($selectedDepartment) }}
    </pre>
@endif --}}

{{-- 
@if(isset($selectedDepartment))
    <h2>Employees {{ $selectedDepartment->department }} Department</h2>
    <div class="card-deck">
        @foreach($employees as $employee)
            @if($employee->department_id == $selectedDepartment->id)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employee ID: {{ $employee->employee_id }}</h5>
                        <p class="card-text">Name: {{ $employee->name }}</p>
                        <!-- Add more details as needed -->
                    </div>
                </div>
            @endif
        @endforeach
    </div> --}}
    {{-- Debugging --}}
    {{-- <pre> --}}
        {{-- {{ dd($selectedDepartment, $employees) }}
    </pre> --}}
{{-- @endif --}}


@if(isset($selectedDepartment))
    <h2>Employees {{ $selectedDepartment->department }} Department</h2>
    <div class="card-deck">
        @foreach($employees as $employee)
            @if($employee->department_id == $selectedDepartment->id)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employee ID: {{ $employee->employee_id }}</h5>
                        <p class="card-text">Name: {{ $employee->name }}</p>
                        {{-- <p class="card-text">Department ID: {{ $employee->department_id }}</p> --}}
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    {{-- Debugging --}}
     <pre>
        {{-- {{ dd($selectedDepartment, $employees) }} --}}
    </pre> 
@endif 






    

                    
 
                </div>
            </div>
        </div>
        

        

@endsection



            </div>
  <!------------------------------/date and month pickup ----------------------------->

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
</script>