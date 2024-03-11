{{-- only html css forms --}}
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
                    <h3 class="page-title">Attendance Report PDF</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Report</a></li>
                        <li class="breadcrumb-item active">Attendance Report PDF</li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- /Page Header -->

        <div class="border ">

                {{-- <style>
                    table {
                        border-collapse: collapse;
                        border-spacing: 0;
                        width: 100%;
                        border: 1px solid #ddd;
                    }

                    th,
                    td {
                        text-align: left;
                        padding: 8px;
                    }

                    tr:nth-child(even) {
                        background-color: #f2f2f2
                    }
                </style> --}}

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
</style>
            </head>

            <body>

                <h5>Monthly Attendance Summery</h5>
                <h4>Month of ............</h4>

<div class="border border-dark">
    <div style="overflow-x:auto;">
        <table>
            <tr>
                <th>Full Name</th>
                <th>Number of days</th>
                <th>Absent days</th>
                <th>WO</th>
                <th>Holidays</th>
                <th>Working Days</th>
                <th>OT</th>
                <th>Extra Days</th>

            </tr>
            <tr>
                <td>Gamika  punsisi</td>
                <td>50</td>
                <td>50</td>
                <td>50</td>
                <td>50</td>
                <td>50</td>
                <td>50</td>
                <td>50</td>

            </tr>
            <tr>
                <td>Nimshan mohommad</td>
                <td>94</td>
                <td>94</td>
                <td>94</td>
                <td>94</td>
                <td>94</td>
                <td>94</td>
                <td>94</td>

            </tr>
            <tr>
                <td>Adam</td>
                <td>67</td>
                <td>67</td>
                <td>67</td>
                <td>67</td>
                <td>67</td>
                <td>67</td>
                <td>67</td>

            </tr>
        </table>
    </div>

</body>

</html>

</div>


</div>









    </div>
</div>
</div>
