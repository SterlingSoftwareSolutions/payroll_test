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
        Schema::create('attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            // $table->date('date');
            $table->date('date')->nullable();
            $table->integer('month_days');
            $table->integer('month_weekends');
            $table->integer('month_holidays');
            $table->integer('work_days');
            $table->integer('work_hours');
            $table->integer('absent_days'); 
            $table->integer('days_worked');
            $table->integer('days_worked_holiday');
            $table->integer('days_worked_weekend');
            $table->integer('days_worked_holiday_weekend');
            $table->integer('late_minutes');
            $table->integer('ot_minutes');
            $table->integer('annual_leaves_taken')->default(0);
           

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
        Schema::dropIfExists('attendance_reports');
    }
};
