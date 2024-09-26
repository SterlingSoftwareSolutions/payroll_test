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
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Report</a></li>
                        <li class="breadcrumb-item active">Payslip Report</li>
                    </ul>
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
                            @foreach($employees as $employee)
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
                            <th>Position</th>
                            <th>Net Salary</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->j_title }}</td>
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
                                            data-id="{{ $employee->id }}" data-target="#edit_payslip"><i
                                                class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                            data-target="#delete_holiday"><i class="fa fa-print m-r-5"></i> Print</a>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
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
                                    <select class="form-control" id="employeeSelect" name="employeeSelect">
                                        <!-- Add options for employees -->
                                        <option value="1">Employee 1</option>
                                        <option value="2">Employee 2</option>
                                        <!-- Add more options as needed -->
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="Earnings" style="color: #ff0404;">Earnings</label>
    
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="basicSalary">Basic Salary</label>
                                    <input type="text" class="form-control" id="earnings" name="earnings">
    
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="incentive">Incentive</label>
                                    <input type="text" class="form-control" id="deductions" name="deductions">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="holidayPayment">Holiday Payment</label>
                                    <input type="text" class="form-control" id="basicSalary" name="basicSalary">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="ot">OT</label>
                                    <input type="text" class="form-control" id="epf" name="epf">
                                </div>
                            </div>
    
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group col-md-12">
                                    <label for="label1">Net Salary</label>
                                    <input type="text" class="form-control" id="input1" name="input1">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="deductions" style="color: #ff0404;">Deductions</label>
    
    
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="label1">EPF</label>
                                    <input type="text" class="form-control" id="input1" name="input1">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="label2">ETF</label>
                                    <input type="text" class="form-control" id="input2" name="input2">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="label1">Late Deduction</label>
                                    <input type="text" class="form-control" id="input1" name="input1">
                                </div>
    
                                <div class="form-group col-md-12">
                                    <label for="label2">Leave</label>
                                    <input type="text" class="form-control" id="input2" name="input2">
                                </div>
                    </form>
                </div>
                <div class="form-group col-md-12 d-flex justify-content-center" style="flex-direction: row; width: 400px;">
                    <button type="button" class="btn btn-success"
                        style="background-color: #ff0404; width: 150px;">Approve</button>
                    <button type="button" class="btn btn-success" style="background-color: #05c46b; width: 150px;">Approve &
                        Print</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit payslip Modal -->


</div>
</div>



<!------------------------------------------------------------------------------------------------->


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
        

        $(document).ready(function () {
            $(".action-icon").click(function (e) {
                // Prevent the default dropdown behavior
                e.preventDefault();

                // Toggle the visibility of the dropdown menu
                $(this).siblings(".dropdown-menu").toggle();
            });
        });  
                    
</script>