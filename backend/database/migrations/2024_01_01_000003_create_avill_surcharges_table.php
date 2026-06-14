<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvillSurchargesTable extends Migration
{
    public function up()
    {
        Schema::create('avill_surcharges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('service_type', 80);
            $table->string('vehicle_mode', 80);
            $table->decimal('amount', 20, 4)->default(0);
            $table->boolean('applies_night')->default(false);
            $table->boolean('applies_sunday')->default(false);
            $table->boolean('applies_holiday')->default(false);
            $table->time('night_starts_at')->nullable();
            $table->time('night_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('avill_surcharges'); }
}
