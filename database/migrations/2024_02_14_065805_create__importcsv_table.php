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
        Schema::create('importcsvs', function (Blueprint $table) {
            $table->id();
            $table->string('workID');
            $table->date('csv_date');
            $table->time('punch_in');
            $table->time('punch_out')->default('00:00:00'); // Set default value to '00:00:00'
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
        Schema::dropIfExists('_importcsv');
    }
};
