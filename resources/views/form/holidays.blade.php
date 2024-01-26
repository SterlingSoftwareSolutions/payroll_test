@extends('layouts.master')
@section('content')



  <!-- Page Wrapper -->
  <div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Holidays <span id="year"></span></h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Holidays</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_holiday"><i class="fa fa-plus"></i> Add Holiday</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        {{-- message --}}
        {!! Toastr::message() !!}

<!-- ... (existing code) ... -->

<!-- Display the list of holidays -->
<table class="table table-striped custom-table datatable">
    <thead>
        <tr>
            <th>No</th>
            <th>Holiday ID</th>
            <th>Name</th>
            <th>Holiday Date</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($holidays as $key => $holiday)
        <tr>
            <td>{{ ++$key }}</td>
            <td class="id">{{ $holiday->id }}</td>
            <td class="holiday">{{ $holiday->holiday }}</td>
            <td class="date">{{ $holiday->date }}</td>
            <td class="text-right">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                            class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item edit_holiday" href="#" data-toggle="modal" data-target="#edit_holiday"
                            data-id="{{ $holiday->id }}" data-name="{{ $holiday->holiday }}"
                            data-date="{{ $holiday->date }}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a class="dropdown-item delete_holiday" href="#" data-toggle="modal" data-target="#delete_holiday"
                            data-id="{{ $holiday->id }}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- ... (existing code) ... -->

<!-- Add Holiday Modal -->
<div class="modal custom-modal fade" id="add_holiday" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Holiday</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display success message if any -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form to add a new holiday -->
                <form action="{{ route('form/holidays/save') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Holiday ID <span class="text-danger">*</span></label>
                        {{-- <input class="form-control" type="text" id="holidayId" name="holidayId"> --}}
                        <input class="form-control" type="text" id="holidayId" name="holidayId" value="{{ $next_id }}"readonly>

                    </div>
                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="nameHoliday" required>
                    </div>
                    <div class="form-group">
                        <label>Holiday Date <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input class="form-control datetimepicker" type="text" name="holidayDate" required>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn">Add Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Holiday Modal -->
<div class="modal custom-modal fade" id="edit_holiday" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Holiday</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('form/holidays/update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Holiday ID <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="holidayId_edit" name="holidayId" readonly>
                    </div>
                    <div class="form-group">
                        <label>Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="holidayName_edit" name="holidayName" required>
                    </div>
                    <div class="form-group">
                        <label>Holiday Date <span class="text-danger">*</span></label>
                        <div class="cal-icon">
                            <input type="text" class="form-control datetimepicker" id="holidayDate_edit" name="holidayDate"
                                required>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Holiday Modal -->
    </div>
  </div>
<!-- ... (existing code) ... -->

@section('script')
<script>
    document.getElementById("year").innerHTML = new Date().getFullYear();

    // Edit Holiday Modal
    $(document).on('click', '.edit_holiday', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var date = $(this).data('date');

        $('#holidayId_edit').val(id);
        $('#holidayName_edit').val(name);
        $('#holidayDate_edit').val(date);
    });

    // Delete Holiday Modal
    $(document).on('click', '.delete_holiday', function () {
        var id = $(this).data('id');
        $('#delete_holiday .e_id').val(id);
    });

</script>
@endsection

