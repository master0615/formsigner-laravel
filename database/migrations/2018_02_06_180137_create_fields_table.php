<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'fields', function ( Blueprint $table ) {
			$table->increments( 'id' )->unsigned();
			$table->string( 'name' )->nullable();
			$table->string( 'type' ); //'text', 'radio', 'checkbox', 'signature', 'textarea', 'select'
			$table->string( 'group' )->nullable();
			$table->string( 'value' )->nullable();
			$table->integer( 'length' )->default(0);
			$table->integer( 'file_id' )->unsigned();
			$table->foreign( 'file_id' )->references( 'id' )->on( 'files' );
			$table->double( 'x_rate', 8, 8)->default(0);
			$table->double( 'y_rate', 8, 8)->default(0);
			$table->double( 'width_rate', 8, 8)->default(0);
			$table->double( 'height_rate', 8, 8)->default(0);
			$table->string( 'description')->nullable();
			$table->boolean( 'is_only_check_group')->nullable();
			$table->boolean( 'is_mandatory')->default(false);
			$table->string( 'date_format')->nullable();
			$table->string( 'select_options')->nullable();
			$table->string( 'meta_info')->nullable();
			$table->timestamps();
		} );

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'fields' );
	}
}
