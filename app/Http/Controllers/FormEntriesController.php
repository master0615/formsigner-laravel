<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormEntry;

class FormEntriesController extends Controller
{
    //


	/** Parameters :
	 * fieldID
	 * userID
	 * fieldID
	 * Value
	*/
	public function store(Request $request) {
		$formEntry = new FormEntry();
		$formEntry->field_id = $request->field_id;
		$formEntry->user_id = $request->user_id;
		$formEntry->save();

		return response()->json( $formEntry );
	}
}
