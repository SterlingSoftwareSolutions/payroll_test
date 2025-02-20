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
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Basic Salary</th>
                            <th>BR Allowance</th>
                            <th>Total EPF</th>
                            <th>Increments</th>
                            <th>Deductions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr class="{{ $employee->status == 'inactive' ? 'bg-danger' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->department->department }}</td>
                                <td>{{ $employee->status }}</td>
                                <td>{{ $employee->basic_Salary - 3500 }}</td>
                                <td>3500</td>
                                <td>{{ $employee->basic_Salary }}</td>
                
                                <!-- Increment and Deduction Columns -->
                                <td>
                                    <!-- Using Flexbox to display increments -->
                                    <div class="d-flex flex-column">
                                        @foreach ($employee->salaryDetails as $salaryDetail)
                                            @if($salaryDetail->type == 'increments')
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>{{ $salaryDetail->increment_name }}</span>
                                                    <span>{{ $salaryDetail->increment_amount }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                
                                <td>
                                    <!-- Using Flexbox to display deductions -->
                                    <div class="d-flex flex-column">
                                        @foreach ($employee->salaryDetails as $salaryDetail)
                                            @if($salaryDetail->type == 'deductions')
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>{{ $salaryDetail->increment_name }}</span>
                                                    <span>{{ $salaryDetail->increment_amount }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No employees available</td>
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
                        format: 'a3',
                        orientation: 'landscape',
                        title: 'Salary Report'
                    }
                };

                html2pdf(element, options);
            }
        </script>
    @endsection
