<?php

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
        Schema::create('pay_slip', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->constrained()->nullOnDelete();
            // net_salary - computed field
            $table->boolean('approved')->default(false);

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

            // Extra paymments
            $table->double('holiday_payment');
            $table->double('incentives');

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
        Schema::dropIfExists('pay_slip');
    }
};