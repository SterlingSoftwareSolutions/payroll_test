<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: lightgray;
        }
        /* Align the heading and date in a row */
        .row {
            display: flex;
            justify-content: space-between;
        }

        /* Style the heading */
        h2 {
            margin: 0; /* Remove default margin */
        }

        /* Style the date */
        .text-md-right {
            font-size: 18px; /* Example font size */
        }

    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-6">
            <h2>Attendance Report</h2>
        </div>
        <div class="col-md-6 text-md-right">
            {{ date('Y-m', strtotime($attendances[0]['date'])) }}
        </div>
    </div>
    

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Number of work days</th>
                <th>WO</th>
                <th>Absent days</th>
                <th>Work Holidays</th>
                <th>Work days</th>
                <th>Extra days</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $report->work_days }}</td>
                <td>{{ $report->month_weekends }}</td>
                <td>{{ $report->absent_days }}</td>
                <td>{{ $report->days_worked_holiday }}</td>
                <td>{{ $report->days_worked }}</td>
                <td>{{ $report->days_worked_weekend}}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Punch In</th>
                <th>Punch Out</th>
                <th>Work Hours</th>
                <th>OT</th>
                <th>Late</th>
            </tr>
        </thead>
        <tbody>
            @foreach (collect($attendances)->sortBy('date') as $attendance)
            <tr>
                <td>{{ date('Y-m-d', strtotime($attendance['date'])) }}</td>
                <td>{{ date('H:i:s', strtotime($attendance['punch_in'])) }}</td>
                <td>{{ date('H:i:s', strtotime($attendance['punch_out'])) }}</td>
                <td>{{ $attendance['workHours'] }}</td>
                <td>{{ $attendance['OT'] }}</td>
                <td>{{ $attendance['late'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
