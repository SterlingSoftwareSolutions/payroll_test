
@extends('layouts.master')
@section('content')

<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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
<!-------------------------------------------------------------------------------->







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