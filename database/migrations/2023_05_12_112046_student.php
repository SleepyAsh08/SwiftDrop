<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Student extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('scholarship')->nullable()->default(NULL);
            $table->double('GPA');
            $table->unsignedBigInteger('course_id')->nullable()->default(NULL);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('course_id')->references('id')->on('course');

            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('students');
    }
}
