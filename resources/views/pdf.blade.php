<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Report</title>
</head>
<body>
    <table style="width: 100%">
        <thead>
            <tr style="border: 1px solid #000;">
                <th style="border: 1px solid #000;">Name</th>
                <th style="border: 1px solid #000;">Number of days</th>
                <th style="border: 1px solid #000;">Absent days</th>
                <th style="border: 1px solid #000;">WO</th>
                <th style="border: 1px solid #000;">Holidays</th>
                <th style="border: 1px solid #000;">Working days</th>
                <th style="border: 1px solid #000;">Extra days</th>
            </tr>
        </thead>

        <tbody>
            <tr style="border: 1px solid #000;">
                <td  style="border: 1px solid #000;">{{ $employee->full_name }}</td>
                <td  style="border: 1px solid #000;">{{  $total_days }}</td>
                <td  style="border: 1px solid #000;">{{  $absent_days }}</td>
                <td  style="border: 1px solid #000;">{{   $weekend_days }}</td>
                <td  style="border: 1px solid #000;">{{  $holiday_working_count }}</td>
                <td  style="border: 1px solid #000;">{{   $working_days }}</td>
                <td  style="border: 1px solid #000;">{{  $extra_days_count    }}</td>
            </tr>
        </tbody>
       
    </table>
</body>
</html>