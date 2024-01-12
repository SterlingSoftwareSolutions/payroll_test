@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-lists-center">
                    <div class="col">
                        <h3 class="page-title">Employee</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="{{ route('form/employee/new') }}" class="btn add-btn" data-toggle="#" data-target="#"><i class="fa fa-plus"></i> Add Employee</a>
                        <!-- todo                         
                        <div class="view-icons">
                            <a href="{{ route('all/employee/card') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                            <a href="{{ route('all/employee/list') }}" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
                        </div>
                    /todo -->
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('all/employee/search') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="full_name">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <!-- Include this section where you want the dropdown to appear -->
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <select class="select floating" name="department">
                                <option value=""> -- Select Department-- </option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Include this script at the end of your Blade view -->
                    <script>
                        // Fetch departments and populate the dropdown
                        window.addEventListener('DOMContentLoaded', (event) => {
                            fetch('/get-departments')
                                .then(response => response.json())
                                .then(departments => {
                                    const dropdown = document.getElementById('departmentDropdown');

                                    departments.forEach(department => {
                                        const option = document.createElement('option');
                                        option.value = department.id; // Assuming your department model has an 'id' field
                                        option.textContent = department.name; // Assuming your department model has a 'name' field
                                        dropdown.appendChild(option);
                                    });
                                });
                        });

                        // Handle department selection
                        document.getElementById('departmentDropdown').addEventListener('change', function () {
                            const selectedDepartment = this.value;
                            document.getElementById('selectedDepartment').innerText = `Selected Department: ${selectedDepartment}`;

                        });
                    </script>

                    <div class="col-sm-6 col-md-2">  
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>  
                    </div>
                </div>
            </form>

      <!-- Page Content -->
      <h3 class="page-title">All Employee</h3>
      {{-- message --}}
            {{-- {!! Toastr::message() !!} --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Mobile</th>
                                    <th>Role</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody> 
                                @if(!empty($employees))
                                @foreach ($employees as $employee)
                                        <tr>
                                            <td class="text-left">{{ $employee->employee_id}}</td>
                                            <td class="text-left">{{ $employee->full_name }}</td>
                                            <td class="text-left">{{ $employee->email }}</td>
                                            <td class="text-left">{{ $employee->c_number}}</td>
                                            <td class="text-left">{{ $employee->j_title}} </td>
                                            <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item userUpdate" data-toggle="modal" data-id="{{ $employee->employee_id }}" data-target="#edit_employee"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                            
                                            <!-- Edit Leave Modal -->
                                            <div id="edit_leave" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <!-- Add your edit form content here -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Leave</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Your edit form goes here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Approve Modal -->
                                            <div id="delete_approve" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <!-- Add your delete confirmation content here -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Leave</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Your delete confirmation content goes here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- View Leave Modal -->
                                            <div id="view" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <!-- Add your view content here -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">View Leave</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Your view content goes here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // Handle click event for Edit button
                                                $('.leaveUpdate').on('click', function() {
                                                    var employeeId = $(this).data('id');
                                                    // Use the employeeId to fetch data and populate the edit modal
                                                    // For example: $.ajax({ url: 'get_employee_data.php', data: { id: employeeId }, success: function(data) { /* Populate the edit modal with data */ } });
                                                });

                                                // Handle click event for Delete button
                                                $('.leaveDelete').on('click', function() {
                                                    var employeeId = $(this).data('id');
                                                    // Use the employeeId to show a confirmation message in the delete modal
                                                    // For example: $('#delete_approve .modal-body').html('Are you sure you want to delete leave with ID ' + employeeId + '?');
                                                });

                                                // Handle click event for View button
                                                $('.leaveView').on('click', function() {
                                                    var employeeId = $(this).data('id');
                                                    // Use the employeeId to fetch data and populate the view modal
                                                    // For example: $.ajax({ url: 'get_employee_data.php', data: { id: employeeId }, success: function(data) { /* Populate the view modal with data */ } });
                                                });
                                            </script>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <!-- /Page Content --> 

        <!-- Add Employee Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                     <br><h4>Employee Details</h4>
                        <form action="{{ route('list') }}" method="POST">
                            @csrf
                    
                            <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="circle-image" id="imagePreview"></div>
                                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin-bottom: 20px;"> <!-- Adjust the margin as needed -->
                                    <!-- Your upload image input goes here -->
                                </div>
                            </div>
                            </div>          

                            <style>
                            .circle-image {
                                border-radius: 50%;
                                overflow: hidden;
                                width: 100px; /* Adjust the size of the circle as needed */
                                height: 100px; /* Adjust the size of the circle as needed */
                                background-color: #eee; /* Optional: Add a background color to the circle */
                                float: left; /* Align the circle to the left side */
                                margin-right: 20px; /* Optional: Add some spacing between the input and the circle */
                            }

                            .circle-image img {
                                width: 100%;
                                height: auto;
                                display: block;
                            }
                            </style>
                            <script>
                            function previewImage() {
                                var input = document.getElementById('image');
                                var preview = document.getElementById('imagePreview');

                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function (e) {
                                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                                    };

                                    reader.readAsDataURL(input.files[0]);
                                } else {
                                    // Display a default user account image
                                    var defaultImageUrl = 'https://example.com/default-user-image.png'; // Replace with the actual URL of the default image
                                    preview.innerHTML = '<img src="' + defaultImageUrl + '" alt="Default Image">';
                                }
                            }
                            </script>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="id" name="id">
                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="f_name" name="f_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Larst Name <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="l_name" name="l_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Full Name <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="full_name" name="full_name">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="email" name="email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">DOB <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="dob" name="dob">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">NIC <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="nic" name="nic">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Contact No <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="c_number" name="c_number">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Job Title <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="j_title" name="j_title">
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Department Name <span class="text-danger">*</span></label>
                                        {{-- <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="d_name" name="d_name"> --}}
                                        
                                            <select class="select form-control floating" name="department">
                                                <option value=""> --Select Department-- </option>
                                                @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department }}</option>
                                                @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Joined Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="joinedDate" name="joinedDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Created Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="createdDate" name="createdDate">
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    // Get the current local date and time
                                    var currentDate = new Date();

                                    // Format the date as "yyyy-mm-dd hh:mm:ss"
                                    var formattedDate = currentDate.getFullYear() + "-" +
                                        ("0" + (currentDate.getMonth() + 1)).slice(-2) + "-" +
                                        ("0" + currentDate.getDate()).slice(-2) + " " +
                                        ("0" + currentDate.getHours()).slice(-2) + ":" +
                                        ("0" + currentDate.getMinutes()).slice(-2) + ":" +
                                        ("0" + currentDate.getSeconds()).slice(-2);

                                    // Set the value of the input field to the formatted date
                                    document.getElementById("createdDate").value = formattedDate;   
                                </script>
                                <div class="col-sm-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Status <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="status" name="status">
                                    </div>
                                </div>
                                <style>
                                    .text-box {
                                        border: 1px solid #ccc;
                                        padding: 5px;
                                        margin: 10px 0;
                                        width: 200px;
                                        white-space: nowrap;
                                        overflow: hidden;
                                    }
                                </style>
                                                                   
                            </div>
                            <!-- ... existing form fields ... -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Description</label>
                                            <textarea class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="description" name="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                    <button class="btn btn-primary submit-btn">Edit</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Employee Modal -->
        
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        $("input:checkbox").on('click', function()
        {
            var $box = $(this);
            if ($box.is(":checked"))
            {
                var group = "input:checkbox[class='" + $box.attr("class") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            }
            else
            {
                $box.prop("checked", false);
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2s-hidden-accessible').select2({
                closeOnSelect: false
            });
        });
    </script>
    <script>
        // select auto id and email
        $('#name').on('change',function()
        {
            $('#employee_id').val($(this).find(':selected').data('employee_id'));
            $('#email').val($(this).find(':selected').data('email'));
        });
    </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_image').val(_this.find('.image').text());

            var name_role = (_this.find(".role_name").text());
            var _option = '<option selected value="' + name_role+ '">' + _this.find('.role_name').text() + '</option>'
            $( _option).appendTo("#e_role_name");

            var position = (_this.find(".position").text());
            var _option = '<option selected value="' +position+ '">' + _this.find('.position').text() + '</option>'
            $( _option).appendTo("#e_position");

            var department = (_this.find(".department").text());
            var _option = '<option selected value="' +department+ '">' + _this.find('.department').text() + '</option>'
            $( _option).appendTo("#e_department");

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + _this.find('.statuss').text() + '</option>'
            $( _option).appendTo("#e_status");
            
        });
    </script>
    <script>
        // Function to reload table data
        function reloadTableData() {
            // Make an AJAX request to fetch the updated data
            $.ajax({
                type: 'GET',
                url: '{{ route("all.employee.getData") }}', // Adjust the route as needed
                success: function (data) {
                    // Update the table with the new data
                    updateTable(data.employees);
                    toastr.success('Employee data updated successfully');
                    // Trigger table update after a successful submission
                    reloadTableData();
                },
                error: function (error) {
                    // Handle errors (if needed)
                    console.log(error);
                    toastr.error('Error updating employee data');
                }
            });
        }

        // Function to update the table
        function updateTable(employees) {
            // Clear existing table data (you may need to adjust this based on your table structure)
            $('#employeeTable tbody').empty();

            // Iterate over the fetched data and append rows to the table
            $.each(employees, function (index, employee) {
                var row = '<tr>' +
                    '<td>' + employee.id + '</td>' +
                    '<td>' + employee.full_name + '</td>' +
                    '<td>' + employee.email + '</td>' +
                    '<td>' + employee.c_number + '</td>' +
                    '<td>' + employee.j_title + '</td>' +
                    // Add other columns as needed
                    '</tr>';
                $('#employeeTable tbody').append(row);
            });
        }
    </script>
    @endsection

@endsection
