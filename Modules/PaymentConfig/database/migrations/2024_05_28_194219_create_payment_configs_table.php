<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentConfigsTable extends Migration
{
    public function up()
    {
        if(Schema::hasTable('payment_configs')) return;
        Schema::create('payment_configs', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->json('config');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_configs');
    }
}
