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
            $table->decimal('month_weekends', 10, 1);
            $table->integer('month_holidays');
            $table->decimal('work_days', 10, 1);
            $table->decimal('work_hours', 10, 1);
            $table->decimal('absent_days', 10, 1); 
            $table->decimal('days_worked', 10, 1);
            $table->decimal('days_worked_holiday', 10, 1);
            $table->decimal('days_worked_weekend', 10, 1);
            $table->decimal('days_worked_holiday_weekend', 10, 1);
            $table->decimal('late_minutes', 10, 2);
            $table->decimal('ot_minutes', 10, 2);
            $table->integer('half_day')->nullable();
            $table->integer('annual_leaves')->nullable();
            $table->decimal('annual_leaves_taken', 10, 1)->default(0);
            $table->integer('work_half_day')->nullable();
            $table->decimal('remove_late_minutes', 10, 2)->nullable();

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
