<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_types', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        DB::table('position_types')->insert([
            ['position' => 'CEO', 'department' => 'Local', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'CFO', 'department' => 'Local', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Manager', 'department' => 'Local', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Web Designer', 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Web Developer', 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Android Developer', 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'IOS Developer', 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Team Leader', 'department' => 'IT', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position_types');
    }
}