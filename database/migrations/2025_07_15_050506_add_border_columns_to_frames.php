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
        Schema::table('frames', function (Blueprint $table) {
            $table->string('border_top')->nullable();
            $table->string('border_bottom')->nullable();
            $table->string('border_right')->nullable();
            $table->string('border_left')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nama_tabel', function (Blueprint $table) {
            $table->dropColumn(['border_top', 'border_bottom', 'border_right', 'border_left']);
        });
    }
};
