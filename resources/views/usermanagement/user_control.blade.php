@extends('layouts.master')
@section('content')

    <!-- Include Bootstrap CSS and JS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- Include Bootstrap DateTimePicker CSS and JS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
<!-- Main CSS -->
{{-- <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}"> --}}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">User Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i
                                class="fa fa-plus"></i> Add User</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('search/user/list') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group ">
                            <input type="text" class="form-control floating" id="name" name="name" placeholder="Name">
                            {{-- <label class="focus-label">Name</label> --}}
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group ">
                            <input type="text" class="form-control floating" id="name" name="department" placeholder="Department">
                            {{-- <label class="focus-label">Department</label> --}}
                        </div>
                    </div>
                    {{-- <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="name" name="status">
                            <label class="focus-label">Status</label>
                        </div>
                    </div> --}}
                    <div class="col-sm-6 col-md-3">
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User ID</th>
                                    <th hidden></th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    {{-- <th>Phone</th> --}}
                                    {{-- <th>Join Date</th> --}}
                                    {{-- <th>Role</th>
                                    <th>Status</th> --}}
                                    <th>Department</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key => $user)
                                    <tr>
                                        <td>
                                            <span hidden class="image">{{ $user->avatar }}</span>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('employee/profile/' . $user->user_id) }}" class="avatar"><img
                                                        src="{{ URL::to('/images/' . $user->avatar) }}"
                                                        alt="{{ $user->avatar }}"></a>
                                                <a href="{{ url('employee/profile/' . $user->user_id) }}"
                                                    class="name">{{ $user->name }}</span></a>
                                            </h2>
                                        </td>
                                        <td hidden class="ids">{{ $user->id }}</td>
                                        <td class="id">{{ $user->user_id }}</td>
                                        <td class="email">{{ $user->email }}</td>
                                        <td class="position">{{ $user->position }}</td>
                                        <td class="phone_number" hidden>{{ $user->phone_number }}</td>
                                        <td class="join_date" hidden>{{ $user->join_date }}</td>
                                        {{-- <td>
                                        @if ($user->role_name == 'Admin')
                                            <span class="badge bg-inverse-danger role_name">{{ $user->role_name }}</span>
                                            @elseif ($user->role_name=='Super Admin')
                                            <span class="badge bg-inverse-warning role_name">{{ $user->role_name }}</span>
                                            @elseif ($user->role_name=='Normal User')
                                            <span class="badge bg-inverse-info role_name">{{ $user->role_name }}</span>
                                            @elseif ($user->role_name=='Client')
                                            <span class="badge bg-inverse-success role_name">{{ $user->role_name }}</span>
                                            @elseif ($user->role_name=='Employee')
                                            <span class="badge bg-inverse-dark role_name">{{ $user->role_name }}</span>
                                        @endif
                                    </td> --}}
                                        <td hidden>
                                            <div class="dropdown action-label">
                                                @if ($user->status == 'Active')
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-success"></i>
                                                        <span class="statuss">{{ $user->status }}</span>
                                                    </a>
                                                @elseif ($user->status == 'Inactive')
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-info"></i>
                                                        <span class="statuss">{{ $user->status }}</span>
                                                    </a>
                                                @elseif ($user->status == 'Disable')
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-danger"></i>
                                                        <span class="statuss">{{ $user->status }}</span>
                                                    </a>
                                                @elseif ($user->status == '')
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-dot-circle-o text-dark"></i>
                                                        <span class="statuss">N/A</span>
                                                    </a>
                                                @endif

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fa fa-dot-circle-o text-success"></i> Active
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fa fa-dot-circle-o text-warning"></i> Inactive
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Disable
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="department">{{ $user->department }}</td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item userUpdate" data-toggle="modal"
                                                        data-id="'.$user->id.'" data-target="#edit_user"><i
                                                            class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item userDelete" href="#"
                                                        data-toggle="modal" ata-id="'.$user->id.'"
                                                        data-target="#delete_user"><i class="fa fa-trash-o m-r-5"></i>
                                                        Delete</a>
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
        </div>
        <!-- /Page Content -->


        <!-- Add User Modal -->
        <div id="add_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user/add/save') }}" method="POST" enctype="multipart/form-data"
                            onsubmit="return validateForm()">
                            @csrf
                            <div class="rows">
                                <div class="group" id="uploadGroup">
                                    <div class="user-profile">
                                        <img id="profileImage" src="{{ asset('assets/images/photo_defaults.jpg') }}"
                                            alt="">
                                        <span class="edit-text" onclick="openFileInput()">Edit</span>
                                    </div>
                                    <input type="file" name="image" id="fileInput" style="display: none;"
                                        accept="image/*" onchange="previewImage()">
                                </div>
                                <div class="row">
                                    <div class="col-sms">
                                        <div class="col-sm col-sm-input">
                                            <label for="">User Id</label><br>
                                            <input class="form-control col-sm" type="text" name="user_id"
                                                id="user_id" value="{{ $nextUserId }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sms">
                                        <div class="col-sm col-sm-input">
                                            <label for="">User Role</label><br>
                                            <select class="form-control col-sm" name="role_name" id="role_name" disabled>
                                                @foreach ($role_name as $role)
                                                    <option value="{{ $role->role_type }}">{{ $role->role_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-right ">
                                        <label for="">Department</label><br>
                                        <select class="form-control" name="department" id="department">
                                            <option value="" selected disabled>-- Select Department --</option>
                                            @foreach ($department as $departments)
                                                <option value="{{ $departments->department }}">
                                                    {{ $departments->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-right">
                                        <label for="">Position</label><br>
                                        <select class="form-control" name="position" id="position">
                                            <option value="" selected disabled>-- Select Position --</option>
                                            <!-- Add other position options dynamically if needed -->
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input class="form-control " type="text" id="fullname" name="name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Emaill Address</label>
                                    <input class="form-control" type="email" id="" name="email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="tel" id="" name="phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Created Date and Time</label>
                                        <?php $dateTime = now()->format('Y-m-d H:i:s'); ?>
                                        <div class='input-group date' id='datetimepicker'>
                                            <input type='text' class="form-control" name="created_at" id="created_at"
                                                value="{{ old('created_at', $dateTime) }}" readonly />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <script type="text/javascript">
                                            $(function() {
                                                $('#datetimepicker').datetimepicker({
                                                    format: 'YYYY-MM-DD HH:mm:ss', // Include time in the format
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Repeat Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="form-control" name="status" id="status">
                                        @foreach ($status_user as $status)
                                            <option value="{{ $status->type_name }}"
                                                {{ $loop->first ? 'selected' : '' }}>
                                                {{ $status->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto float-right ml-auto">
                                    <br><br>
                                    <button id="add_user_btn" class="btn add-btn" data-toggle="modal"
                                        data-target="#add_user" disabled>
                                        <i class="fa fa-plus"></i> Add User
                                    </button>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="" name="name" value="{{ old('name') }}" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Emaill Address</label>
                                    <input class="form-control" type="email" id="" name="email" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Role Name</label>
                                    <select class="select" name="role_name" id="role_name">
                                        <option selected disabled> --Select --</option>
                                        @foreach ($role_name as $role)
                                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Position</label>
                                    <select class="select" name="position" id="position">
                                        <option selected disabled> --Select --</option>
                                        @foreach ($position as $positions)
                                        <option value="{{ $positions->position }}">{{ $positions->position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="tel" id="" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Department</label>
                                    <select class="select" name="department" id="department">
                                        <option selected disabled> --Select --</option>
                                        @foreach ($department as $departments)
                                        <option value="{{ $departments->department }}">{{ $departments->department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status" id="status">
                                        <option selected disabled> --Select --</option>
                                        @foreach ($status_user as $status)
                                        <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="image">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Repeat Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Choose Repeat Password">
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add User Modal -->

        <!-- Edit User Modal -->
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
                            <div class="rows">
                                <div class="group" id="uploadGroup">
                                    <div class="user-profile">
                                        <span hidden class="image"></span>
                                        <a href="{{ url('employee/profile/' . $user->user_id) }}" id="e_image"><img
                                                src="{{ URL::to('/images/' . $user->avatar) }}"
                                                alt="{{ $user->avatar }}"></a>
                                        <span class="edit-text" onclick="openFileInput()">Edit</span>
                                    </div>
                                    <input type="file" name="avatar" id="fileInput" style="display: none;"
                                        accept="image/*" onchange="previewImage()">
                                </div>
                                <div class="row">
                                    <div class="col-sms">
                                        <div class="col-sm col-sm-input">
                                            <label for="">User Id</label><br>
                                            @csrf
                                            <input class="form-control col-sm" type="text" name="user_id"
                                                id="e_id"readonly>
                                        </div>
                                    </div>

                                    <div class="col-sms">
                                        <div class="col-sm col-sm-input">
                                            <label for="">User Role</label><br>
                                            <select class="form-control col-sm" name="role_name" id="role_name" disabled>
                                                @foreach ($role_name as $role)
                                                    <option value="{{ $role->role_type }}">{{ $role->role_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-right ">
                                        <label for="">Department</label>
                                        <select class="select" name="department" id="e_department">
                                            @foreach ($department as $departments)
                                                <option value="{{ $departments->department }}">
                                                    {{ $departments->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-right ">
                                        <label for="">Position</label><br>
                                        <select class="select" name="position" id="e_position">
                                            @foreach ($position as $positions)
                                                <option value="{{ $positions->position }}">{{ $positions->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" type="text" name="name" id="e_name"
                                            value="" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="email" id="e_email"
                                        value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone"
                                            value="" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Created Date and Time</label>
                                    <input class="form-control" type="text" id="e_join_date" name="join"
                                        value="" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Repeat Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status_user" id="e_status">
                                        @foreach ($status_user as $status)
                                            <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto float-right ml-auto">
                                    <br><br>
                                    <button type="submit" class="btn add-btn">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="e_id" value="">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" type="text" name="name" id="e_name" value="" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="email" id="e_email" value=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Role Name</label>
                                    <select class="select" name="role_name" id="e_role_name">
                                        @foreach ($role_name as $role)
                                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Position</label>
                                    <select class="select" name="position" id="e_position">
                                        @foreach ($position as $positions)
                                        <option value="{{ $positions->position }}">{{ $positions->position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>Department</label>
                                    <select class="select" name="department" id="e_department">
                                        @foreach ($department as $departments)
                                        <option value="{{ $departments->department }}">{{ $departments->department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <select class="select" name="status" id="e_status">
                                        @foreach ($status_user as $status)
                                        <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="images">
                                    <input type="hidden" name="hidden_image" id="e_image" value="">
                                </div>
                            </div>
                            <br>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- /Edit Salary Modal -->

        <!-- Delete User Modal -->
        <div class="modal custom-modal fade" id="delete_user" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete User</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('user/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <input type="hidden" name="avatar" class="e_avatar" value="">
                                <div class="row">
                                    <div class="col-6" style="display: flex; align-items: center; justify-content: center;">
                                        <button type="submit" class=" continue-btn submit-btn delete-btn" style="margin-top: 15px; border-radius: 100px; background-color: #f53542 !important; color: #fff !important; border-color: #f53542 !important; transition: background-color 0.3s, border-color 0.3s;" onmouseover="this.style.backgroundColor='#f80919'; this.style.borderColor='#f80919';" onmouseout="this.style.backgroundColor='#f53542'; this.style.borderColor='#f53542';">Delete</button>
                                    </div>
                                 
                                    <div class="col-6" style="display: flex; align-items: center; justify-content: center;">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="cancel-btn text-center"
                                           style="width: 210px; height: 50px; display: flex; align-items: center !important; justify-content: center !important; background-color: transparent !important; color: #f53542 !important; border-color: #f53542 !important; transition: background-color 0.3s, border-color 0.3s; border-radius: 100px !important;"
                                           onmouseover="this.style.backgroundColor='#f53542'; this.style.borderColor='#f53542'; this.style.color='#fff';"
                                           onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='#f53542'; this.style.color='#f53542';"
                                        >
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete User Modal -->
    </div>
    <!-- /Page Wrapper -->
@section('script')
    {{-- update js --}}
    <script>
        $(document).on('click', '.userUpdate', function() {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_join_date').val(_this.find('.join_date').text());
            // $('#e_join_date').val(_this.find('.join_date').text());

            $('#e_image').val(_this.find('.avatar').text());

            var name_role = (_this.find(".role_name").text());
            var _option = '<option selected value="' + name_role + '">' + _this.find('.role_name').text() +
                '</option>'
            $(_option).appendTo("#e_role_name");

            var position = (_this.find(".position").text());
            var _option = '<option selected value="' + position + '">' + _this.find('.position').text() +
                '</option>'
            $(_option).appendTo("#e_position");

            var department = (_this.find(".department").text());
            var _option = '<option selected value="' + department + '">' + _this.find('.department').text() +
                '</option>'
            $(_option).appendTo("#e_department");

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' + statuss + '">' + _this.find('.statuss').text() + '</option>'
            $(_option).appendTo("#e_status");
            var statuss = (_this.find(".statuss").text());

        });
    </script>
    {{-- delete js --}}
    <script>
        $(document).on('click', '.userDelete', function() {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
            $('.e_avatar').val(_this.find('.image').text());
        });
    </script>

    <!-- Your existing HTML code -->

    <script>
        function validateForm() {
            var email = $('input[name="email"]').val();
            var phone = $('input[name="phone"]').val();
            var password = $('input[name="password"]').val();
            var confirmPassword = $('input[name="password_confirmation"]').val();
            var department = $('#department').val();
            var position = $('#position').val();
            var fullname = $('#fullname').val();

            return email !== "" && phone !== "" && password !== "" && confirmPassword !== "" && department !== null &&
                position !== null && fullname !== "";
        }

        // Enable/disable the button based on required fields
        $(document).ready(function() {
            $('input[name="email"], input[name="phone"], input[name="password"], input[name="password_confirmation"], #department, #position, #fullname')
                .on('input change', function() {
                    var isValid = validateForm();

                    if (isValid) {
                        // Enable the button
                        $('#add_user_btn').prop('disabled', false);
                    } else {
                        // Disable the button
                        $('#add_user_btn').prop('disabled', true);
                    }
                });
        });
    </script>






<script>
    // When the department dropdown changes
    $('#department').on('change', function() {
        var selectedDepartment = $(this).val();

        // Clear the options in the position dropdown and set the placeholder
        $('#position').empty().append('<option value="" selected disabled>-- Select Position --</option>');

        // If a department is selected
        if (selectedDepartment) {
            // Fetch positions based on the selected department
            $.ajax({
                url: '/get-positions/' + selectedDepartment,
                type: 'GET',
                success: function(data) {
                    // Populate the position dropdown with the fetched data
                    $.each(data, function(key, value) {
                        $('#position').append('<option value="' + value.position + '">' +
                            value.position + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
</script>



    <script>
        function openFileInput() {
            document.getElementById('fileInput').click();
        }

        function previewImage() {
            const fileInput = document.getElementById('fileInput');
            const profileImage = document.getElementById('profileImage');

            const selectedFile = fileInput.files[0];

            if (selectedFile) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };

                reader.readAsDataURL(selectedFile);
            }
        }
    </script>
@endsection

@endsection
