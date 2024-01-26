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
                <td>{{ $total_days }}</td>
                <td>{{ $absent_days }}</td>
                <td>{{ $weekend_days }}</td>
                <td>{{ $holiday_working_count }}</td>
                <td>{{ $attended_days }}</td>
                <td>{{ $extra_days_count }}</td>
            </tr>
        </tbody>


    </table>
</body>

</html>