<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->date('usage_date');
            $table->bigInteger('download_mb')->default(0); // Download in MB
            $table->bigInteger('upload_mb')->default(0); // Upload in MB
            $table->bigInteger('total_mb')->default(0); // Total usage in MB
            $table->decimal('session_duration_hours', 8, 2)->default(0); // Session time in hours
            $table->string('ip_address')->nullable();
            $table->string('device_mac')->nullable();
            $table->boolean('is_peak_hours')->default(false);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['client_id', 'usage_date']);
            $table->index(['subscription_id', 'usage_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_usages');
    }
}
