<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToAvillHolidaysTable extends Migration
{
    public function up()
    {
        Schema::table('avill_holidays', function (Blueprint $table) {
            $table->string('department', 100)->nullable()->after('country_code');
            $table->string('city', 100)->nullable()->after('department');
        });
    }

    public function down()
    {
        Schema::table('avill_holidays', function (Blueprint $table) {
            $table->dropColumn(['department', 'city']);
        });
    }
}
