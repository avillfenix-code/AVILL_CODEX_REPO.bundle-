<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvillManualFaresTable extends Migration
{
    public function up()
    {
        Schema::create('avill_manual_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_area_id')->constrained('avill_service_areas')->cascadeOnDelete();
            $table->foreignId('destination_area_id')->constrained('avill_service_areas')->cascadeOnDelete();
            $table->string('service_type', 80);
            $table->string('vehicle_mode', 80);
            $table->enum('pricing_mode', ['tarifa_fija', 'cotizacion_manual'])->default('tarifa_fija');
            $table->decimal('base_amount', 20, 4)->nullable();
            $table->decimal('minimum_amount', 20, 4)->nullable();
            $table->decimal('management_surcharge_amount', 20, 4)->default(0);
            $table->decimal('night_surcharge_amount', 20, 4)->default(0);
            $table->decimal('holiday_surcharge_amount', 20, 4)->default(0);
            $table->decimal('rain_surcharge_amount', 20, 4)->default(0);
            $table->decimal('additional_km_amount', 20, 4)->default(0);
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('avill_manual_fares'); }
}
