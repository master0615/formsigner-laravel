<?php

namespace App\Http\Controllers;

use App\SendEmail;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    public function index($userId) {
        $send_emails = SendEmail::where( "user_id", $userId )->get();
        return response()->json($send_emails, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithParams(Request $request, $userId)
    {
		$totalSendEmails = SendEmail::where( "user_id", $userId )->count();

		$send_emails = SendEmail::where( "user_id", $userId )->get();

		$totalFiltered = $totalSendEmails; 

		$page_number = empty($request->page_number) ? 0: $request->page_number;
		$page_size = empty($request->page_size) ? 5: $request->page_size;
		$order = empty($request->order) ? 'created_at' : $request->order;
		$dir = empty($request->dir) ? 'desc': $request->dir;
		$offset = $page_number * $page_size;

        if(empty($request->filter)) {            
			$send_emails = SendEmail::where( "user_id", $userId )
						->offset($offset)
                        ->limit($page_size)
                        ->orderBy($order,$dir)
                        ->get();
        } else {
            $filter = $request->filter; 

            $send_emails = SendEmail::where( "user_id", $userId )
                            ->where("email", 'like', "%{$filter}%")
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

			$totalFiltered = SendEmail::where( "user_id", $userId )
                            ->where("email", 'like', "%{$filter}%") 
                            ->count();
        }		

        $response['data'] = $send_emails;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

		return response()->json( $response, 200 );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:send_emails|min:5',
            'user_id' => 'required',
        ], [
            'email.unique' => "The email address is already registered on the system"
        ]);

        $send_email = new SendEmail();
        $send_email->email = strtolower($request->email);
        $send_email->user_id = $request->user_id;
        $send_email->save();

        return response()->json([
                'data' => $send_email,
                'message' => " created.",
            ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SendEmail  $sendEmail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$request->validate([
			'user_id' => 'required',
            'email'  => 'required|email|unique:send_emails|min:5'
        ], [
            'email.unique' => "The email address is already registered on the system"
		]);
		

		$send_email = SendEmail::findOrFail( $id );

		if ( $request->user_id != $send_email->user_id ){
            throw new \App\Exceptions\InvalidAccessException();
		}

		$send_email->email   = $request->email;
		$send_email->user_id = $request->user_id;
		$send_email->save();

		return  response()->json([
                    'data' => $send_email,
                    'message' => " updated.",
                ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SendEmail  $sendEmail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $send_email = SendEmail::findOrFail( $id );
        $send_email->delete();
        return response()->json($send_email, 204);
    }
}
