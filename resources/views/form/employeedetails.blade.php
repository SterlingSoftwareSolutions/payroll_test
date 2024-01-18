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
                                <button type="submit" class="edit-icon">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <p class="text-secondary">{{ $employee->j_title }}</p>
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
                                    <td class="title">Address</td>
                                    <td class="text">{{ $employee->description }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <style>
            .nav-tabs .nav-link label {
                padding: 10px 15px;
                /* Adjust the padding for spacing */
                background-color: #ed5a5b;
                /* Set your desired background color */
                color: #fff;
                /* Set text color */
                border-radius: 100px;
                /* Set border radius to achieve a rounded appearance */
                display: inline-block;
                /* Ensure the label behaves as a block element */
            }

            .custom-label {
                padding: 10px;
                padding-left: 20px;
                padding-right: 20px;
                background-color: #ed5a5b;
                color: #fff;
                border-radius: 100px;
                display: inline-block;
                width: 180px;
                text-align: center;
            }

            .nav {
                margin-left: 48px;
            }

            .custom-nav-item {}
        </style>

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
                                        <span class="custom-label">Bank & Statutory</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="tab-content">
            <!-- Profile Info Tab -->
            <div id="emp_profile" class="pro-overview tab-pane fade show active">
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
                                            <td class="title">Job title</td>
                                            <td class="text">{{ $employee->j_title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Date of Birth</td>
                                            <td class="text">{{ $employee->dob->format('Y-m-d') }}</td>
                                            <td></td>
                                            <td class="title">Created Date</td>
                                            <td class="text">{{ $employee->createdDate->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">NIC</td>
                                            <td class="text">{{ $employee->nic }}</td>
                                            <td></td>
                                            <td class="title">Joined Date</td>
                                            <td class="text">{{ $employee->joinedDate->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Phone</td>
                                            <td class="text">{{ $employee->c_number }}</td>
                                            <td></td>
                                            <td class="title">Status</td>
                                            <td class="text">{{ $employee->status }}</td>
                                        </tr>
                                        <tr>
                                            <td class="title">Email</td>
                                            <td class="text">{{ $employee->email }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="title">Address</td>
                                            <td class="text">{{ $employee->description }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="content container-fluid">
                            <div class="card profile-box flex-fill">
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
                        <div class="card profile-box flex-fill">
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
        $holiday = $holiday ? $holiday->where('date_holiday', $attendance->date)->first() : null;
        @endphp
        <tr data-employee-id="{{ $attendance->employee->id }}">
            <td>{{ optional($attendance->employee)->full_name ?? '' }}</td>
            <td>{{ $totDays }}</td>
            <td>{{ $totDays - ($attendanceCounts->where('employee_id',
                optional($attendance->employee)->id)->first()->attendance_count ?? 0) -
                optional($attendance->employee->holiday)->count() }}</td>
            <td>{{ $weekendCount }}</td>
            {{-- <td>
                @if ($attendance->is_holiday)
                <span class="badge badge-warning badge-pill float-right">{{ $attendance->holiday_name }}</span>
                @endif
            </td> --}}
            <td>{{ $employeeHolidayCounts[$attendance->employee_id] ?? 0 }}</td>
            <td>{{ $attendanceCounts->where('employee_id',
                optional($attendance->employee)->id)->first()->attendance_count ?? 0 }}</td>
            <td>{{ $attendance->overtime ?? 'N/A' }}</td>
            <td>{{ $extraDaysCount }}</td>
            <td>
                <button onclick="generateAndDownloadEmployeePDF({{ $attendance->employee->id }})">Download PDF</button>
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
