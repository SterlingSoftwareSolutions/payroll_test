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
                        <input type="text" value="{{ request('year') }}" name="year" class="form-control form-control-1 input-sm from-year" placeholder="Year">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="form-control floating" id="monthDropdown" name="month">
                            <option value=""></option>
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
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="actionDropdown" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            PDF
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                            <a class="dropdown-item" href="#"
                                                onclick="handleActionSelection('view')">View PDF</a>
                                            <a class="dropdown-item" href="#"
                                                onclick="handleActionSelection('download')">Download PDF</a>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->f_name }}</td>
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