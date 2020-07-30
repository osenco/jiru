<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebHooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_hooks', function (Blueprint $table) {
            $table->id();
            $table->string('shortcode');
            $table->string('link');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('key')->nullable();
            $table->string('secret')->nullable();
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
        Schema::dropIfExists('web_hooks');
    }
}
