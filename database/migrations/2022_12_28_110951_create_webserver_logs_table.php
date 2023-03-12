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
        Schema::create('webserver_logs', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->nullable();
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('remote_addr')->nullable();
            $table->string('remote_user')->nullable();
            $table->dateTime('time_local')->nullable();
            $table->string('request')->nullable();
            $table->string('status')->nullable();
            $table->string('body_bytes_sent')->nullable();
            $table->string('referer')->nullable();
            $table->string('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webserver_logs');
    }
};
