@extends('layouts.master')
@section('content')


<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Welcome {{ Session::get('name') }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header    ---->

        <div class="container mt-4">
            <!-- First Row -->
            <div class="row">
                <div class="col-md-6 border">
                    <!-- Content for the first column -->
                    <div class="col">
                        <div class="col-md-9 col-sm-9 col-lg-9 col-xl-12">
                            <div class="card dash-widget">
                                <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                                    <div class="dash-widget-info">

                                        <span>Employees </span>
                                        <h2>{{ $employeeCount }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 border">
                    <!-- Content for the second column -->

                    <div class="col">
                        <div class="">
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xl-12">
                                <div class="card dash-widget">
                                    <div class="card-body">
                                        <span class="dash-widget-icon"> <i class="fa-solid fa-calendar-days"
                                                style="color: #ff0a0a;"></i></span>
                                        <div class="dash-widget-info">
                                            <div id="timezone-widget">
                                                <p id="current-date"></p>
                                                <p id="current-timezone"></p>
                                            </div>

                                            <script>
                                                function updateDateTime() {
                const currentDate = new Date();
                const options = { 
                    timeZone: 'Asia/Colombo', 
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                }; 

                const formattedDate = currentDate.toLocaleDateString('en-US', options);
                const formattedTime = currentDate.toLocaleTimeString('en-US', options);

                document.getElementById('current-date').innerHTML = `Today Date: ${formattedDate}`;
             //   document.getElementById('current-timezone').innerHTML = `Current Time: ${formattedTime}`;
            }

            setInterval(updateDateTime, 1000);
            updateDateTime();
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row mt-4">
                <!-- Content for the second row -->
                <div class="col-md-12 border">

                    <div class="p-3 bg-opacity-10 border-start-0 rounded-end">
                        <div class="">

                            <div class="p-3 bg-opacity-10">
                                <h5 class="card-title">Notifications</h5>

                                <style>
                                    .overflow-auto::-webkit-scrollbar {
                                        width: 12px;
                                    }

                                    .overflow-auto::-webkit-scrollbar-thumb {
                                        background-color:  #ED5A5B;
                                    }

                                    .overflow-auto::-webkit-scrollbar-track {
                                        background-color: #f1f1f1;
                                    }
                                </style>

                                <div class="overflow-auto p-3 bg-light"
                                    style="max-width: 1000px; max-height: 200px; overflow: hidden; border-radius: 15px;">

                                    @foreach($employees as $employee)
                                    @if($employee->dob && $employee->dob->format('m-d') === now()->format('m-d'))
                                    <div class="notification">
                                        <div class="card mb-3">
                                            <div class="card-body d-flex flex-row border-left border-danger">
                                                <div>
                                                    Today is {{ $employee->full_name }}'s Birthday. 🎂🎉
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Third Row -->
                    <div class="row mt-4">
                        <div class="col-md-6 border">
                            <!-- Content for the third row, first column -->
                            <div class="col">
                                <div class="col-md-12 col-lg-6 col-xl-12 d-flex">
                                    <div class="card flex-fill">
                                        <div class="card-body">
                                            <h4 class="card-title">Today Absent IT Department<span
                                                    class="badge bg-inverse-danger ml-2"></span></h4>
                                            <div class="leave-info-box">
                                                <div class="media align-items-center">
                                                    <a href="profile.html" class="avatar"><img alt=""
                                                            src="assets/img/user.jpg"></a>
                                                    <div class="media-body">
                                                        <div class="text-sm my-0">Ravindu Umayanga</div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">28 Nov 2023</h6> <span
                                                            class="text-sm text-muted">Leave Date</span>
                                                    </div>
                                                    <div class="col-6 text-right"> <span
                                                            class="badge bg-inverse-danger">Pending</span> </div>
                                                </div>
                                            </div>
                                            <div class="leave-info-box">
                                                <div class="media align-items-center">
                                                    <a href="profile.html" class="avatar"><img alt=""
                                                            src="assets/img/user.jpg"></a>
                                                    <div class="media-body">
                                                        <div class="text-sm my-0">Dhanushka Sandaruwan</div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">28 Nov 2023</h6> <span
                                                            class="text-sm text-muted">Leave Date</span>
                                                    </div>
                                                    <div class="col-6 text-right"> <span
                                                            class="badge bg-inverse-success">Approved</span> </div>
                                                </div>
                                            </div>
                                            <div class="load-more text-center"> <a class="text-dark"
                                                    href="javascript:void(0);">Load More</a> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border">
                            <!-- Content for the third row, second column -->
                            <div class="col">
                                <div class="col-md-12 col-lg-6 col-xl-12 d-flex">
                                    <div class="card flex-fill">
                                        <div class="card-body">
                                            <h4 class="card-title">Today Absent Call Department<span
                                                    class="badge bg-inverse-danger ml-2"></span></h4>
                                            <div class="leave-info-box">
                                                <div class="media align-items-center">
                                                    <a href="profile.html" class="avatar"><img alt=""
                                                            src="assets/img/user.jpg"></a>
                                                    <div class="media-body">
                                                        <div class="text-sm my-0">Nimshan Nimshan</div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">28 Nov 2023</h6> <span
                                                            class="text-sm text-muted">Leave Date</span>
                                                    </div>
                                                    <div class="col-6 text-right"> <span
                                                            class="badge bg-inverse-danger">Pending</span> </div>
                                                </div>
                                            </div>
                                            <div class="leave-info-box">
                                                <div class="media align-items-center">
                                                    <a href="profile.html" class="avatar"><img alt=""
                                                            src="assets/img/user.jpg"></a>
                                                    <div class="media-body">
                                                        <div class="text-sm my-0">Sithumini Sandaruwan</div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-3">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">28 Nov 2023</h6> <span
                                                            class="text-sm text-muted">Leave Date</span>
                                                    </div>
                                                    <div class="col-6 text-right"> <span
                                                            class="badge bg-inverse-success">Approved</span> </div>
                                                </div>
                                            </div>
                                            <div class="load-more text-center"> <a class="text-dark"
                                                    href="javascript:void(0);">Load More</a> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!----------------------------------->
            </div>
        </div>
    </div>
    <!----------------end main border-------------------------->
</div>
</div>
</div>

<!-- /Page Content -->
</div>
@endsection