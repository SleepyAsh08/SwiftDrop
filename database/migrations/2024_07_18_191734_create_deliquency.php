<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliquency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliquency', function (Blueprint $table) {
            //
            $table->id();
            $table->unsignedBigInteger('reported_by');
            $table->unsignedBigInteger('committed_by');
            $table->string('reason');
            $table->unsignedBigInteger('last_modified_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reported_by')->references('id')->on('users');
            $table->foreign('committed_by')->references('id')->on('users');
            $table->foreign('last_modified_by')->references('id')->on('users');

            $table->index(['reported_by', 'committed_by', 'last_modified_by']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliquency', function (Blueprint $table) {
            //
        });
    }
}
