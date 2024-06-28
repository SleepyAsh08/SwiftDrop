<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdditionalInformationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('lastname')->after('name');
            $table->string('middle_initial', 2)->after('lastname')->nullable();
            $table->date('date_of_birth')->after('middle_initial')->nullable();
            $table->string('contact_number')->after('date_of_birth');
            $table->string('telephone_number')->after('contact_number')->nullable();
            $table->string('reason_of_disapproval')->after('telephone_number')->nullable();
            $table->json('user_photo')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('lastname');
            $table->dropColumn('middle_initial');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('contact_number');
            $table->dropColumn('telephone_number')->nullable();
            $table->dropColumn('reason_of_disapproval')->nullable();
            $table->dropColumn('photos')->nullable();
        });
    }
}
