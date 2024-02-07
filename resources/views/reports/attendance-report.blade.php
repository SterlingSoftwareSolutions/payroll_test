@extends('layouts.master')
@section('content')
    <!-- Add this to the head section of your HTML file -->
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>




    <!-- Include Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

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

                    <div class="col d-flex justify-content-between">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h3 class="page-title">Attendance Report</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Report</a></li>
                                    <li class="breadcrumb-item active">Attendance Report</li>
                                </ul>
                            </div>


                        </div>


                    </div>



                </div>



            </div>

            <!-- /Page Header -->

            <!-- Search Filter -->
            <!-- Search Filter -->
            {{-- <form method="GET">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="select form-control floating" name="department">
                            <option value=""> -- Select Department-- </option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department')==$department->id)>{{
                                $department->department }}</option>
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
                            <option value=""></option>
                            <option value="1" @selected(request('month')==1)>January</option>
                            <option value="2" @selected(request('month')==2)>February</option>
                            <option value="3" @selected(request('month')==3)>March</option>
                            <option value="4" @selected(request('month')==4)>April</option>
                            <option value="5" @selected(request('month')==5)>May</option>
                            <option value="6" @selected(request('month')==6)>June</option>
                            <option value="7" @selected(request('month')==7)>July</option>
                            <option value="8" @selected(request('month')==8)>August</option>
                            <option value="9" @selected(request('month')==9)>September</option>
                            <option value="10" @selected(request('month')==10)>October</option>
                            <option value="11" @selected(request('month')==11)>November</option>
                            <option value="12" @selected(request('month')==12)>December</option>
                        </select>
                        <label class="focus-label">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 border-radius border ">
                    <button type="submit" class="btn btn-danger btn-block rounded">Search</button>
                </div>
                <div>
                </div>


            </div>
        </form> --}}



            {{-- <form method="GET">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="select form-control floating" name="department">
                            <option value=""> -- Select Department-- </option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department')==$department->id)>{{
                                $department->department }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <label class="focus-label">Year</label>
                        <input type="number" class="form-control form-control-1 input-sm from-year" name="year"
                            placeholder="Year" value="{{ request('year') }}">
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="form-control floating" id="monthDropdown" name="month">
                            <option value=""></option>
                            @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" @selected(request('month')==$i)>{{
                                date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                        </select>
                        <label class="focus-label">Month</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3 border-radius border">
                    <button type="submit" class="btn btn-danger btn-block rounded">Search</button>
                </div>


            </div>
        </form>
        --}}

            <form method="GET">
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Department</label>
                            <select class="select form-control floating" name="department">
                                <option value=""> -- Select Department-- </option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @if (request('department') == $department->id) selected @endif>
                                        {{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Year</label>
                            <input type="number" class="form-control form-control-1 input-sm from-year" name="year"
                                placeholder="Year" value="{{ request('year') }}">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Month</label>
                            <select class="form-control floating" id="monthDropdown" name="month">
                                <option value=""></option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @if (request('month') == $i) selected @endif>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3 border-radius border">
                        <button type="submit" class="btn btn-danger btn-block rounded">Search</button>
                    </div>
                </div>
            </form>


            @if (request()->has('department'))
                <div style="margin-top: 20px;">
                    <h4>Selected Department ID:</h4>
                    <p>{{ request('department') }}</p>
                </div>
            @endif

            <!------------------------------------>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable border=1" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>Number of days</th>
                                    <th>Absent days</th>
                                    <th>WO</th>
                                    <th>Holidays</th>
                                    <th>Working days</th>
                                    <th>OT</th>
                                    <th>Extra days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($attendances as $attendance)
                                @php
                                    // Extract year from the date
                                  //  dd($attendance);
                                    $date = \Carbon\Carbon::parse($attendance->date);
                                    $year = $date->format('Y');
                                    $month = $date->format('M');
                                @endphp
                                <tr data-employee-id="{{ $attendance->employee->id }}">
                                    <td>{{ optional($attendance->employee)->full_name ?? '' }}</td>
                                    <td>
                                        @php
                                            $departmentId = optional($attendance->employee)->d_name;
                                            $departmentName = '';
                                            if ($departmentId) {
                                                $department = \App\Models\Department::find($departmentId);
                                                if ($department) {
                                                    $departmentName = $department->department;
                                                }
                                            }
                                        @endphp
                                        {{ $departmentName }}
                                    </td>
                                    <td>{{ $year }}</td>
                                    <td>{{ $month }}</td>
                                    <td>{{ $totDays }}</td>
                                    <td>{{ $totDays -
                                        ($attendanceCounts->where('employee_id', optional($attendance->employee)->id)->first()->attendance_count ?? 0) -
                                        optional($attendance->employee->holiday)->count() }}
                                    </td>
                                    <td>{{ $weekendCount }}</td>
                                    {{-- Uncomment if needed --}}
                                    {{-- <td>
                                        @if ($attendance->is_holiday)
                                        <span class="badge badge-warning badge-pill float-right">{{ $attendance->holiday_name }}</span>
                                        @endif
                                    </td> --}}
                                    <td>{{ $employeeHolidayCounts[$attendance->employee_id] ?? 0 }}</td>
                                    <td>{{ $attendanceCounts->where('employee_id', optional($attendance->employee)->id)->first()->attendance_count ?? 0 }}
                                    </td>
                                    <td>{{ $attendance->overtime ?? 'N/A' }}</td>
                                    <td>{{ $extraDaysCount }}</td>
                                    <td>
                                        <a href="/form/attendance/download/{{ $attendance->employee->id }}"><button>Download PDF</button></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- Add a button to download all rows -->

                    </div>




                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

<!-------------------------------------------------------------------------------------------->

{{-- <div id="print_report" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>hello</h2>
            </div>
        </div>
    </div>
</div> --}}


{{-- @section('content')
<form action="/attendance-form" method="post">
    @csrf
    <table border="1">
        <!-- ... (same HTML form as before) ... -->
    </table>

    <button type="submit">Submit</button>

    <!-- Add a button for downloading the PDF -->
    <a href="form/attendance/pdf" target="_blank" class="btn btn-secondary">Download PDF</a>
</form>
@endsection --}}









<!-- /Edit attendance report download Modal -->
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


<script>
    $(document).ready(function() {
        $('.action-icon.dropdown-toggle').click(function() {
            $(this).next('.dropdown-menu').toggleClass('show');
        });

        $(document).click(function(event) {
            var dropdown = $('.action-icon.dropdown-toggle');
            if (!dropdown.is(event.target) && dropdown.has(event.target).length === 0) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
</script>
