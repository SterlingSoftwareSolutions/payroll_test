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
    {!! Toastr::message() !!}
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
            {{-- <form action="{{ route('all/employee/save') }}" method="POST" style="border:1px solid #ccc;"> --}}
            <form action="{{ route('all/employee/update', ['employeeId' => $employee->id]) }}" method="POST"
                onsubmit="return validateForm()" style="border:1px solid #ccc;">
                {{-- <form action="{{ route('all/employee/save') }}" method="POST" onsubmit="return validateForm()" style="border:1px solid #ccc;"> --}}
                @csrf
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
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="id" name="id" value="{{ $employee->employee_id }}" readonly>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Department Name <span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="d_name" name="d_name">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @if ($employee->d_name == $department->id) selected @endif>
                                        {{ $department->department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="f_name" name="f_name" value="{{ $employee->f_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label">Larst Name <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="l_name" name="l_name" value="{{ $employee->l_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label">Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="full_name" name="full_name" value="{{ $employee->full_name }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">DOB <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" tabindex="-1" aria-hidden="true" id="dob"
                                    name="dob" value="{{ $employee->dob }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="gender" name="gender">
                                <option value="male" @if ($employee->gender == 'male') selected @endif>Male</option>
                                <option value="female" @if ($employee->gender == 'female') selected @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get the email input field
                            var emailInput = document.getElementById('email');

                            // Add an input event listener
                            emailInput.addEventListener('input', function() {
                                // Get the entered email value
                                var emailValue = emailInput.value.trim();

                                // Define the regular expression for email validation
                                var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

                                // Check if the entered email matches the format
                                if (emailRegex.test(emailValue)) {
                                    // Valid email format
                                    emailInput.setCustomValidity('');
                                } else {
                                    // Invalid email format
                                    emailInput.setCustomValidity('Please enter a valid email address.');
                                }
                            });
                        });
                    </script>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="email" name="email" value="{{ $employee->email }}" required>
                            <div class="invalid-feedback">
                                <!-- This will display the custom validation message -->
                                Please enter a valid email address.
                            </div>
                        </div>
                    </div>
                    <script>
                        // Add this script to trigger validation feedback
                        document.getElementById('email').addEventListener('invalid', function() {
                            this.parentElement.classList.add('was-validated');
                        });
                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get the NIC input field
                            var nicInput = document.getElementById('nic');

                            // Add an input event listener
                            nicInput.addEventListener('input', function() {
                                // Get the entered NIC value
                                var nicValue = nicInput.value.trim();

                                // Define the regular expressions for the two formats
                                var format1Regex = /^[0-9]{9}[xXvV]$/;
                                var format2Regex = /^[0-9]{12}$/;

                                // Check if the entered NIC matches either format
                                if (format1Regex.test(nicValue) || format2Regex.test(nicValue)) {
                                    // Valid NIC format
                                    nicInput.setCustomValidity('');
                                } else {
                                    // Invalid NIC format
                                    nicInput.setCustomValidity(
                                        'NIC should be either 10 characters with 9 numbers and the last one being "x", "X", "v", or "V", or 12 characters with all numbers.'
                                    );
                                }
                            });
                        });
                    </script>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">NIC <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="nic" name="nic" value="{{ $employee->nic }}" required>
                            <div class="invalid-feedback">
                                <!-- This will display the custom validation message -->
                                Please enter a valid NIC.
                            </div>
                        </div>
                    </div>

                    <script>
                        // Add this script to trigger validation feedback
                        document.getElementById('nic').addEventListener('invalid', function() {
                            this.parentElement.classList.add('was-validated');
                        });
                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get the contact number input field
                            var contactNumberInput = document.getElementById('c_number');

                            // Add an input event listener
                            contactNumberInput.addEventListener('input', function() {
                                // Get the entered contact number value
                                var contactNumberValue = contactNumberInput.value.trim();

                                // Define the regular expression for contact number validation
                                var contactNumberRegex = /^0[0-9]{9}$/;

                                // Check if the entered contact number matches the format
                                if (contactNumberRegex.test(contactNumberValue)) {
                                    // Valid contact number format
                                    contactNumberInput.setCustomValidity('');
                                } else {
                                    // Invalid contact number format
                                    contactNumberInput.setCustomValidity(
                                        'Please enter a valid contact number starting with "0" and having 10 digits.');
                                }
                            });
                        });
                    </script>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Contact No <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="c_number" name="c_number" value="{{ $employee->c_number }}" required>
                            <div class="invalid-feedback">
                                <!-- This will display the custom validation message -->
                                Please enter a valid contact number starting with "0" and having 10 digits.
                            </div>
                        </div>
                    </div>

                    <script>
                        // Add this script to trigger validation feedback
                        document.getElementById('c_number').addEventListener('invalid', function() {
                            this.parentElement.classList.add('was-validated');
                        });
                    </script>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Job Title <span class="text-danger">*</span></label>
                            <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="j_title" name="j_title" value="{{ $employee->j_title }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-form-label">Joined Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control datetimepicker" type="text" id="joinedDate"
                                    name="joinedDate" value="{{ $employee->joinedDate }}" required>
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
                            <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                id="status" name="status">
                                <option value="active" @if ($employee->status == 'active') selected @endif>Active</option>
                                <option value="inactive" @if ($employee->status == 'inactive') selected @endif>Inactive
                                </option>
                                <option value="disable" @if ($employee->status == 'disable') selected @endif>Disable</option>
                            </select>
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
                            <textarea class="form-control" style="width: 100%; height: 100%" tabindex="-1" aria-hidden="true" id="description"
                                name="description">{{ $employee->description }}</textarea>
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
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne"
                                aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                <h3>Bank Details</h3>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="submit-section" style="">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Account Name <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" style="width: 100%;" tabindex="-1"
                                                        aria-hidden="true" id="account_name" name="account_name"
                                                        value="{{ $employee->account_name }}" required>
                                                </div>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var accountNumberInput = document.getElementById('account_number');

                                                    accountNumberInput.addEventListener('input', function() {
                                                        var value = accountNumberInput.value.trim();
                                                        var regex = /^\d{5,}$/;

                                                        if (regex.test(value)) {
                                                            accountNumberInput.setCustomValidity('');
                                                        } else {
                                                            accountNumberInput.setCustomValidity(
                                                                'Please enter a valid account number with at least 5 digits.');
                                                        }
                                                    });
                                                });
                                            </script>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Account Number <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" style="width: 100%;" tabindex="-1"
                                                        aria-hidden="true" id="account_number"
                                                        name="account_number"value="{{ $employee->account_number }}"
                                                        required>
                                                    <div class="invalid-feedback">
                                                        <!-- This will display the custom validation message -->
                                                        Please enter a valid account number with at least 5 digits.
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                document.getElementById('account_number').addEventListener('invalid', function() {
                                                    this.parentElement.classList.add('was-validated');
                                                });
                                            </script>


                                            <script>
                                                function validateAccountNumber() {
                                                    // Get the input value
                                                    var accountNumber = document.getElementById('account_number').value;

                                                    // Get the error message element
                                                    var errorElement = document.getElementById('accountNumberError');

                                                    // Check if the account number has at least 5 numbers
                                                    if (accountNumber.length < 5 || !/^\d+$/.test(accountNumber)) {
                                                        // Display the error message
                                                        errorElement.textContent = 'Account number must have at least 5 numbers.';
                                                    } else {
                                                        // Clear the error message if the validation passes
                                                        errorElement.textContent = '';
                                                    }
                                                }
                                            </script>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Bank Name <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" style="width: 100%;" tabindex="-1"
                                                        aria-hidden="true" id="bank_name" name="bank_name"
                                                        value="{{ $employee->bank_name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Branch <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" style="width: 100%;" tabindex="-1"
                                                        aria-hidden="true" id="branch" name="branch"
                                                        value="{{ $employee->branch }}" required>
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
                                data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo"
                                aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                <h3>Salary Details</h3>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="submit-section" style=""></div>
                                        <div class="row">

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Get the Basic Salary input field
                                                    var basicSalaryInput = document.getElementById('basic_Salary');

                                                    // Add an input event listener
                                                    basicSalaryInput.addEventListener('input', function() {
                                                        // Get the entered Basic Salary value
                                                        var basicSalaryValue = basicSalaryInput.value.trim();

                                                        // Define the regular expression for numbers-only validation
                                                        var numbersRegex = /^[0-9]+$/;

                                                        // Check if the entered Basic Salary contains only numbers
                                                        if (numbersRegex.test(basicSalaryValue)) {
                                                            // Valid format (only numbers)
                                                            basicSalaryInput.setCustomValidity('');
                                                        } else {
                                                            // Invalid format (contains non-numeric characters)
                                                            basicSalaryInput.setCustomValidity(
                                                                'Please enter a valid basic salary with only numeric characters.');
                                                        }
                                                    });
                                                });
                                            </script>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Basic Salary <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" style="width: 100%;" tabindex="-1"
                                                        aria-hidden="true" id="basic_Salary" name="basic_Salary"
                                                        type="number" value="{{ $employee->basic_Salary }}" required>
                                                    <div class="invalid-feedback">
                                                        <!-- This will display the custom validation message -->
                                                        Please enter a valid basic salary with only numeric characters.
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // Add this script to trigger validation feedback
                                                document.getElementById('basic_Salary').addEventListener('invalid', function() {
                                                    this.parentElement.classList.add('was-validated');
                                                });
                                            </script>
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
                                            </div>
                                            @foreach ($salary as $index => $record)
                                                <div class="increment-container">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label for="type{{ $index }}"
                                                                class="col-form-label">Type :</label>
                                                            <select class="form-control" style="width: 100%;"
                                                                id="type{{ $index }}" name="type[]">
                                                                <option value="" disabled>Select a type</option>
                                                                <option value="increments"
                                                                    @if (old('type.' . $index, $record->type) == 'increments') selected @endif>
                                                                    Increments</option>
                                                                <option value="deductions"
                                                                    @if (old('type.' . $index, $record->type) == 'deductions') selected @endif>
                                                                    Deductions</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="incrementName{{ $index }}"
                                                                class="col-form-label">Name :</label>
                                                            <input type="text" class="form-control"
                                                                id="incrementName{{ $index }}"
                                                                name="increment_name[]"
                                                                value="{{ old('increment_name.' . $index, $record->increment_name) }}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label for="incrementAmount{{ $index }}"
                                                                class="col-form-label">Amount :</label>
                                                            <input type="text" class="form-control"
                                                                id="incrementAmount{{ $index }}"
                                                                name="increment_amount[]"
                                                                value="{{ old('increment_amount.' . $index, !empty($record->increment_amount) ? number_format($record->increment_amount, 2, '.', '') : '') }}">
                                                            <div class="invalid-feedback">
                                                                Please enter a valid amount with only numeric characters.
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="deductionDate{{ $index }}"
                                                                class="col-form-label">Date :<span
                                                                    style="color: darkgray"> (dd-mm-yyyy)</span></label>
                                                            <div class="cal-icon">
                                                                <input class="form-control datetimepicker" type="text"
                                                                    id="deductionDate{{ $index }}"
                                                                    name="deduction_dates[]"
                                                                    value="{{ old('deduction_dates.' . $index, !empty($record->date) ? $record->date : '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        {{-- <button type="button" class="btn btn" onclick="deleteRow('${incrementId}')" style="color: black; background: transparent;">
                                                        <h4 style="color: #FB0101;"><span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i></span> Remove Field</h4>
                                                    </button> --}}
                                                    </div>
                                                </div>
                                            @endforeach


                                            <script>
                                                function validateForm() {
                                                    var typeSelect = document.getElementById("type");
                                                    var incrementNameInput = document.getElementById("incrementName1");
                                                    var incrementAmountInput = document.getElementById("incrementAmount1");
                                                    var dateInput = document.getElementById("deductionDate1");

                                                    if (typeSelect.value !== "") {
                                                        // If a type is selected, check other fields
                                                        if (incrementNameInput.value === "") {
                                                            alert("Please enter a name.");
                                                            return false;
                                                        }

                                                        if (incrementAmountInput.value === "") {
                                                            alert("Please enter an amount.");
                                                            return false;
                                                        }

                                                        if (dateInput.value === "") {
                                                            alert("Please enter a date.");
                                                            return false;
                                                        }
                                                    }

                                                    return true;
                                                }
                                            </script>

                                        </div>
                                    </div>
                                </div>
                                <!-- Updated script with styled delete button -->
                                <script>
                                    let incrementCount = 2; // Start count from 2 for default increment form
                                    let deductionCount = 2; // Start count from 2 for default deduction form

                                    function addIncrement() {
                                        const container = document.querySelector('.increment-container');
                                        const incrementId = `incrementRow${incrementCount}`;

                                        // Create a new div element
                                        const newIncrementDiv = document.createElement('div');
                                        newIncrementDiv.id = incrementId;

                                        // Append the HTML content to the new div element
                                        newIncrementDiv.innerHTML = `
                                            <div class="row"><br> <hr style="border-color: #979797;">
                                                <div class="col-sm-6">
                                                    <label for="type${incrementCount}" class="col-form-label">Type :</label>
                                                    <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="type${incrementCount}" name="type[]" required>
                                                        <option value="" disabled selected>Select a type</option> <!-- Placeholder option -->
                                                        <option value="increments">Increments</option>
                                                        <option value="deductions">Deductions</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="incrementName${incrementCount}" class="col-form-label">Name :</label>
                                                    <input type="text" class="form-control" name="increment_name[]" required>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 mb-3">
                                                        <label for="incrementAmount${incrementCount}" class="col-form-label">Amount :</label>
                                                        <input style="width: 102%;" type="number" class="form-control" name="increment_amount[]" required>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label for="deductionDate${incrementCount}" class="col-form-label">Date :<span style="color: darkgray"> (dd-mm-yyyy)</span></label>
                                                        <div class="cal-icon ml-2">
                                                            <input style="width: 105%;" class="form-control datetimepicker incrementDate" type="text" id="deductionDate${incrementCount}" name="deduction_dates[]" required>
                                                            <div class="invalid-feedback">
                                                                <!-- This will display the custom validation message -->
                                                                Please enter a valid date in the format dd-mm-yyyy.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn" onclick="deleteRow('${incrementId}')" style="color: black; background: transparent;">
                                                        <h4 style="color: #FB0101;"><span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i></span> Remove Field</h4>
                                                    </button>
                                                </div>
                                            </div>
                                            `;

                                        // Append the new div element to the container
                                        container.appendChild(newIncrementDiv);

                                        // Initialize date picker for the newly added field
                                        initializeDatePicker(`#deductionDate${incrementCount}`);

                                        // Increment counters
                                        incrementCount++;
                                    }

                                    function addDeduction() {
                                        const container = document.querySelector('.deduction-container');
                                        const deductionId = `deductionRow${deductionCount}`;

                                        // Create a new div element
                                        const newDeductionDiv = document.createElement('div');
                                        newDeductionDiv.id = deductionId;

                                        // Append the HTML content to the new div element
                                        newDeductionDiv.innerHTML = `
                                                <span><i class="fa-solid fa-circle-minus" style="color: #fa0505;"></i></span><div id="${deductionId}">
                                                    <div class="row mb-3">
                                                        <label for="deductionName${deductionCount}" class=" col-form-label">Name :</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="deductionName${deductionCount}" name="deduction_name[]">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <label for="deductionAmount${deductionCount}" class="col-form-label">Amount :</label>
                                                            <input type="number" class="form-control" id="deductionAmount${deductionCount}" name="deduction_amount[]">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label for="deductionDate${deductionCount}" class="col-form-label">Date :<span style="color: darkgray"> (dd-mm-yyyy)</span></label>
                                                            <div class="cal-icon">
                                                                <input class="form-control datetimepicker" type="text" id="deductionDate${deductionCount}" name="deduction_dates[]" required>
                                                                <div class="invalid-feedback">
                                                                    <!-- This will display the custom validation message -->
                                                                    Please enter a valid date in the format dd-mm-yyyy.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;

                                        // Append the new div element to the container
                                        container.appendChild(newDeductionDiv);

                                        // Initialize date picker for the newly added field
                                        initializeDatePicker(`#deductionDate${deductionCount}`);

                                        // Increment deductionCount
                                        deductionCount++;
                                    }

                                    function deleteRow(rowId) {
                                        const row = document.getElementById(rowId);
                                        row.parentNode.removeChild(row);
                                    }

                                    function initializeDatePicker(elementId) {
                                        $(elementId).datetimepicker({
                                            format: 'DD-MM-YYYY', // You can add more options based on your library's documentation
                                        });
                                    }
                                </script>

                                <!-- Initialize Datepicker -->
                                <script>
                                    $(document).ready(function() {
                                        $('#date').datepicker({
                                            format: 'yyyy-mm-dd',
                                            autoclose: true
                                        });
                                    });
                                </script>
                                <!-- Include Bootstrap JS (optional) -->
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
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
