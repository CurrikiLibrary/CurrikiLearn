<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('group_type_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
/*
            $table->foreign('group_type_id')
                ->references('id')->on('custom_group_types')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')->on('custom_groups')
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
        Schema::dropIfExists('custom_groups');
        Schema::enableForeignKeyConstraints();
            
    }
}
