<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		//
		Schema::create('files', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('ext');			
			$table->string('description')->nullable()->default('');
			$table->integer('page_number')->default(1);
			$table->integer('form_id')->unsigned();
			$table->foreign('form_id')->references('id')->on('forms');			
			$table->integer('file_width')->unsigned()->default(0);
			$table->integer('file_height')->unsigned()->default(0);
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
        Schema::dropIfExists('files');
    }
}