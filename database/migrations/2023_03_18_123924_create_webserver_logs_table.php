<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webserver_logs', function (Blueprint $table) {
            $table->id();
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('remote_addr')->nullable();
            $table->string('remote_user')->nullable();
            $table->dateTimeTz('time_local')->nullable();
            $table->string('request')->nullable();
            $table->smallInteger('status')->nullable();
            $table->integer('body_bytes_sent')->nullable();
            $table->string('referer')->nullable();
            $table->string('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webserver_logs');
    }
};
