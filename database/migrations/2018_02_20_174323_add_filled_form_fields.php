<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilledFormFields extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('filled_forms', function (Blueprint $table) {
			$table->integer( 'form_id' )->references( 'id' )->on( 'forms' );
			$table->integer( 'user_id' )->references( 'id' )->on( 'users' );
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
	}
}
