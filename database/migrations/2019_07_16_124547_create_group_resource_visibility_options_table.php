<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupResourceVisibilityOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_group_resource_visibility_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->integer('resource_id');
            $table->unsignedBigInteger('visibility_id');
            $table->timestamps();
/*
            $table->foreign('group_id')
                ->references('id')->on('custom_group_resources')
                ->onDelete('cascade');

            $table->foreign('resource_id')
                ->references('resourceid')->on('resources')
                ->onDelete('cascade');

            $table->foreign('visibility_id')
                ->references('id')->on('custom_resource_visibility_options')
                ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('custom_group_resource_visibility_options');
        Schema::enableForeignKeyConstraints();
    }
}
