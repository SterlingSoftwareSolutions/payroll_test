@extends('layouts.master')
@section('content')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->


    <!-- Include Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
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
                    <form method="post" action="/form/payslip/generate">
                        @csrf
                        <button type="submit" class="btn btn-secondary p-3 mr-2">Generate</button>
                    </form>
                    <form method="post" action="/form/payslip/approve_all">
                        @csrf
                        <button type="submit" class="btn btn-primary p-3">Approve All</button>
                    </form>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form method="GET">
                <div class="row filter-row">
                    <div class="col">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control form-control-1 input-sm from-year">
                            <label class="focus-label">Year</label>
                        </div>
                    </div>
                    <div class="col">
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
                    <div class="col-auto">
                        <button type="submit" class="btn btn-danger fa fa-search"></button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Month</th>
                                    <th>Net Salary</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($payslips))
                                    @foreach ($payslips as $payslip)
                                        <tr>
                                            <td><i class="fa {{ $payslip->approved_at ? 'fa-check text-success' : 'fa-times text-danger'}}"></i></td>
                                            <td>{{ $payslip->employee->employee_id }}</td>
                                            <td>{{ $payslip->employee->full_name }}</td>
                                            <td>{{ $payslip->date->format('F Y') }}</td>
                                            <td>{{ $payslip->net_salary }}</td>
                                            <!--need to calaculation-->
                                            <td class="text-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                        aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if($payslip->approved_at)
                                                            <a class="dropdown-item" href="/form/payslip/print/{{$payslip->id}}">
                                                                <i class="fa fa-print m-r-5"></i>
                                                                Print
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item userUpdate" data-toggle="modal"
                                                                data-id="{{ $payslip->id }}" data-target="#edit_payslip">
                                                                <i class="fa fa-pencil m-r-5"></i>
                                                                Edit
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <form action="/form/payslip/generate" method="post" class="p-3">
                                                @csrf
                                                <h3>No Payslips Found</h3>
                                                <button class="btn btn-danger">Generate payslips for previous month</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
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
                                    <input type="hidden" value="0" name="payslip_id" id="payslip_id">

                                    <div class="form-group col-md-12 p-3">
                                        <label for="employee_id">Selected Employee</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" readonly>
                                    </div>

                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group col-md-12">
                                            <label for="Earnings" style="color: green;">Earnings</label>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="basic_salary">Basic Salary</label>
                                            <input type="number" step="0.01" class="form-control" id="basic_salary" name="basic_salary">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="br_allowance">BR Allowance</label>
                                            <input type="number" step="0.01" class="form-control" id="br_allowance" name="br_allowance">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="fixed_allowance">Fixed Allowance</label>
                                            <input type="number" step="0.01" class="form-control" id="fixed_allowance" name="fixed_allowance">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="attendance_allowance">Attendance Allowance</label>
                                            <input type="number" step="0.01" class="form-control" id="attendance_allowance" name="attendance_allowance">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="holiday_payment">Holiday Payment</label>
                                            <input type="number" step="0.01" class="form-control" id="holiday_payment" name="holiday_payment">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="incentives">Incentives</label>
                                            <input type="number" step="0.01" class="form-control" id="incentives" name="incentives">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="ot">OT</label>
                                            <input type="number" step="0.01" class="form-control" id="ot" name="ot">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="other_increments">Other Increments</label>
                                            <input type="number" step="0.01" class="form-control" id="other_increments" name="other_increments">
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="form-group col-md-12">
                                            <label for="deductions" style="color: #ff0404;">Deductions</label>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="no_pay_leave_deduction">No Pay Leave Deduction</label>
                                            <input type="number" step="0.01" class="form-control" id="no_pay_leave_deduction" name="no_pay_leave_deduction">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="late_deduction">Late Deduction</label>
                                            <input type="number" step="0.01" class="form-control" id="late_deduction" name="late_deduction">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="employee_epf">EPF (Employee)</label>
                                            <input type="number" step="0.01" class="form-control" id="employee_epf" name="employee_epf">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="paye">P.A.Y.E.</label>
                                            <input type="number" step="0.01" class="form-control" id="paye" name="paye">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="stamp_duty">Stamp Duty</label>
                                            <input type="number" step="0.01" class="form-control" id="stamp_duty" name="stamp_duty">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="advance">Advance</label>
                                            <input type="number" step="0.01" class="form-control" id="advance" name="advance">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="loan">Loan</label>
                                            <input type="number" step="0.01" class="form-control" id="loan" name="loan">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="other_deductions">Other Deductions</label>
                                            <input type="number" step="0.01" class="form-control" id="other_deductions" name="other_deductions">
                                        </div>
                                    </div>

                                   <!-- Company Deductions -->
                                    <div class="col-md-12">
                                        <div class="form-group col-md-12">
                                            <label for="deductions" style="color: #ff0404;">Company Deductions</label>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="company_epf">EPF (Company)</label>
                                            <input type="number" step="0.01" class="form-control" id="company_epf" name="company_epf">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="etf">ETF</label>
                                            <input type="number" step="0.01" class="form-control" id="etf" name="etf">
                                        </div>
                                    </div>

                                    <!-- Bottom -->
                                    <div class="form-group col-md-12 d-flex justify-content-between"
                                        style="flex-direction: row; width: 400px;">
                                        <div
                                            style="background-color: red; display: flex; align-items: center; justify-content: center; color: white; width: 300px; margin-left: 15px; border-radius: 10px">
                                            <label >Total Pay: <span id="net_salary">0.00</span></label>
                                        </div>

                                        <div>
                                            <button type="submit" class="btn btn-success"
                                                style="background-color:transparent ;color: #05c46b ;border-color: #05c46b; width: 150px;">Approve</button>
                                            <button type="submit" name="print" class="btn btn-success"
                                                style="background-color: #05c46b; width: 150px;border-color: #05c46b;">Approve &
                                                Print</button>
                                        </div>
                                    </div>
                            </form>
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

                // Make an AJAX request to fetch the basic salary based on the employee id
                $.ajax({
                    type: 'GET',
                    url: '/form/payslip/show/' + employeeId, // Adjust the URL to your actual route
                    success: function(response) {
                        console.log(response.employee_employee_id)
                        var payslip = response[0];
                        console.log(payslip);
                        // Update the value of the basic salary input field in the modal
                        $('#payslip_id').val(payslip.id);
                        $('#employee_id').val(response.employee_employee_id);
                        $('#net_salary').text(payslip.net_salary);
                        $('#basic_salary').val(payslip.basic_salary);
                        $('#br_allowance').val(payslip.br_allowance);
                        $('#fixed_allowance').val(payslip.fixed_allowance);
                        $('#attendance_allowance').val(payslip.attendance_allowance);
                        $('#no_pay_leave_deduction').val(payslip.no_pay_leave_deduction);
                        $('#late_deduction').val(payslip.late_deduction);
                        $('#employee_epf').val(payslip.employee_epf);
                        $('#paye').val(payslip.paye);
                        $('#stamp_duty').val(payslip.stamp_duty);
                        $('#advance').val(payslip.advance);
                        $('#loan').val(payslip.loan);
                        $('#other_deductions').val(payslip.other_deductions);
                        $('#other_increments').val(payslip.other_increments);
                        $('#holiday_payment').val(payslip.holiday_payment);
                        $('#incentives').val(payslip.incentives);
                        $('#ot').val(payslip.ot);
                        $('#company_epf').val(payslip.company_epf);
                        $('#etf').val(payslip.etf);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
