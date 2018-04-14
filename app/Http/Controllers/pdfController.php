<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use PDF;

class pdfController extends Controller
{
    //

	public function generatePDF(Request $request) {
		$users = User::all();
		view()->share('users',$users);

		if($request->has('download')) {
			// pass view file
			$pdf = PDF::loadView('pdfview');
			// download pdf
			return $pdf->download('userlist.pdf');
		}
		return view('pdfview');
	}
}
