<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Field;
use App\FieldMeta;

class FieldController extends Controller {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		//
		$field = new Field();
		$input = $request->all();
		$field->fill( $input )->save();

		return response()->json( $field, 201 );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		$field = Field::findOrFail( $id );

		return response()->json( $field, 200 );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
		$field = Field::findOrFail( $id );
		$field->update($request->all());
		$field->save();

		return response()->json( $field, 200 );

	}

	public function addSelectOptions( Request $request ) {

		// Find selected field
		$fieldMeta           = new FieldMeta();
		$fieldMeta->value    = $request->value;
		$fieldMeta->field_id = $request->id;
		$fieldMeta->type     = $request->type; // Defaults to "select_option"
		$fieldMeta->save();

		return response()->json( $fieldMeta );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		//
		// delete
		$field = Field::findOrFail( $id );
		$field->delete();

		return response()->json( null, 204 );
	}
}
