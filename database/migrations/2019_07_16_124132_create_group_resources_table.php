<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_group_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->integer('resource_id');
            $table->timestamps();
/*
            $table->foreign('group_id')
                ->references('id')->on('custom_groups')
                ->onDelete('cascade');

            $table->foreign('resource_id')
                ->references('resourceid')->on('resources')
                ->onDelete('cascade');
                */
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
        Schema::dropIfExists('custom_group_resources');
        Schema::enableForeignKeyConstraints();
    }
}
