<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report</title>
</head>

<body>
    <h2>Attendance Report</h2>

    <table border='1' cellpadding="5" cellspacing="5">


        <thead>
            <tr>
                <th>Name</th>
                <th>Number of days</th>
                <th>Absent days</th>
                <th>WO</th>
                <th>Holidays</th>
                <th>Working days</th>
                <th>Extra days</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $work_days }}</td>
                <td>{{ $no_pay_leaves }}</td>
                <td>{{ $month_weekends_count }}</td>
                <td>{{ $month_holidays->count() }}</td>
                <td>{{ $days_worked->count() + $days_worked_holiday->count() - $days_worked_holiday_weekend->count() }}</td>
                <td>{{ $days_worked_weekend->count() }}</td>
            </tr>
        </tbody>


    </table>
</body>

</html>