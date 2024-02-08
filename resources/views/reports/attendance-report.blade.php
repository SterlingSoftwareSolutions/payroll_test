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

            <form method="get" action="{{ route('attendance/report/search') }}">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group ">
                            <select class="select form-control floating" name="department">
                                <option value=""disabled selected> -- Select Department-- </option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @if (request('department') == $department->id) selected @endif>
                                        {{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group ">
                            <input type="number" class="form-control form-control-1 input-sm from-year" name="year"
                                placeholder="Year" value="{{ request('year') }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group ">
                            <select class="form-control floating" id="monthDropdown" name="month">
                                <option value=""disabled selected>--Select Month--</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @if (request('month') == $i) selected @endif>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 ">
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
                                            <a href="/form/attendance/download/{{ $attendance->employee->id }}"><button>Download
                                                    PDF</button></a>
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
