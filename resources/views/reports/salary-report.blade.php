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
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                {{-- <div class="col border border-info">
                    <h3 class="page-title">Salary Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Reports</a></li>
                        <li class="breadcrumb-item active">Salary Report</li>
                    </ul>

                    <button type="button" class="btn btn-success"
                        style="background-color: #ff0404; width: 150px;">Print</button>
                </div> --}}
                <div class="col d-flex justify-content-between">
                    <div class="d-flex justify-content-between p-3">
                        <div>
                            <h3 class="page-title">Salary Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Reports</a></li>
                                <li class="breadcrumb-item active">Salary Report</li>
                            </ul>
                        </div>

                        {{-- <button type="button" class="btn btn-success"
                            style="background-color: #05c46b; width: 200px;">Print Salary Report</button> --}}

                        <button onclick="downloadPDF()" class="btn btn-primary">Download PDF</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Search Filter -->
        <form method="GET">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="select form-control floating" name="department">
                            <option value=""> -- Select Department-- </option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department')==$department->id)>{{
                                $department->department }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-sm-6 col-md-3">
                    <div class="form-group ">
                        {{-- <label class="focus-label">Year</label> --}}
                        <input type="text" class="form-control form-control-1 input-sm from-year" placeholder="Year">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group ">
                        <select class="select form-control floating" id="monthDropdown" name="month">
                            <option value="" selected disabled>--Select Month--</option>
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
                        {{-- <label class="focus-label">Month</label> --}}
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 ">
                    <button type="submit" class="btn btn-danger btn-block" style="height: 30px;">
                        <img src="{{ URL::to('assets/img/search.png') }}" alt="">
                        &nbsp;&nbsp;Search
                    </button>
                </div>
                <div>
                </div>


            </div>
        </form>



        <table class="table table-striped custom-table datatable" id="attendanceTable">
            {{-- <table class="table table-striped custom-table datatable"> --}}
                <thead>

                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Account No</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->bank_name }}</td>
                        <td>{{ $employee->branch }}</td>
                        <td>{{ $employee->account_number }}</td>
                    
                        <td>
                            @foreach ($payslips as $payslip)
                            @if($payslip->employee->id === $employee->id)
                            {{ number_format($payslip->net_salary()) }}
                            @break
                            @endif
                            @endforeach
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </table>
    </div>



    {{-- <table class="table table-striped custom-table datatable">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Bank Name</th>
                <th>Branch Name</th>
                <th>Account No</th>
                <th>Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $employee->bank_name }}</td>
                <td>{{ $employee->branch }}</td>
                <td>{{ $employee->account_number }}</td>
                <td>Net Salary</td>
                <!--need to calaculation-->


            </tr>
            @endforeach
        </tbody>
    </table> --}}








    <!-- /Page Header -->
</div>
</div>


<!--------------------------------------------------------------------------------------------------->

<!-- Print Modal -->
<div class="modal custom-modal fade" id="print_salary_report" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Salary Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table>
                    <thead>

                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Bank Name</th>
                            <th>Branch Name</th>
                            <th>Account No</th>
                            <th>Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>ABC Bank</td>
                            <td>Main Branch</td>
                            <td>123456789</td>
                            <td>$50,000</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>

        </div>

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
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait', title: 'Salary Report' }
        };

        html2pdf(element, options);
    }
</script>

@endsection