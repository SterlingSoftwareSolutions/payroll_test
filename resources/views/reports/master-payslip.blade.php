@extends('layouts.master')
@section('content')
    <!-- Add this to the head section of your HTML file -->
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



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
        <div class="container-fluid my-4">
            <!-- Download Button -->
            <div class="d-flex justify-content-end mb-3">
                <button onclick="downloadPDF()" class="btn btn-primary">Download PDF</button>
            </div>

            <!-- Table Container -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="attendanceTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Basic Salary</th>
                            <th>BR Allowance</th>
                            <th>Fixed Allowance</th>
                            <th>Attendance Allowance</th>
                            <th>No Pay Leave Deduction</th>
                            <th>Late Deduction</th>
                            <th>Employee EPF</th>
                            <th>PAYE</th>
                            <th>Advance</th>
                            <th>Loan</th>
                            <th>Other Deductions</th>
                            {{-- <th>Holiday Payment</th> --}}
                            <th>Extra Days Payment</th>
                            <th>Incentive 1</th>
                            <th>Incentive 2</th>
                            <th>OT</th>
                            <th>Other Increments</th>
                            <th>Company EPF</th>
                            <th>ETF</th>
                            <th>Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payslips as $payslip)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payslip->date->format('Y-m-d') }}</td>
                                <td>{{ $payslip->employee->full_name }}</td>
                                <td>{{ $payslip->employee->department->department }}</td>
                                <td>{{ number_format($payslip->basic_salary, 2) }}</td>
                                <td>{{ number_format($payslip->br_allowance, 2) }}</td>
                                <td>{{ number_format($payslip->fixed_allowance, 2) }}</td>
                                <td>{{ number_format($payslip->attendance_allowance, 2) }}</td>
                                <td>{{ number_format($payslip->no_pay_leave_deduction, 2) }}</td>
                                <td>{{ number_format($payslip->late_deduction, 2) }}</td>
                                <td>{{ number_format($payslip->employee_epf, 2) }}</td>
                                <td>{{ number_format($payslip->paye, 2) }}</td>
                                <td>{{ number_format($payslip->advance, 2) }}</td>
                                <td>{{ number_format($payslip->loan, 2) }}</td>
                                <td>{{ number_format($payslip->other_deductions, 2) }}</td>
                                {{-- <td>{{ number_format($payslip->holiday_payment, 2) }}</td> --}}
                                <td>{{ number_format($payslip->extra_days_payment, 2) }}</td>
                                <td>{{ number_format($payslip->incentive1, 2) }}</td>
                                <td>{{ number_format($payslip->incentive2, 2) }}</td>
                                <td>{{ number_format($payslip->ot, 2) }}</td>
                                <td>{{ number_format($payslip->other_increments, 2) }}</td>
                                <td>{{ number_format($payslip->company_epf, 2) }}</td>
                                <td>{{ number_format($payslip->etf, 2) }}</td>
                                <td>{{ number_format($payslip->net_salary, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="22" class="text-center">No payslips available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!------------------------------------------------------------------------------------------------------>

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
            function downloadPDF() {
                var element = document.getElementById('attendanceTable');

                var options = {
                    margin: 10,
                    filename: 'Salary_Report.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a1',
                        orientation: 'landscape',
                        title: 'Salary Report'
                    }
                };

                html2pdf(element, options);
            }
        </script>
    @endsection
