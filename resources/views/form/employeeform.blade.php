<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS and Popper.js (Order is important) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Include Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.21.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Include datepicker.css -->
<link rel="stylesheet" href="path/to/datepicker.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<!-- Include Bootstrap Datepicker CSS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

.accordion-button:focus {
border-color: red !important;
}
@extends('layouts.master')
@section('content')

<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-lists-center">
                <div class="col">
                    <h3 class="page-title">Add Employee</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Employees</a></li>
                        <li class="breadcrumb-item active">Add Employee</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <!-- resources/views/form.blade.php -->
    <div class="modal-body">
        <form action="{{ route('all/employee/save') }}" method="POST" style="border:1px solid #ccc;">
            <h3 style="border: 1px solid #ccc; padding-top:30px; padding-bottom: 30px; padding-left:20px; ">Personal
                Details</h3>
            @csrf
            <!-- Your form fields go here -->
            <div class="submit-section" style="">
            </div>
            <div class="row" style="padding-left:20px; padding-right:20px;">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="id"
                            name="id">
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Department Name <span class="text-danger">*</span></label>
                        <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="d_name"
                            name="d_name">
                            <option value="it">IT</option>
                            <option value="local">Local</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="f_name"
                            name="f_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Larst Name <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="l_name"
                            name="l_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Full <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="full_name"
                            name="full_name">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">DOB <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input class="form-control datetimepicker" tabindex="-1" aria-hidden="true" id="dob" name="dob">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="gender"
                            name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="email"
                            name="email">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">NIC <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="nic"
                            name="nic">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Contact No <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="c_number"
                            name="c_number">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Job Title <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="j_title"
                            name="j_title">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Joined Date <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input class="form-control datetimepicker" type="text" id="joinedDate" name="joinedDate">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-form-label">Created Date <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input class="form-control" type="text" id="createdDate" name="createdDate" readonly>
                        </div>
                    </div>
                </div>

                <script>
                    // Get the current local date in the "DD/MM/YYYY" format
                    var currentDate = new Date();
                    var formattedDate = currentDate.getDate().toString().padStart(2, '0') + '/' +
                        (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                        currentDate.getFullYear();

                    // Set the value of the input field to the current date
                    document.getElementById("createdDate").value = formattedDate;
                </script>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label">Status <span class="text-danger">*</span></label>
                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="status"
                            name="status">
                    </div>
                </div>
                <style>
                    .text-box {
                        border: 1px solid #ccc;
                        padding: 5px;
                        margin: 10px 0;
                        width: 200px;
                        white-space: nowrap;
                        overflow: hidden;
                    }
                </style>

            </div>
            <!-- ... existing form fields ... -->
            <div class="row" style="padding-left:20px; padding-right:20px;">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label">Description</label>
                        <textarea class="form-control" style="width: 100%; height:100%" tabindex="-1" aria-hidden="true"
                            id="description" name="description"></textarea>
                    </div>
                </div>
            </div>

            <br><br><br>
            <!-- new accordian  -->
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                        <button class="accordion-button"
                            style="padding-top:20px; padding-bottom: 20px; padding-left:20px; " type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                            <h3>Bank Details</h3>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                        aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="submit-section" style="">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Account Name <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="account_name" name="account_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Account Number <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="account_number" name="account_number">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Bank Name <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="bank_name" name="bank_name">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Branch <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="branch" name="branch">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed"
                            style="padding-top:20px; padding-bottom: 20px; padding-left:20px; " type="button"
                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseTwo">
                            <h3>Salary Details</h3>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
                        aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="submit-section" style=""></div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Basic Salary <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="basic_Salary" name="basic_Salary">
                                            </div>
                                        </div>

                                            <div class="increment-container">
                                                <!-- Default increment form -->
                                                <button type="button" class="btn btn" onclick="addIncrement()"
                                                    style="color: black;">
                                                    <h4 style="color: #05C46B;">
                                                        <span><i class="fa-solid fa-circle-plus"
                                                                style="color: #05a31f;"></i></span>
                                                        Add more..
                                                    </h4>
                                                </button>
                                                <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="type" class="col-sm-2 col-form-label">Type :</label>
                                                        <select class="form-control" style="width: 100%;" tabindex="-1"
                                                            aria-hidden="true" id="type" name="type">
                                                            <option value="increments">Increments</option>
                                                            <option value="deductions">Deductions</option>
                                                        </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementName1" class="col-sm-2 col-form-label">Name
                                                        :</label>
                                                        <input type="text" class="form-control" id="incrementName1"
                                                            name="increment_name[]">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementAmount1" class="col-sm-2 col-form-label">Amount
                                                        :</label>
                                                        <input type="text" class="form-control" id="incrementAmount1"
                                                            name="increment_amount[]">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="date" class="col-sm-2 col-form-label">Date :</label>
                                                        <div class="cal-icon">
                                                            <input class="form-control datetimepicker" type="text"
                                                                id="date" name="date">
                                                        </div>
                                                </div>
                                            </div>
                   
                                      
                         

                                            </div>
                                    </div>
                                    <!-- Include Bootstrap JS (optional) -->
                                    <script
                                        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                                    <script
                                        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                                    <script
                                        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                                    <!-- Updated script with styled delete button -->
                                    <script>
                                        let incrementCount = 2; // Start count from 2 for default form
                                        let deductionCount = 2; // Start count from 2 for default form

                                        function addIncrement() {
                                            const container = document.querySelector('.increment-container');
                                            const incrementId = `incrementRow${incrementCount}`;
                                            container.innerHTML += `
                                            <div id="${incrementId}">
                                            <div class="row"><br> <hr style="border-color: #979797;">
                                                <div class="col-sm-6">
                                                    <label for="type" class="col-sm-2 col-form-label">Type :</label>
                                                    <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="type" name="type">
                                                        <option value="increments">Increments</option>
                                                        <option value="deductions">Deductions</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementName${incrementCount}" class="col-sm-2 col-form-label">Name :</label>
                                                        <input type="text" class="form-control" id="incrementName${incrementCount}" name="increment_name[]">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementAmount${incrementCount}" class="col-sm-2 col-form-label">Amount :</label>
                                                        <input type="text" class="form-control" id="incrementAmount${incrementCount}" name="increment_amount[]">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementDate${incrementCount}" class="col-sm-2 col-form-label">Date :</label>
                                                        <div class="cal-icon">
                                                            <input class="form-control datetimepicker" type="text" id="incrementDate${incrementCount}" name="increment_dates[]">
                                                        </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn" onclick="deleteRow('${incrementId}')" style="color: black; background: transparent;">
                                                        <h4 style="color: #FB0101;"><span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i></span> Remove Field</h4>
                                                    </button>
                                                </div>
                                            </div>
                                                `;
                                            initializeDatePicker(`incrementDate${incrementCount}`);
                                            incrementCount++;
                                        }
                                        function addDeduction() {
                                            const container = document.querySelector('.deduction-container');
                                            const deductionId = `deductionRow${deductionCount}`;
                                            container.innerHTML += `
                                            <span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i>
                                                                    </span><div id="${deductionId}">
                                                        <div class="row mb-3">
                                                            <label for="deductionName${deductionCount}" class="col-sm-2 col-form-label">Name :</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="deductionName${deductionCount}" name="deduction_name[]">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="deductionAmount${deductionCount}" class="col-sm-2 col-form-label">Amount :</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="deductionAmount${deductionCount}" name="deduction_amount[]">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="deductionDate${deductionCount}" class="col-sm-2 col-form-label">Date :</label>
                                                            <div class="col-sm-10">
                                                                <div class="cal-icon">
                                                                    <input class="form-control datetimepicker" type="text" id="deductionDate${deductionCount}" name="deduction_dates[]">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-3">
                                                                <button type="button" class="btn btn" onclick="deleteRow('${deductionId}')" style="color: black; background: transparent;">
                                                                    <h4 style="color: #FB0101;"><span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i>
                                                                    </span> Remove Field</h4>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                            initializeDatePicker(`deductionDate${deductionCount}`);
                                            deductionCount++;
                                        }
                                        function deleteRow(rowId) {
                                            const row = document.getElementById(rowId);
                                            row.parentNode.removeChild(row);
                                        }
                                    </script>
                                    <!-- Initialize Datepicker -->
                                    <script>
                                        $(document).ready(function () {
                                            $('#date').datepicker({
                                                format: 'yyyy-mm-dd',
                                                autoclose: true
                                            });
                                        });
                                    </script>
                                    <!-- Include Bootstrap JS (optional) -->
                                    <script
                                        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                                    <script
                                        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
                                    <script
                                        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                                    <!-- Include datepicker.js -->
                                    <script src="path/to/datepicker.js"></script>

                                    <script>
                                        // Initialize datepicker for the "Created Date" field
                                        $(document).ready(function () {
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
            <div class="submit-section">
                <button class="btn btn-primary submit-btn">Submit</button>
            </div>
        </form>
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
</div>