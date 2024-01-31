<style type="text/css">
	*{
		margin: 0;
		padding: 0;
	}
</style>
<div style="color: #7030A0; font-family: sans-serif; line-height: 1.5; padding: 45px;">
	<p>
		Sterling BPO Solutions (PVT) LTD<br/>
		No.12A, 005 Church Rd, Seeduwa 11410<br/>
		PAYSLIP FOR {{ strtoupper($payslip->date->format('F Y'))}}.
	</p>

	<br>

	<table style="width: 100%">
		<tr>
			<td style="width: 33.33%;">Name</td>
			<td>{{$payslip->employee->full_name}}</td>
		</tr>
		<br>
		<tr>
			<td style="width: 33.33%;">Basic Salary</td>
			<td style="text-align: right;">{{$payslip->basic_salary ? number_format($payslip->basic_salary, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">-</td>
			<td style="text-align: right;">-</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Other</td>
			<td style="text-align: right;">-</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">No Pay Leave</td>
			<td style="text-align: right;">{{$payslip->no_pay_leave_deduction ? number_format($payslip->no_pay_leave_deduction, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Late Deduction</td>
			<td style="text-align: right;">{{$payslip->late_deduction ? number_format($payslip->late_deduction, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Total Basic Pay</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance + $payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Total For EPF</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance + $payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Earnings for P.A.Y.E</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance + $payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<br>
		<tr style="width: 50%">
			<td style="width: 20%;">Attendance Allowance</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->attendance_allowance ? number_format($payslip->attendance_allowance, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%;">OT</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->ot ? number_format($payslip->ot, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%;">Incentive</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->incentives ? number_format($payslip->incentives, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%;">Holiday Payment</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->holiday_payment ? number_format($payslip->holiday_payment, 2) : '-'}}</td>
		</tr>
		<br>
		<tr style="width: 50%">
			<td style="width: 20%;">Deductions</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">E.P.F (8%)</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->employee_epf ? number_format($payslip->employee_epf, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">P.A.Y.E</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->paye ? number_format($payslip->paye, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Advance</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->advance ? number_format($payslip->advance, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Stamp Duty</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->stamp_duty ? number_format($payslip->stamp_duty, 2) : '-'}}</td>
		</tr>
		<br>
		<tr>
			<td style="width: 33.33%;">Net Salary</td>
			<td style="text-align: right;">{{$payslip->net_salary() ? number_format($payslip->net_salary(), 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">E.P.F 12%</td>
			<td style="text-align: right;">{{$payslip->company_epf ? number_format($payslip->company_epf, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">E.T.F 3%</td>
			<td style="text-align: right;">{{$payslip->etf ? number_format($payslip->etf, 2) : '-'}}</td>
		</tr>
		<br>
		<tr>
			<td>BANK</td>
			<td style="text-align: left;">{{$payslip->bank_name ?? '-'}}</td>
		</tr>
		<tr>
			<td>ACC NO</td>
			<td style="text-align: left;">{{$payslip->account_number ?? '-'}}</td>
		</tr>
		<tr>
			<td>BRANCH</td>
			<td style="text-align: left;">{{$payslip->branch ?? '-'}}</td>
		</tr>
	</table>
</div>

