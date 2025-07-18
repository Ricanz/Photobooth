<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['single', 'double'])->default('single');
            $table->string('image')->nullable();
            $table->string('border_top')->nullable();
            $table->string('border_bottom')->nullable();
            $table->string('border_right')->nullable();
            $table->string('border_left')->nullable();
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
        Schema::dropIfExists('frames');
    }
};
