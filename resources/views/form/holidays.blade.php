
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

            @php
                use Carbon\Carbon;
                $today_date = Carbon::today()->format('d-m-Y');
            @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Holiday ID</th>
                                    <th>Name </th>
                                    <th>Holiday Date</th>
                                    <th>Day</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($holiday as $key=>$items)
                                @if(($today_date > $items->date_holiday))
                                    <tr class="holiday-completed">
                                        <td>{{ ++$key }}</td>
                                        <td class="text-left">{{ $items->holiday_id }}</td>
                                        <td>{{ $items->name_holiday }}</td>
                                        <td>{{ date('d F, Y', strtotime($items->date_holiday)) }}</td>
                                        <td>{{ date('l', strtotime($items->date_holiday)) }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                            @foreach ($holiday as $key=>$items)
                                @if(($today_date <= $items->date_holiday))
                                    <tr class="holiday-upcoming">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $items->holiday_id }}</td>
                                        <td class="holidayName">{{ $items->name_holiday }}</td>
                                        <td>{{ date('d F, Y', strtotime($items->date_holiday)) }}</td>
                                        <td>{{ date('l', strtotime($items->date_holiday)) }}</td>
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item userUpdate" data-toggle="modal" data-id="{{ $items->holiday_id }}" data-target="#edit_holiday"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_holiday"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
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
                        <form action="{{ route('form/holidays/save') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Holiday ID <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="holidayId" name="holidayId">
                            </div>
                            <div class="form-group">
                                <label>Holiday Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="nameHoliday" name="nameHoliday">
                            </div>
                            <div class="form-group">
                                <label>Holiday Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" type="text" id="holidayDate" name="holidayDate">
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Holiday Modal -->

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
                                <input type="text" class="form-control" id="holidayId_edit" name="holidayId" value="">
                            </div>
                            <div class="form-group">
                                <label>Holiday Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="holidayName_edit" name="holidayName" value="">
                            </div>
                            <div class="form-group">
                                <label>Holiday Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" id="holidayDate_edit" name="holidayDate" value="">
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

        <!-- Delete Holiday Modal -->
        <div class="modal custom-modal fade" id="delete_holiday" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Holiday</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/holidays/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="holiday_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Holiday Modal -->
       
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        document.getElementById("year").innerHTML = new Date().getFullYear();
    </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#holidayName_edit').val(_this.find('.holidayName').text());
            $('#holidayDate_edit').val(_this.find('.holidayDate').text());  
        });
    </script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userdelete',function()
        {
            var _this = $(this).parents('tr');
            $('.holiday_id').val(_this.find('holiday_id').text());
        });
    </script>
@endsection
