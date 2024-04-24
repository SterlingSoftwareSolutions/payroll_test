<style type="text/css">
	/* CSS styles for the PDF */
	body {
		font-family: Arial, sans-serif;
		color: #000000;
		font-size: 18px;
		line-height: 1.5;
		padding: 45px;
	}

	table {
		width: 100%;
		margin-bottom: 20px;
	}

	th,
	td {
		padding: 8px;
		border-bottom: 1px solid #ddd;
		text-align: left;
	}

	th {
		background-color: #f2f2f2;
	}

	.logo {
		float: left;
		margin-left: 20px;
	}

	.right-content {
		float: right;
		margin-top: 20px;
	}

	.employee-details {
		float: right;
		margin-top: 20px;
		margin-left: 20px;
	}

	.employee-table {
		width: 50%;
		float: left;
	}

	.payslip-header {
		clear: both;
		text-align: center;
		margin-top: 50px;
		font-weight: bold;
		font-size: 18px;
		color: #000000;
	}

	.bank-details-header {
		text-align: center;
		margin-top: 50px;
		font-weight: bold;
		font-size: 14px;
		color: #000000;
	}

	.highlight-row {
		background-color: #ffc107;
		/* Yellow color as an example */
		font-weight: bold;
		/* Make text bold */
	}
</style>
</head>

<body>
	<div class="logo">
		<img src="" alt="Logo" width="150">
	</div>

	<div class="right-content">
		<p><span style="color: red;">Sterling BPO Solutions (PVT) LTD</span></p>
		<p>No.12A, 005 Church Rd, Seeduwa 11410</p>
	</div>

	<div class="employee-details">
		<div class="employee-table">
			<table>
				<!-- Employee Details -->
				<tr>
					<td style="width:10%;">Name:</td>
					<td>Gamika Punsisi</td>
				</tr>
				<tr>
					<td style="width:10%;">Department:</td>
					<td>IT</td>
				</tr>
				<tr>
					<td style="width:10%;">Designation:</td>
					<td>ASE</td>
				</tr>
			</table>
		</div>
		<div class="employee-table">
			<table>

				<tr>
					<td style="width:10%;">ETF No:</td>
					<td>085</td>
				</tr>
				<tr>
					<td style="width:10%;">NIC No:</td>
					<td>920782892V</td>
				</tr>
			</table>
		</div>
	</div>


	<!-- Payslip Header -->
	<div class="payslip-header">
		PAYSLIP FOR {{ strtoupper($payslip->date->format('F Y'))}}
	</div>


	<table>

		<tr>
			<td style="width: 33.33%;">Basic Salary</td>
			<td style="text-align: right;">{{$payslip->basic_salary ? number_format($payslip->basic_salary, 2) : '-'}}
			</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">BR Allowance</td>
			<td style="text-align: right;">{{$payslip->br_allowance ? number_format($payslip->br_allowance, 2) : '-'}}
			</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Other</td>
			<td style="text-align: right;">-</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">No Pay Leave</td>
			<td style="text-align: right;">{{$payslip->no_pay_leave_deduction ?
				number_format($payslip->no_pay_leave_deduction, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Late Deduction</td>
			<td style="text-align: right;">{{$payslip->late_deduction ? number_format($payslip->late_deduction, 2) :
				'-'}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Total Basic Pay</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Total For EPF</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Earnings for P.A.Y.E</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 33.33%;">Other Increments</td>
			<td style="text-align: right;">{{$payslip->other_increments ? number_format($payslip->other_increments, 2) :
				'-'}}</td>
		</tr>
		<br>

		<tr style="width: 50%">
			<td style="width: 20%;">Incentive</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->incentives ?
				number_format($payslip->incentives, 2) : '-'}}</td>
		</tr>

		<tr style="width: 50%">
			<td style="width: 20%;">Attendance Allowance</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->attendance_allowance ?
				number_format($payslip->attendance_allowance, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%;">OT</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->ot ? number_format($payslip->ot, 2) : '-'}}
			</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%;">Holiday Payment</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->holiday_payment ?
				number_format($payslip->holiday_payment, 2) : '-'}}</td>
		</tr>


		<br>
		<tr style="width: 50%">
			<td style="width: 20%;">Deductions</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">E.P.F (8%)</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->employee_epf ?
				number_format($payslip->employee_epf, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">P.A.Y.E</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->paye ? number_format($payslip->paye, 2) :
				'-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Advance</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->advance ? number_format($payslip->advance, 2)
				: '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Loan</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->loan ? number_format($payslip->loan, 2) :
				'-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Stamp Duty</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->stamp_duty ?
				number_format($payslip->stamp_duty, 2) : '-'}}</td>
		</tr>

		<br>


		<tr class="highlight-row">
			<td style="width: 33.33%;">Net Salary</td>
			<td style="text-align: right;">{{$payslip->net_salary ? number_format($payslip->net_salary, 2) : '-'}}</td>
		</tr>



		<!-- Bank Details Header -->
		<div class="bank-details-header">
			BANK DETAILS
		</div>


		<table>
			<tr>
				<td style="width: 50%;">BANK</td>
				<td style="text-align: right;">{{$payslip->bank_name ?? '-'}}</td>
			</tr>
			<tr>
				<td style="width: 50%;">A/C NO</td>
				<td style="text-align: right;">{{$payslip->account_number ?? '-'}}</td>
			</tr>
			<tr>
				<td style="width: 50%;">BRANCH</td>
				<td style="text-align: right;">{{$payslip->branch ?? '-'}}</td>
			</tr>
		</table>