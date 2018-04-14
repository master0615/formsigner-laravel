<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
            $table->string('receivers');
            $table->string('subject');
            $table->text('body');
            $table->string('file_name');
            $table->enum('status',['sign', 'create'])->default('sign');            
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
        Schema::dropIfExists('send_forms');
    }
}
