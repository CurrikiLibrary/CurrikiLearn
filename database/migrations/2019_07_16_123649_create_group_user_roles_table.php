<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_group_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->bigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
/*
            $table->foreign('group_id')
                ->references('id')->on('custom_groups')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('userid')->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')->on('custom_user_roles')
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
        Schema::dropIfExists('custom_group_user_roles');
        Schema::enableForeignKeyConstraints();
    }
}
