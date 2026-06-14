<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvillHolidaysTable extends Migration
{
    public function up()
    {
        Schema::create('avill_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->date('date');
            $table->string('country_code', 5)->default('CO');
            $table->string('applies_to', 80)->default('todos');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('avill_holidays'); }
}
