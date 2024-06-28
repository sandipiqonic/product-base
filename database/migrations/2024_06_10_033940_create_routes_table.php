<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // The name of the route
            $table->string('method')->nullable(); // The HTTP method (e.g., GET, POST)
            $table->string('uri')->nullable(); // The URI of the route
            $table->string('controller')->nullable(); // The controller method handling the route, if any
            $table->string('middleware')->nullable(); // Middleware applied to the route, if any
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
