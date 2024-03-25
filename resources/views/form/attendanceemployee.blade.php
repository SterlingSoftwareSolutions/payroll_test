@extends('layouts.master')

@section('content')

<!-- Page Wrapper -->
<div class="page-wrapper">

    @if(session('import_errors'))
        <!-- Upload CSV Errors - Modal -->
        <div id="errorsModal" class="modal fade">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Import Errors</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div style="height: 350px; overflow-y: scroll;">
                    @foreach(session('import_errors') as $date=>$import_errors)
                    <div class="mb-4">
                        <h4 class="mb-2">Date: {{$date}}</h4>
                        @foreach($import_errors as $work_id=>$message)
                        <p class="mb-2">Work ID: {{$work_id}} - <span class="text-secondary">{{$message}}</span></p>
                        @endforeach
                    </div>
                    @endforeach
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#errorsModal').modal('show');
            });
        </script>
    @endif

    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-lists-center">
                <div class="col">
                    <h3 class="page-title">Attendence</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ul>
                </div>

                <form action="{{ route('form/attendance/csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="csv_file">CSV File:</label>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                    </div>
                    <button type="submit">Upload CSV</button>
                </form>

                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_attendance"
                        id="add_attendence"><i class="fa fa-plus"></i> Add Attendance</a>

                </div>
            </div>
        </div>
        <!-- /Page Header -->



        <!-- Search Filter -->
        <form action="{{ route('all/attendance/search') }}" method="POST">
            @csrf
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group ">
                        <input type="text" class="form-control floating" name="employee_id" placeholder="Employee ID">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <select class="select floating" name="month">
                            <option disabled selected>-- Select Month --</option>
                            <option value="01">Jan</option>
                            <option value="02">Feb</option>
                            <option value="03">Mar</option>
                            <option value="04">Apr</option>
                            <option value="05">May</option>
                            <option value="06">Jun</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                            <option value="09">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group">
                        <input type="number" class="form-control floating" name="select_year" placeholder="Select Year">
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
                                    option.value = department
                                        .id; // Assuming your department model has an 'id' field
                                    option.textContent = department
                                        .name; // Assuming your department model has a 'name' field
                                    dropdown.appendChild(option);
                                });
                            });
                    });

                    // Handle department selection
                    document.getElementById('departmentDropdown').addEventListener('change', function() {
                        const selectedDepartment = this.value;
                        document.getElementById('selectedDepartment').innerText = `Selected Department: ${selectedDepartment}`;

                    });
                </script>

                <div class="col-sm-6 col-md-3 ">
                    <button type="submit" class="btn btn-danger btn-block" style="height: 30px;">
                        <img src="{{ URL::to('assets/img/search.png') }}" alt="">
                        &nbsp;&nbsp;Search
                    </button>
                </div>
            </div>
        </form>


        <!-- Page Content -->
        <h3 class="page-title">Attendance</h3>
        {{-- message --}}
        {!! Toastr::message() !!}
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Attendance Id</th>
                                <th>Work Id</th>
                                <th>Employee Name</th>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Production</th>
                                <th>Over Time</th>
                                <th>Late Time</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (!empty($attendance))
                                @foreach ($attendance as $key => $items)
                                <tr class="attendance-completed">
                                    <td>{{ ++$key }}</td>
                                    <td class="text-left" id="ids">{{ $items->id }}</td>
                                    <td class="text-left" id="ids">{{ $items->WorkId }}</td>
                                    <td class="text-left" id="empname">{{ $items->employee->full_name }}</td>
                                    <td class="text-left" id="empid" hidden>{{ $items->employee->id }}</td>
                                    <td class="text-left" id="selctdate">{{ $items->date->format('Y-m-d') }}</td>
                                    <td class="text-left" id="punchin">{{ $items->punch_in->format('H:i') }}</td>
                                    <td class="text-left" id="punchout">{{ $items->punch_out->format('H:i') }}</td>
                                    {{-- Calculate Work Hours --}}
                                    {{-- @php
                                        $punchIn = new DateTime($items->punch_in);
                                        $punchOut = new DateTime($items->punch_out);
                                        $workHours = $punchOut->diff($punchIn)->format('%H:%I');
                                    @endphp --}}

                                    <td class="text-left">{{  $items->workHours }}</td>

                                    {{-- Calculate Overtime --}}
                                    {{-- @php
                                        $regularWorkingHours = new DateTime('10:00');
                                        $workHours = new DateTime($punchOut->diff($punchIn)->format('%H:%I'));
                                        $overtime = $workHours > $regularWorkingHours ?
                                        $workHours->diff($regularWorkingHours)->format('%H:%I') : '00:00';
                                    @endphp --}}
                                    <td class="text-left">{{ $items->OT }}</td>
                                    <td class="text-left">{{ $items->late }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item userUpdate" data-toggle="modal"
                                                    data-id="{{ $items->id }}" data-target="#edit_attendance">
                                                    <i class="fa fa-pencil m-r-5"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            <!----------------for csv file data get -------------------------->
                            @if(isset($CsvData) && count($CsvData) > 0)
                                @foreach($CsvData as $data)
                                <tr>
                                    @foreach($data as $header => $value)
                                    <td>{{ $value }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @endif
                            <!-- Edit Attendance Modal -->
                            <div class="modal custom-modal fade" id="edit_attendance" role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Attendance</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('form/attendance/update') }}" method="POST">
                                                @csrf
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Attendance ID <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" style="width: 100%;" tabindex="-1"
                                                            aria-hidden="true" id="a_id" name="attendance_id" readonly>
                                                    </div>
                                                </div>
                                                {{-- employee Id send controller --}}
                                                <input class="form-control" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true" id="e_id" name="employee_id" hidden>

                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Select Employee <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" style="width: 100%;" tabindex="-1"
                                                            aria-hidden="true" id="e_name" name="employee_name"
                                                            readonly>
                                                        @error('employee_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Select Date <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" style="width: 100%;" tabindex="-1"
                                                            aria-hidden="true" id="e_date" type="date" name="date">
                                                        @error('date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Punch In time <span
                                                                class="text-danger">*</span></label>
                                                        <div>
                                                            <input class="form-control" style="width: 100%;"
                                                                tabindex="-1" aria-hidden="true" id="e_punchin"
                                                                name="punch_in" type="time">
                                                            @error('punch_in')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Punch Out time <span
                                                                class="text-danger">*</span></label>
                                                        <div>
                                                            <input class="form-control" style="width: 100%;"
                                                                tabindex="-1" aria-hidden="true" id="e_punchout"
                                                                name="punch_out" type="time">
                                                            @error('punch_out')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- ... existing form fields ... -->

                                                <div class="submit-section">
                                                    <button type="submit"
                                                        class="btn btn-primary submit-btn">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Edit Attendance Modal -->

                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Attendance Modal -->
        <div id="add_attendance" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form.attendance.store') }}" method="POST">
                            @csrf
                            <div class="col-md">
                                <div class="form-group">
                                    <label class="col-form-label">Attendance ID <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="attendance_id" name="attendance_id" value="{{ $next_id }}" disabled>
                                    @error('attendance_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label class="col-form-label">Select Employee <span
                                            class="text-danger">*</span></label>
                                    <input list="browsers" name="employee_id" id="employee_id" class="form-control"
                                        style="width: 100%;">
                                    @error('employee_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('selected_employee_id')
                                    <span class="text-danger">Enter Correct employee ID</span>
                                    @enderror
                                    <datalist id="browsers">
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }} - {{ $employee->full_name }}">
                                            {{ $employee->employee_id }}
                                        </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <!-- Add an invisible input field -->
                            <input type="hidden" name="selected_employee_id" id="selected_employee_id"
                                value="{{ $employee->id }}">

                            <script>
                                // Add event listener to update the hidden input field value
                                document.getElementById('employee_id').addEventListener('input', function() {
                                    var selectedEmployeeId = this.value.split(' - ')[0]; // Extracting the ID from the selected option
                                    document.getElementById('selected_employee_id').value = selectedEmployeeId;
                                });
                            </script>

                            <div class="col-md">
                                <div class="form-group">
                                    <label class="col-form-label">Select Date <span class="text-danger">*</span></label>
                                    <input class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true"
                                        id="date" type="date" name="date">
                                    @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label class="col-form-label">Punch In time <span
                                            class="text-danger">*</span></label>
                                    <div>
                                        <input class="form-control" style="width: 100%;" tabindex="-1"
                                            aria-hidden="true" id="punch_in" name="punch_in" type="time">
                                        @error('punch_in')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label class="col-form-label">Punch Out time <span
                                            class="text-danger">*</span></label>
                                    <div>
                                        <input class="form-control" style="width: 100%;" tabindex="-1"
                                            aria-hidden="true" id="punch_out" name="punch_out" type="time">
                                        @error('punch_out')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ... existing form fields ... -->

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Employee Modal -->
    </div>
</div>
@endsection
    <!-- /Page Wrapper -->
    @section('script')
        <script>
            $("input:checkbox").on('click', function() {
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[class='" + $box.attr("class") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
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
            $('#name').on('change', function() {
                $('#employee_id').val($(this).find(':selected').data('employee_id'));
                $('#email').val($(this).find(':selected').data('email'));
            });
        </script>

        <script>
            // Disable future dates
            document.getElementById('date').addEventListener('input', function() {
                var today = new Date().toISOString().split('T')[0];
                if (this.value > today) {
                    this.value = today;
                }
            });
        </script>

        <!-- Include punch in and punch out time adjust -->
        <script>
            $(document).ready(function() {
                $('#punch_in, #e_punchin').attr('max', getCurrentTime());

                // Handle Punch In time changes
                $('#punch_in, #e_punchin').on('input', function() {
                    var punchInTime = $(this).val();

                    $('#punch_out, #e_punchout').attr('min', punchInTime);

                    $('#punch_out, #e_punchout').attr('max', getCurrentTime());

                    var selectedTime = $('#punch_out, #e_punchout').val();

                    if (selectedTime < punchInTime) {
                        $('#punch_out, #e_punchout').val(punchInTime);
                    }
                });

                // Function to get the current time in HH:mm format
                function getCurrentTime() {
                    var currentTime = new Date();
                    var currentHour = currentTime.getHours();
                    var currentMinutes = currentTime.getMinutes();
                    return currentHour.toString().padStart(2, '0') + ':' + currentMinutes.toString().padStart(2, '0');
                }
            });
        </script>

        {{-- update js --}}
        <script>
            $(document).on('click', '.userUpdate', function() {
                var _this = $(this).parents('tr');
                $('#a_id').val(_this.find('#ids').text());
                $('#e_name').val(_this.find('#empname').text());
                $('#e_id').val(_this.find('#empid').text());
                $('#e_date').val(_this.find('#selctdate').text());
                $('#e_punchin').val(_this.find('#punchin').text());
                $('#e_punchout').val(_this.find('#punchout').text());
                console.log(_this.find('#empid').text());

            });
        </script>
        <script>
            // Function to reload table data
            function reloadTableData() {
                // Make an AJAX request to fetch the updated data
                $.ajax({
                    type: 'GET',
                    url: '{{ route('all.employee.getData') }}', // Adjust the route as needed
                    success: function(data) {
                        // Update the table with the new data
                        updateTable(data.employees);
                        toastr.success('Employee data updated successfully');
                        // Trigger table update after a successful submission
                        reloadTableData();
                    },
                    error: function(error) {
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
                $.each(employees, function(index, employee) {
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

            @if ($errors -> any())
                document.getElementById("add_attendence").click();
            @endif
        </script>
    @endsection
