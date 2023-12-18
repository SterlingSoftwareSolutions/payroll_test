<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *   $table->foreignId('department_id')->references('id')->on('departments');
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            // $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
          
            $table->date('date');
            $table->time('punch_in');
            $table->time('punch_out');
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
        Schema::dropIfExists('attendances');
    }
    
};
