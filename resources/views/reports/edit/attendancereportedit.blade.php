@extends('layouts.master')
@section('content')
    <!-- Add this to the head section of your HTML file -->
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <!-- jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JS -->



    <!-- Include Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Include Bootstrap Datepicker CSS and JS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>






    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col d-flex justify-content-between">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h3 class="page-title">Attendance Report Edit</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Report</a></li>
                                    <li class="breadcrumb-item active">Attendance Report Edit</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal-body">
                <form method="POST" action="{{ route('attendance-report.update',['id' => $id]) }}">
                    @csrf
                    @foreach ($attendanceReport as $attendanceReport)
                        <h3 style="border: 1px solid #ccc; padding-top:30px; padding-bottom: 30px; padding-left:20px; ">
                            Attendance Report Update Form</h3>

                        <div class="row" style="padding-left:20px; padding-right:20px;">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="employee_id" name="employee_id" value="{{ $attendanceReport->employee_id }}"
                                        readonly>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="date" name="date" value="{{ $attendanceReport->date }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Monthly Days Count <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="month_days" name="month_days" value="{{ $attendanceReport->month_days }}"
                                        required>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Weekend Count <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="month_weekends" name="month_weekends"
                                        value="{{ $attendanceReport->month_weekends }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Month Holidays <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="month_holidays" name="month_holidays"
                                        value="{{ $attendanceReport->month_holidays }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Work Days <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="work_days" name="work_days" value="{{ $attendanceReport->work_days }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Work Hours <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="work_hours" name="work_hours" value="{{ $attendanceReport->work_hours }}"
                                        required>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Days Worked <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="days_worked" name="days_worked" value="{{ $attendanceReport->days_worked }}"
                                        required>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Holiday Work Days <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="days_worked_holiday" name="days_worked_holiday"
                                        value="{{ $attendanceReport->days_worked_holiday }}" required>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Weekend Work Days <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="days_worked_weekend" name="days_worked_weekend"
                                        value="{{ $attendanceReport->days_worked_weekend }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Late Minutes <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="late_minutes" name="late_minutes"
                                        value="{{ $attendanceReport->late_minutes }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Absent Days <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="absent_days" name="absent_days" value="{{ $attendanceReport->absent_days }}"
                                        required>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">OT Minutes <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="ot_minutes" name="ot_minutes" value="{{ $attendanceReport->ot_minutes }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Half Days <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="half_days" name="half_days" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Annual Leaves <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="annual_leaves" name="annual_leaves"
                                        value="{{ $attendanceReport->annual_leaves }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Annual Leaves Taken <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="annual_leaves_taken" name="annual_leaves_taken"
                                        value="{{ $attendanceReport->annual_leaves_taken }}" required
                                        onchange="updateRemainingLeaves()">
                                </div>
                            </div>
                            {{-- onchange option --}}
                            <script>
                                function updateRemainingLeaves() {
                                    var annualLeaves = parseInt(document.getElementById('annual_leaves').value);
                                    var annualLeavesTaken = parseInt(document.getElementById('annual_leaves_taken').value);

                                    var remainingLeaves = annualLeaves - annualLeavesTaken;
                                    remainingLeaves = remainingLeaves < 0 ? 0 : remainingLeaves;
                                    document.getElementById('annual_leaves').value = remainingLeaves;
                                }
                            </script>

                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    @endforeach
                </form>





            </div>











        </div>
    </div>











    <!-- Include datepicker.js -->
    <script src="path/to/datepicker.js"></script>

    <script>
        // Initialize datepicker for the "Created Date" field
        $(document).ready(function() {
            // Initialize datepicker for "Created Date" field
            $('.datetimepicker').datepicker();
        });
    </script>





    <style>
        /* Custom styles for changing the active accordion button color */
        .accordion-button:not(.collapsed) {
            background-color: white;
            border-color: 2px solid white;
            /* Set the desired background color for the active button */
        }
    </style>
