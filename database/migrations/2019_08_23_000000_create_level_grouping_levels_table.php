<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


// Pivot table between level groupings and levels
class CreateLevelGroupingLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_level_grouping_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level_grouping_id');
            $table->integer('level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_level_grouping_levels');
    }
}
