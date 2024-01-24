@extends('layouts.master')
@section('content')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
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
                    <div class="col">
                        <h3 class="page-title">Payslip Approve</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Report</a></li>
                            <li class="breadcrumb-item active">Payslip Report</li>
                        </ul>
                    </div>
                    <div class="col text-right col-md-3" style="height: 50px;">
                        <!-- Add the "Approve Payslip" button here -->
                        <div data-toggle="modal" data-target="#approve_payslip" class="btn btn-primary"
                            style="height: 100%;">Approve Payslip
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Page Header -->

            <!-- Search Filter -->
            <form method="GET">
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {{-- <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>Net Salary</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>Position</td>
                                <td>Net Salary</td>

                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit_payslip" href="#" data-toggle="modal"
                                                data-target="#edit_payslip"><i class="fa fa-pencil m-r-5"></i> Edit
                                                Payslip</a>
                                            <a class="dropdown-item print_payslip" href="#" data-toggle="modal"
                                                data-target="#print_payslip"><i class="fa fa-print m-r-5"></i> Print
                                                Payslip</a>
                                        </div>
                                    </div>
                                </td>


                </div>

                </td>
                </tr>
                @endforeach
                </tbody>
                </table> --}}

                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Month</th>
                                    <th>Net Salary</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->full_name }}</td>
                                        {{-- <td>{{ $employee->j_title }}</td> --}}
                                        <td>{{ date('F Y') }}</td>
                                        <td>Net Salary</td>
                                        <!--need to calaculation-->
                                        {{-- <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit_payslip" href="#" data-toggle="modal"
                                            data-target="#edit_payslip" data-employee-id="{{ $employee->id }}"><i
                                                class="fa fa-pencil m-r-5"></i> Edit Payslip</a>
                                        <a class="dropdown-item print_payslip" href="#" data-toggle="modal"
                                            data-target="#print_payslip" data-employee-id="{{ $employee->id }}"><i
                                                class="fa fa-print m-r-5"></i> Print Payslip</a>
                                    </div>
                                </div>
                            </td> --}}
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item userUpdate" data-toggle="modal"
                                                        data-id="{{ $employee->employee_id }}" data-target="#edit_payslip">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>

                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#delete_holiday"><i class="fa fa-print m-r-5"></i>
                                                        Print</a>
                                                    {{-- <a class="dropdown-item" href="#" data-toggle="modal"
                                            data-target="#delete_holiday"><i class="fa fa-trash-o m-r-5"></i> Print</a>
                                        --}}

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!----------------------------------------------------------------------------------------->
            <!-- Edit payslip Modal -->
            <div class="modal custom-modal fade" id="edit_payslip" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Payslip Approve</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="{{ route('form/payslip/update') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group col-md-12">
                                            <label for="employeeSelect">Select Employee</label>
                                            <input type="text" class="form-control" id="employeeIdInput"
                                                name="employee_id" readonly>

                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="Earnings" style="color: green;">Earnings</label>

                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="basicSalary">Basic Salary</label>
                                            <input type="text" class="form-control" id="basicSalary"
                                                name="basic_salary">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="brAllowance">BR Allowance</label>
                                            <input type="" class="form-control" id="brAllowance">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="incentive1">Incentive 01</label>
                                            <input type="" class="form-control" id="incentive1">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="incentive2">Incentive 02</label>
                                            <input type="" class="form-control" id="incentive2">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">Attendance Allowance</label>
                                            <input type="" class="form-control" id="">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="">Holiday Payments</label>
                                            <input type="" class="form-control" id="">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="ot">OT</label>
                                            <input type="text" class="form-control" id="epf" name="epf">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="increment_others">Other</label>
                                            <input type="" class="form-control" id="increment_others">
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6"style="margin-top: 100px;">
                                        <div class="form-group col-md-12">
                                            <label for="deductions" style="color: #ff0404;">Deductions</label>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">EPF<span> (Employee)</span> </label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">Late Deduction</label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">Leave</label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">Earning for P.A/Y.E</label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">Stamp Duty</label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="Advanced">Advanced</label>
                                            <input type="" class="form-control" id="Advanced">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="deduction_others">Other</label>
                                            <input type="" class="form-control" id="deduction_others">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="">EPF<span> (Company)</span> </label>
                                            <input type="" class="form-control" id="">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">ETF</label>
                                            <input type="" class="form-control" id="">
                                        </div>

                            </form>
                        </div>
                        <div class="form-group col-md-12 d-flex justify-content-between"
                            style="flex-direction: row; width: 400px;">
                            <div
                                style="background-color: red; display: flex; align-items: center; justify-content: center; height: 50px; color: white; width: 300px; margin-left: 15px; border-radius: 10px">
                                <label for="">Total pay :</label>
                                <label for="">68,850.66</label>
                            </div>

                            <div>
                                <button type="button" class="btn btn-success"
                                    style="background-color:transparent ;color: #05c46b ;border-color: #05c46b; width: 150px;">Approve</button>
                                <button type="button" class="btn btn-success"
                                    style="background-color: #05c46b; width: 150px;border-color: #05c46b;">Approve &
                                    Print</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /Edit payslip Modal -->

        </div>
    </div>



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


        $(document).ready(function() {
            $(".action-icon").click(function(e) {
                // Prevent the default dropdown behavior
                e.preventDefault();

                // Toggle the visibility of the dropdown menu
                $(this).siblings(".dropdown-menu").toggle();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Triggered when the modal is about to be shown
            $('#edit_payslip').on('show.bs.modal', function(event) {
                var link = $(event.relatedTarget); // Link that triggered the modal
                var employeeId = link.data('id'); // Extract employee id from data-id attribute

                // Update the value of the employee id input field in the modal
                $('#employeeIdInput').val(employeeId);

                // Make an AJAX request to fetch the basic salary based on the employee id
                $.ajax({
                    type: 'GET',
                    url: '/getDetails/' + employeeId, // Adjust the URL to your actual route
                    success: function(response) {
                        // Update the value of the basic salary input field in the modal
                        $('#basicSalary').val(response.basic_salary);
                        $('#brAllowance').val(response.brAllowance);
                        $('#incentive1').val(response.incentive1);
                        $('#incentive2').val(response.incentive2);
                        $('#increment_others').val(response.increment_others);
                        $('#deduction_others').val(response.deduction_others);
                        $('#Advanced').val(response.Advanced);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
