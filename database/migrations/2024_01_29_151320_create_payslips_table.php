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
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('br_allowance', 10, 2);
            $table->decimal('fixed_allowance', 10, 2);
            $table->decimal('attendance_allowance', 10, 2);

            // Deductions
            $table->decimal('no_pay_leave_deduction', 10, 2);
            $table->decimal('late_deduction', 10, 2);

            $table->decimal('employee_epf', 10, 2);
            $table->decimal('paye', 10, 2);
            $table->decimal('stamp_duty', 10, 2);

            $table->decimal('advance', 10, 2);
            $table->decimal('loan', 10, 2);
            $table->decimal('other_deductions', 10, 2);

            // Extra paymments
            $table->decimal('holiday_payment', 10, 2);
            $table->decimal('extra_days_payment', 10, 2);
            $table->decimal('incentives', 10, 2);
            $table->decimal('ot', 10, 2);
            $table->decimal('other_increments', 10, 2);

            // Company EPF & ETF
            $table->decimal('company_epf', 10, 2);
            $table->decimal('etf', 10, 2);
            $table->decimal('net_salary', 10, 2);

            // Bank account details
            $table->string('account_name');
            $table->string('account_number');
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
