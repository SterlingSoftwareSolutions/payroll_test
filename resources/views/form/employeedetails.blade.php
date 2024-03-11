@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Employee Details</h3>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <!-- /Page Header -->
            <style>
                .form-row {
                    margin-bottom: 10px;
                    /* Adjust the margin-bottom to control the space between rows */
                }

                .title {
                    font-weight: bold;
                    /* Optionally make the title bold */
                }

                .text {
                    margin-top: 5px;
                    /* Adjust the margin-top to control the space between title and text */
                }
            </style>

            <div class="card profile-box flex-fill">
                <div class="card-body">
                    <div class="m-10 ml-4 basis-1/2">
                        <form method="POST" action="{{ route('form.employee.edit', $employee->employee_id) }}">
                            @csrf
                            @method('PUT')

                            <h3 class="card-title">{{ $employee->full_name }}
                                {{-- <button type="submit" class="edit-icon">
                                    <i class="fa fa-pencil"></i>
                                </button> --}}
                                <p class="text-secondary">{{ $job_title }}</p>
                            </h3>
                    </div>

                    <div class="form-row m-2">
                        <div class="form-group col-md-12">
                            <table class="table table-borderless">
                                <tr class="table-default">
                                    <td class="title">Employee ID</td>
                                    <td class="text">{{ $employee->employee_id }}</td>
                                    <td></td>

                                    <td class="title">Phone</td>
                                    <td class="text">{{ $employee->c_number }}</td>
                                </tr>
                                <tr class="table-default">
                                    <td class="title">Work ID</td>
                                    <td class="text">{{ $employee->work_id }}</td>
                                    <td></td>

                                    <td class="title">ETF NO</td>
                                    <td class="text">{{ $employee->etf_no }}</td>
                                </tr>
                                <tr>
                                    <td class="title">Date Of Join</td>
                                    <td class="text">{{ $employee->joinedDate->format('Y-m-d') }}</td>
                                    <td></td>
                                    <td class="title">Email</td>
                                    <td class="text">{{ $employee->email }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    {{-- <td class="title">Address</td>
                                    <td class="text">{{ $employee->address }}</td> --}}
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <style>
            .custom-label {
                padding: 10px;
                padding-left: 20px;
                padding-right: 20px;
                background-color: transparent;
                border: 2px solid #ed5a5b;
                color: #ed5a5b;
                border-radius: 100px;
                display: inline-block;
                width: 180px;
                text-align: center;
            }

            span.custom-label-click-style {
                background-color: #ed5a5b;
                color: #ffffff !important;
                border-radius: 100px;
                padding: 10px;
                display: inline-block;
                padding-left: 20px;
                padding-right: 20px;
                /* width: 180px; */
                text-align: center;
            }

            .nav {
                margin-left: 48px;
            }

            .custom-nav-item {}
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var elements = document.querySelectorAll('.custom-nav-link');

                elements.forEach(function(element) {
                    element.addEventListener('click', function() {
                        // Remove the class and reset the style from all elements
                        elements.forEach(function(el) {
                            el.classList.remove('custom-label-click-style');
                            var customLabel = el.querySelector('.custom-label');
                            customLabel.style.color = '#ed5a5b'; // Reset to the default color
                            customLabel.style.backgroundColor =
                                'transparent'; // Reset background color
                        });

                        // Add the class to the clicked element
                        this.classList.add('custom-label-click-style');

                        // Update the style of the custom-label element
                        var customLabel = this.querySelector('.custom-label');
                        customLabel.style.color = '#ffffff';
                        customLabel.style.backgroundColor = '#ed5a5b';
                    });
                });
            });
        </script>




        {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}

        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <div class="row user-tabs">
                        <div class="col-lg-12 col-md-12 col-sm-12 custom-line-tabs">
                            <ul class="nav custom-nav-tabs">
                                <li class="nav-item custom-nav-item">
                                    <a href="#emp_profile" data-toggle="tab" class="nav-link custom-nav-link active">
                                        <span class="custom-label">Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item custom-nav-item">
                                    <a href="#emp_salary" data-toggle="tab" class="nav-link custom-nav-link">
                                        <span class="custom-label">Salary</span>
                                    </a>
                                </li>
                                <li class="nav-item custom-nav-item">
                                    <a href="#bank_statutory" data-toggle="tab" class="nav-link custom-nav-link">
                                        <span class="custom-label">Attendance</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <script>
            $(document).ready(function() {
                // Handle tab click event
                $('.nav-link').click(function() {
                    // Remove active class from all tabs
                    $('.nav-link').removeClass('active');
                    // Remove active class from all labels
                    $('.custom-label').removeClass('active');

                    // Add active class to the clicked tab and its label
                    $(this).addClass('active');
                    $(this).find('.custom-label').addClass('active');
                });
            });
        </script> --}}

        <div class="tab-content">
            <!-- Profile Info Tab -->
            <div id="emp_profile" class="pro-overview tab-pane fade show active  m-3">
                <div class="content container-fluid mt-4">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <div class="m-10 ml-4 basis-1/2">
                                <form method="POST" action="{{ route('form.employee.edit', $employee->employee_id) }}">
                                    @csrf
                                    @method('PUT')

                                    <h3 class="card-title ml-2 mt">Personal Information
                                        <button type="submit" class="edit-icon">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </h3>
                                </form>
                            </div>

                            <div class="form-row m-2">
                                <div class="form-group col-md-12">
                                    <table class="table table-borderless">
                                        <tr class="table-default">
                                            <td class="title">First Name</td>
                                            <td class="text">{{ $employee->f_name }}</td>
                                            <td></td>
                                            <td class="title">Employee ID</td>
                                            <td class="text">{{ $employee->employee_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Last Name</td>
                                            <td class="text">{{ $employee->l_name }}</td>
                                            <td></td>
                                            <td class="title">Department</td>
                                            <td class="text">{{ $employee->d_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Full Name</td>
                                            <td class="text">{{ $employee->full_name }}</td>
                                            <td></td>
                                            <td class="title">Work ID</td>
                                            <td class="text">{{ $employee->work_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Date of Birth</td>
                                            <td class="text">{{ $employee->dob->format('Y-m-d') }}</td>
                                            <td></td>
                                            <td class="title">ETF NO</td>
                                            <td class="text">{{ $employee->etf_no }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">NIC</td>
                                            <td class="text">{{ $employee->nic }}</td>
                                            <td></td>
                                            <td class="title">Job title</td>
                                            <td class="text">{{ $job_title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Phone</td>
                                            <td class="text">{{ $employee->c_number }}</td>
                                            <td></td>
                                            <td class="title">Job Status</td>
                                            <td class="text">{{ $job_status->status_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Email</td>
                                            <td class="text">{{ $employee->email }}</td>
                                            <td></td>
                                            <td class="title">Status</td>
                                            <td class="text">{{ $employee->status }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Address</td>
                                            <td class="text">{{ $employee->address }}</td>
                                            <td></td>
                                            <td class="title">Created Date</td>
                                            <td class="text">{{ $employee->createdDate->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title"></td>
                                            <td class="text"></td>
                                            <td></td>
                                            <td class="title">Joined Date</td>
                                            <td class="text">{{ $employee->joinedDate->format('Y-m-d') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6 d-flex">
                        <div class="content container-fluid">
                            <div class="card profile-box flex-fill ">
                                <div class="card-body">
                                    <div class="m-10 ml-4 basis-1/2">
                                        <form method="POST"
                                            action="{{ route('form.employee.edit', $employee->employee_id) }}">
                                            @csrf
                                            @method('PUT')

                                            <h3 class="card-title ml-2">Bank information
                                                <button type="submit" class="edit-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </h3>
                                        </form>

                                        <table class="table table-borderless">
                                            <tr class="table-default">
                                                <td class="title">Account Name</td>
                                                <td class="text">{{ $employee->account_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="title">Account No</td>
                                                <td class="text">{{ $employee->account_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="title">Bank Name</td>
                                                <td class="text">{{ $employee->bank_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="title">Branch Name</td>
                                                <td class="text">{{ $employee->branch }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill mr-3">
                            <div class="card-body">
                                <form method="POST" action="{{ route('form.employee.edit', $employee->employee_id) }}">
                                    @csrf
                                    @method('PUT')

                                    <h3 class="card-title ml-2">Salary Informations
                                        <button type="submit" class="edit-icon">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </h3>
                                </form>
                                <table class="table table-borderless">
                                    <tr class="table-default">
                                        <td class="title">Basic Salary</td>
                                        <td class="text">{{ $employee->basic_Salary }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Salary Type</td>
                                        <td class="text">Monthly</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($annualLeaves == 'true')
                    <div class="">
                        <div class="content container-fluid">
                            <div class="card profile-box flex-fill" style="min-height: 25px;">
                                <div class="card-body">
                                    <div class=" basis-1/2">

                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="title">Year</td>
                                                <td class="text">{{ $annual->year }}</td>
                                                <td></td>
                                                <td class="title">Total Leaves</td>
                                                <td class="text">{{ $annual->total_leaves }}</td>
                                            </tr>
                                            <tr>
                                                <td class="title">Used Leaves</td>
                                                <td class="text">{{ $annual->used_leaves }}</td>
                                                <td></td>
                                                <td class="title">Available</td>
                                                <td class="text">{{ $annual->available }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


            <!-- /Profile Info Tab -->
            <!-- Salary Tab -->
            <div class="tab-pane fade" id="emp_salary">
                <div class="pro-overview tab-pane fade show active">
                    <div class="content container-fluid">
                        <div class="container">

                            <table class="table table-striped custom-table datatable ">
                                <thead class="thead-light">
                                    <tr>

                                        <th scope="col">Employee ID</th>
                                        <th scope="col">Month</th>
                                        <th scope="col">Employee Name</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody class="customtable">
                                    @foreach ($salary as $salary)
                                        <tr>
                                            <td>{{ $employee->employee_id }}</td>
                                            <td>{{ $salary->date }}</td>
                                            <td>{{ $employee->full_name }}</td>
                                            <td>{{ $employee->j_title }}</td>
                                            <td>{{ $employee->basic_Salary }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Salary Tab -->
            <!-- Bank Statutory Tab -->
            <div class="tab-pane fade" id="bank_statutory">
                <div class="pro-overview tab-pane fade show active">
                    <div class="content container-fluid">
                        <div class="container">

                            <table class="table table-striped custom-table datatable border=1" id="attendanceTable">
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        @php
                                            $holiday = $holiday
                                                ? $holiday->where('date_holiday', $attendance->date)->first()
                                                : null;
                                        @endphp
                                        <tr data-employee-id="{{ $attendance->employee->id }}">
                                            <td>{{ optional($attendance->employee)->full_name ?? '' }}</td>
                                            <td>{{ $totDays }}</td>
                                            <td>{{ $totDays -
                                                ($attendanceCounts->where('employee_id', optional($attendance->employee)->id)->first()->attendance_count ?? 0) -
                                                optional($attendance->employee->holiday)->count() }}
                                            </td>
                                            <td>{{ $weekendCount }}</td>
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
                                                <button
                                                    onclick="generateAndDownloadEmployeePDF({{ $attendance->employee->id }})">Download
                                                    PDF</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <!-- /Bank Statutory Tab -->
        </div>
    </div>
@endsection
