<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_group_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->bigInteger('user_id');
            $table->timestamps();
/*
            $table->foreign('group_id')
                ->references('id')->on('custom_groups')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('userid')->on('users')
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
        Schema::dropIfExists('custom_group_users');
        Schema::enableForeignKeyConstraints();
    }
}
