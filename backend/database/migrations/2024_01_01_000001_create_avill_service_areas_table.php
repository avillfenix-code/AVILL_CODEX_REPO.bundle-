<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvillServiceAreasTable extends Migration
{
    public function up()
    {
        Schema::create('avill_service_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->json('map_polygon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('avill_service_areas'); }
}
