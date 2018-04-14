<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableScForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This table is used to map needed forms for staff connect integration
		Schema::create('sc_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('form_id')->unsigned();
			$table->foreign('form_id')->references('id')->on('forms');
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
        //
		Schema::dropIfExists('sc_forms');

	}
}
