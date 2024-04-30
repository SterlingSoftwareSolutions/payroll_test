<style type="text/css">

	/* CSS styles for the PDF */
	body {
		font-family: Arial, sans-serif;
		color: #000000;
		font-size: 15px;
		line-height: 0.5;
		padding: 45px;
	}

	.header-line, .footer-line {
        border-top: 2px solid red;
    }

	table {
		width: 100%;
		margin-bottom: 10px;
	}

	th,
	td {
		padding: 8px;
		/* border-bottom: 1px solid #ddd; */
		text-align: left;
	}

	th {
		background-color: #f2f2f2;
	}
 
/* .left-content{
	float:left;
}
.right-content {
    float: right;
} */

.container {
    display: flex; /* Use flexbox to align items */
}

.left-content {
    flex: 0 0 30%; /* Set the width of the left content */
	float:left;
}

.right-content {
    flex: 1; /* Let the right content take up the remaining space */
	float:right;
}


.employee-details {
    clear: both;
    margin-top: 20px;
}

.employee-table {
    width: 50%;
    float: left;
    margin-bottom: 20px;
}

.employee-table table {
    width: 100%;
}

.employee-table td {
    padding: 5px;
}

.employee-table td:first-child {
    width: 30%;
    font-weight: bold;
}

.employee-table td:nth-child(2) {
    width: 70%;
}



	.payslip-header {
		clear: both;
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
/* .bank-details-header {
		text-align: center;
		margin-top: 50px;
		font-weight: bold;
		font-size: 14px;
		color: #000000;
	} */

	.bank-details-header {
    text-align: center;
    margin-top: 20px; /* Adjust margin as needed */
    font-weight: bold;
    font-size: 13px; /* Adjust font size as needed */
    color: #000000;
}

.bank-details-table {
    width: 100%;
    margin-top: 10px; /* Adjust margin as needed */
}

.bank-details-table td {
    padding: 8px;
    /* border-bottom: 1px solid #ddd; */
    text-align: left;
	font-size:12px;
}

.bank-details-table td:first-child {
    width: 30%; /* Adjust width as needed */
    /* font-weight: bold; Bolden the first column */
}

.footer {
    position: fixed;
    bottom: 20px;
    left: 20px; /* Align to the left */
    right: 20px; /* Align to the right */
	display: flex; /* Use flexbox */
    justify-content: space-between; /* Distribute items equally on the line */
    align-items: center; /* Center items vertically */
}

.footer p {
    margin: 5px;
    font-size: 14px;
}

.footer p:last-child {
    text-align: right; /* Align the last paragraph to the right */
}



</style>


<body>

<div class="header-line"></div>
<br>

<div class="container">
    <div class="left-content">
        <img src="../public/pdflogo/sterlinglogo.jpg" alt="Logo" style="max-width: 30%; height: auto;">
    </div>
    <div class="right-content">
        <p><span style="color: red; font-size: 24px;">Sterling BPO Solutions (PVT) LTD</span></p>
        <p>No.12A, 005 Church Rd, Seeduwa 11410</p>
    </div>
</div>


<br>

<!-- First Employee Details Table -->
<div class="employee-details">
    <div class="employee-table">
    <table style="font-size: 14px;">
            <tr>
                <td style="width:5%;">Name:</td>
                <<td style="text-align: right;">{{ $payslip->employee->full_name }}</td>
            </tr>
            <tr>
                <td style="width:5%;">Department:</td>
                <<td style="text-align: right;">{{ $payslip->employee->department->department }}</td>
            </tr>
            <tr>
                <td style="width:5%;">Designation:</td>
				<td style="text-align: right;">{{ $job_title_name}}</td>
            </tr>
        </table>
    </div>
    <div class="employee-table">
        <table style="font-size: 14px;">
            <tr>
                <td style="width:40%;">ETF No:</td>
                <<td style="text-align: right;">{{$payslip->employee->etf_no}}</td>
            </tr>
            <tr>
                <td style="width:20%;">NIC No:</td>
                <<td style="text-align: right;">{{ $payslip->employee->nic }}</td>
            </tr>
        </table>
    </div>
</div>

	<!-- Payslip Header -->
	<div class="payslip-header">
		PAYSLIP FOR {{ strtoupper($payslip->date->format('F Y'))}}
	</div>


	<table style='font-size: 13px'>

		<tr>
			<td style="width: 20%;">Basic Salary</td>
			<td style="text-align: right;">{{$payslip->basic_salary ? number_format($payslip->basic_salary, 2) : '-'}}
			</td>
		</tr>
		<tr>
			<td style="width: 20%;">BR Allowance</td>
			<td style="text-align: right;">{{$payslip->br_allowance ? number_format($payslip->br_allowance, 2) : '-'}}
			</td>
		</tr>
		<tr>
			<td style="width: 20%;">Other</td>
			<td style="text-align: right;">-</td>
		</tr>
		<tr>
			<td style="width: 20%;">No Pay Leave</td>
			<td style="text-align: right;">{{$payslip->no_pay_leave_deduction ?
				number_format($payslip->no_pay_leave_deduction, 2) : '-'}}</td>
		</tr>
		<tr>
			<td style="width: 20%;">Late Deduction</td>
			<td style="text-align: right;">{{$payslip->late_deduction ? number_format($payslip->late_deduction, 2) :
				'-'}}</td>
		</tr>
		<tr>
			<td style="width: 20%;">Total Basic Pay</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 20%;">Total For EPF</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>
		<tr>
			<td style="width: 20%;">Earnings for P.A.Y.E</td>
			<td style="text-align: right;">{{number_format($payslip->basic_salary + $payslip->br_allowance +
				$payslip->fixed_allowance - $payslip->no_pay_leave_deduction - $payslip->late_deduction, 2)}}</td>
		</tr>



		<tr style="width: 50%">
			<td style="width: 20%;"><b>Incentive</b></td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Incentive - 1</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->incentives ? number_format($payslip->incentives, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Increments - 2</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->other_increments ? number_format($payslip->other_increments, 2) :
				'-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 50%; padding-left: 33.33%;">Attendance Allowance</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->attendance_allowance ?
				number_format($payslip->attendance_allowance, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">OT</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->ot ? number_format($payslip->ot, 2) : '-'}}</td>
		</tr>
		<tr style="width: 50%">
			<td style="width: 20%; padding-left: 33.33%;">Holiday Payment</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->holiday_payment ?
				number_format($payslip->holiday_payment, 2) : '-'}}</td>
		</tr>

		<tr style="width: 50%">
			<td style="width: 50%; padding-left: 33.33%;">Extra Day Payment</td>
			<td style="text-align: right; padding-right: 40%;">{{$payslip->extra_days_payment ?
				number_format($payslip->extra_days_payment, 2) : '-'}}</td>
		</tr>

		<br>
		<tr style="width: 50%">
			<td style="width: 20%;"><b>Deductions</b></td>
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
	</table>

	<table style="font-size:13px;">

		<tr>
			<td style="width: 20%;">EPF 12%</td>
			<td style="text-align: right;">{{$payslip->company_epf ? number_format($payslip->company_epf, 2) : '-'}}
			</td>
		</tr>
		<tr>
			<td style="width: 20%;">ETF 3%</td>
			<td style="text-align: right;">{{$payslip->etf ? number_format($payslip->etf, 2) : '-'}}
			</td>
		</tr>

	</table>

	
		
		<!-- New section for bank details -->
<div class="bank-details-header">
    BANK DETAILS
</div>

<table class="bank-details-table">
    <tr>
        <td style="width: 70%;">BANK :</td>
        <td style="text-align: right;">{{$payslip->bank_name ?? '-'}}</td>
    </tr>
    <tr>
        <td style="width: 70%;">A/C NO :</td>
        <td style="text-align: right;">{{$payslip->account_number ?? '-'}}</td>
    </tr>
    <tr>
        <td style="width: 70%;">BRANCH :</td>
        <td style="text-align: right;">{{$payslip->branch ?? '-'}}</td>
    </tr>
</table>






		<div class="footer-line"></div>

	<!-- Footer -->

	<div class="footer">
        <p>Date Generated: {{ $currentDate }}</p>
        <p>This is a system-generated pay slip</p>
    </div>

{{-- 	
<div class="footer">
    <p>Date Generated: <span id="dateGenerated"></span></p>
    <p>This is a system-generated pay slip</p>
</div>



// Now pass this HTML to DOMPDF for rendering into a PDF
// (assuming you have DOMPDF set up correctly)
?>
 --}}

		

	</body>


	
