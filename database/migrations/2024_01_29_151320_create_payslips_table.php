<?php

use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class);
            $table->date('date');
            $table->dateTimeTz('approved_at')->nullable();

            // Gross salary
            $table->double('basic_salary');
            $table->double('br_allowance');
            $table->double('fixed_allowance');
            $table->double('attendance_allowance');

            // Deductions
            $table->double('no_pay_leave_deduction');
            $table->double('late_deduction');

            $table->double('employee_epf');
            $table->double('paye');
            $table->double('stamp_duty');

            $table->double('advance');
            $table->double('loan');
            $table->double('other_deductions');

            // Extra paymments
            $table->double('holiday_payment');
            $table->double('extra_days_payment');
            $table->double('incentives');
            $table->double('ot');
            $table->double('other_increments');

            // Company EPF & ETF
            $table->double('company_epf');
            $table->double('etf');

            // Bank account details
            $table->string('account_name');
            $table->double('account_number');
            $table->string('bank_name');
            $table->string('branch');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payslips');
    }
};
