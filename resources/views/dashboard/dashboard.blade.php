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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                            background-color: #ED5A5B;
                                        }

                                        .overflow-auto::-webkit-scrollbar-track {
                                            background-color: #f1f1f1;
                                        }
                                    </style>

                                    <div class="overflow-auto p-3 bg-light"
                                        style="max-width: 1000px; max-height: 200px; overflow: hidden; border-radius: 15px;">

                                        {{-- @foreach ($employees as $employee)
                                    @if ($employee->dob && $employee->dob->format('m-d') === now()->format('m-d'))
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
                                    @endforeach --}}
                                        @foreach ($employees as $employee)
                                            @if ($employee->dob && $employee->dob->format('m-d') === now()->format('m-d'))
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
                            <div class="col-md-6">
                                <!-- Content for the third row, first column -->
                                <div class="col">
                                    {{-- @if ($employee->d_name == 'D000002') --}}
                                    <div class="col-md-12 col-lg-6 col-xl-12 d-flex">
                                        <div class="card flex-fill">
                                            <div class="card-body" id="dep2">

                                                @php
                                                    $department = [];
                                                @endphp

                                                @foreach ($employees as $employee)
                                                    @if ($employee->d_name == 'D000002')
                                                        @php
                                                            $department[] = \App\Models\Department::where(
                                                                'id',
                                                                $employee->d_name,
                                                            )->first();
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @if ($department)
                                                    <h4 class="card-title">Today Absent
                                                        {{ $department[0]->department }}<span
                                                            class="badge bg-inverse-danger ml-2"></span>
                                                    </h4>
                                                @endif
                                                <!-- Default show -->
                                                <div class="load">
                                                    @foreach ($absentEmployees as $departmentName => $departmentEmployees)
                                                        @foreach ($departmentEmployees as $employee)
                                                            @if ($employee->d_name == 'D000002')
                                                                <div class="custom-info-box mt-2 ">
                                                                    <div class="media align-items-center">
                                                                        <a href="profile.html" class="avatar">
                                                                            <img alt="" src="assets/img/user.jpg">
                                                                        </a>
                                                                        <div class="media-body">
                                                                            <div class="text-sm my-0">
                                                                                {{ $employee->full_name }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                <div class="load-more  text-center">
                                                    <a class="text-dark" href="javascript:void(0);"
                                                        onclick="showMore()">Load More</a>
                                                </div>
                                            </div>
                                            <!-- Additional employees hidden by default -->
                                            @foreach ($absentEmployees as $departmentName => $departmentEmployees)
                                                @foreach ($departmentEmployees as $employee)
                                                    @if ($employee->d_name == 'D000002')
                                                        <div class="custom-info-box box mt-2" style="display:none;">
                                                            <div class="media align-items-center">
                                                                <a href="profile.html" class="avatar">
                                                                    <img alt="" src="assets/img/user.jpg">
                                                                </a>
                                                                <div class="media-body">
                                                                    <div class="text-sm my-0">
                                                                        {{ $employee->full_name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach

                                            <div class="load-more less text-center" style="display:none;">
                                                <a class="text-dark" href="javascript:void(0);"
                                                    onclick="showLess()">Less More</a>
                                            </div>

                                            <script>
                                                function showMore() {
                                                    document.querySelectorAll('.box').forEach(function(element) {
                                                        element.style.display = 'block';
                                                    });
                                                    document.querySelector('.load').style.display = 'none';
                                                    document.querySelector('.less').style.display = 'block';
                                                }

                                                function showLess() {
                                                    document.querySelectorAll('.box').forEach(function(element, index) {
                                                        if (index > -1) {
                                                            element.style.display = 'none';
                                                        }
                                                    });
                                                    document.querySelector('.load').style.display = 'block';
                                                    document.querySelector('.less').style.display = 'none';
                                                }
                                            </script>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Content for the third row, second column -->
                            <div class="col">

                                <div class="col-md-12 col-lg-6 col-xl-12 d-flex">
                                    <div class="card flex-fill">
                                        <div class="card-body">
                                            @php
                                                $departments = [];
                                            @endphp

                                            @foreach ($employees as $employee)
                                                @if ($employee->d_name == 'D000001')
                                                    @php
                                                        $departments[] = \App\Models\Department::where(
                                                            'id',
                                                            $employee->d_name,
                                                        )->first();
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if (!empty($departments))
                                                {{-- <h4 class="card-title">Today Absent
                                                    {{ $departments[0]->department }}<span
                                                        class="badge bg-inverse-danger ml-2"></span>
                                                </h4> --}}
                                            @endif
                                            <div class="ld-more">
                                                @foreach ($absentEmployees as $departmentName => $departmentEmployees)
                                                    @foreach ($departmentEmployees as $employee)
                                                        @if ($employee->d_name == 'D000001')
                                                            <div class="custom-info-box mt-2">
                                                                <div class="media align-items-center">
                                                                    <a href="profile.html" class="avatar">
                                                                        <img alt="" src="assets/img/user.jpg">
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <div class="text-sm my-0">{{ $employee->full_name }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @break
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                <div class="load-more text-center">
                                                    <a class="text-dark" href="javascript:void(0);" onclick="toggleSections()">Load More</a>
                                                </div>
                                            </div>

                                            <div class="ls-more" style="display:none;">
                                                @foreach ($absentEmployees as $departmentName => $departmentEmployees)
                                                    @foreach ($departmentEmployees as $employee)
                                                        @if ($employee->d_name == 'D000001')
                                                            <div class="custom-info-box mt-2">
                                                                <div class="media align-items-center">
                                                                    <a href="profile.html" class="avatar">
                                                                        <img alt="" src="assets/img/user.jpg">
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <div class="text-sm my-0">{{ $employee->full_name }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                <div class="load-more text-center">
                                                    <a class="text-dark" href="javascript:void(0);" onclick="toggleSection()">Less More</a>
                                                </div>
                                            </div>

                                            <script>
                                                function toggleSections() {
                                                    document.querySelector('.ld-more').style.display = 'none';
                                                    document.querySelector('.ls-more').style.display = 'block';
                                                }
                                                function toggleSection() {
                                                    document.querySelector('.ld-more').style.display = 'block';
                                                    document.querySelector('.ls-more').style.display = 'none';
                                                }
                                            </script>

                                    </div>
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
