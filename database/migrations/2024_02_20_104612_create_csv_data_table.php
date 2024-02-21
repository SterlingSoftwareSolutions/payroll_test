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
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->string('user')->default(''); 
            $table->integer('work_id');
            $table->string('card_no');
            $table->date('date');
            $table->time('punch_in');
            $table->string('in_out'); 

            $table->time('punch_out');
            $table->string('event_code');
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
        Schema::dropIfExists('csv_data');
    }
};
