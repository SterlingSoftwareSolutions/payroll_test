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

        {{-- <form method="POST" action="{{ route('reports.update.attendancereport', $attendanceReport->id) }}">
            @csrf
            @method('PATCH')
            <!-- Your form fields go here -->
            <button type="submit">Update</button>
        </form>
        --}}


        <div class="modal-body">

            {{-- <form action="{{ route('reports.update.attendancereport',['employeeId' => $attendanceReport->id]) }}"
                method="POST" onsubmit="return validateForm()" style="border:1px solid #ccc;">
                @csrf
                @method('PATCH') --}}
                <form method="post" action="{{ route('reports.update.attendancereport', ['employeeId' => $attendanceReport->id]) }}">
                    @csrf
                    <h3 style="border: 1px solid #ccc; padding-top:30px; padding-bottom: 30px; padding-left:20px; ">
                        Attendance Report Update Form</h3>
                    {{-- @csrf --}}
                    <div class="submit-section" style="">
                    </div>
                    <div class="row" style="padding-left:20px; padding-right:20px;">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="id" name="id" value="{{ $attendanceReport->employee_id }}" readonly>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Date <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="work_id" name="work_id" value="{{ $attendanceReport->date }}" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Monthly Days Count <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="etf_no" name="etf_no" value="{{ $attendanceReport->month_days }}" required>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Weekend Count <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="f_name" name="f_name" value="{{ $attendanceReport->month_weekends }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Month Holidays <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="l_name" name="l_name" value="{{ $attendanceReport->month_weekends }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Work Days <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="full_name" name="full_name" value="{{ $attendanceReport->work_days }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Work Hours <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="etf_no" name="etf_no" value="{{ $attendanceReport->work_hours }}" required>
                                {{-- <input type="text" name="work_hours" value="{{ $attendanceReport->work_hours }}"
                                    placeholder="Work Hours"> --}}
                                <!-- Include other fields as needed -->

                            </div>
                        </div>



                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Days Worked <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="c_number" name="c_number" value="{{ $attendanceReport->days_worked }}" required>
                                <div class="invalid-feedback">
                                    <!-- This will display the custom validation message -->
                                    Please enter a valid contact number starting with "0" and having 10 digits.
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Holiday Work Days <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="id" name="id" value="{{ $attendanceReport->days_worked_holiday }}" required>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Weekend Work Days <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="work_id" name="work_id" value="{{ $attendanceReport->days_worked_weekend }}"
                                    required>
                            </div>
                        </div>



                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Late Minutes <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="f_name" name="f_name" value="{{ $attendanceReport->late_minutes }}" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Absent Days <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="etf_no" name="etf_no" value="{{ $attendanceReport->absent_days }}" required>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">OT Minutes <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="l_name" name="l_name" value="{{ $attendanceReport->ot_minutes }}" required>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Annual Leaves <span class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="full_name" name="full_name" value="{{ $attendanceReport->annual_leaves }}"
                                    required>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Annual Leaved Taken <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                    id="full_name" name="full_name" value="{{ $attendanceReport->annual_leaves_taken }}"
                                    required>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>















                        <!-- Include Bootstrap JS (optional) -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js">
                        </script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                        <!-- Include datepicker.js -->
                        <script src="path/to/datepicker.js"></script>

                        <script>
                            // Initialize datepicker for the "Created Date" field
                                        $(document).ready(function() {
                                            // Initialize datepicker for "Created Date" field
                                            $('.datetimepicker').datepicker();
                                        });
                        </script>
                    </div>
        </div>
        <!-- Add other form fields or buttons here as needed -->
    </div>
</div>
</div>
</div>

</div>
<!-- end according -->
<style>
    /* Custom styles for changing the active accordion button color */
    .accordion-button:not(.collapsed) {
        background-color: white;
        border-color: 2px solid white;
        /* Set the desired background color for the active button */
    }
</style>