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
            $table->foreignId('employee_id')->constrained()->nullOnDelete();
            $table->double('basic_salary');
            $table->double('net_salary');
            $table->double('epf');
            $table->double('etf');
            $table->double('ot_pay');
            $table->double('late_deduction');
            $table->double('p_a_y_e');
            $table->double('stamp_duty');
            $table->double('holiday_payment');
            $table->double('advance_payment');
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
