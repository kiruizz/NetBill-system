<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 8, 2);
            $table->decimal('setup_fee', 8, 2)->default(0.00);
            $table->integer('speed_mbps'); // Internet speed in Mbps
            $table->integer('data_limit_gb')->nullable(); // Null for unlimited
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'annually'])->default('monthly');
            $table->boolean('is_unlimited')->default(false);
            $table->enum('plan_type', ['residential', 'business', 'corporate'])->default('residential');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('features')->nullable(); // Additional features as JSON
            $table->integer('max_devices')->default(1);
            $table->decimal('overage_rate', 6, 4)->nullable(); // Rate per GB over limit
            $table->timestamps();
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
        Schema::dropIfExists('service_plans');
    }
}
