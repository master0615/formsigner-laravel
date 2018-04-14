<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Sign;
use App\User;
use Illuminate\Http\Request;

class SignController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function set(Request $request, $userId)
    {

		$request->validate([
			'data' => 'required',
			//'file' => 'required|mimes:jpg,jpeg,png',
        ], [
            'data.required' => "Please draw signature!",
		]);


        $user = User::findOrFail($userId);

        $is_exist = isset($user['id']) && Sign::where("user_id", $user->id)->exists();

        if ( $is_exist ) {
            $sign = $user->sign;
        } else {
            $sign = new Sign();
        }

        $sign->user_id = $userId;
        $sign->data = $request->data;
        $sign->save();

        if ( !$request->hasFile('file') ) {
            $file = $request->file;
            $check = strpos($file, "base64");    

            $encoded_image = substr($file, strpos($file, ",") + 1);
            $decoded_image = base64_decode($encoded_image);

            //$targetFile = public_path() . "\storage\images\sign\\{$sign->id}.png";
            //$success = file_put_contents($targetFile,  $decoded_image);

            $targetFile = SIGN_IMAGE_PATH . "/{$sign->id}.png";            
            $path = Storage::put( $targetFile,  $decoded_image);
        } else {
        // store on disk
            $targetFile = SIGN_IMAGE_PATH . "/{$sign->id}.png";
            $path = Storage::putFileAs( SIGN_IMAGE_PATH,  $request->file('file'), $targetFile);
        }
        $sign->path = $sign->path();

        return response()->json( $sign );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sign  $sign
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $sign = $user->sign;

        if ($sign) {
            $sign->path = $sign->path();
        }
        
        return response()->json( $sign );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sign  $sign
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $sign = $user->sign;
        if ($sign) {
            $delete = array(
                str_replace('public/', '', SIGN_IMAGE_PATH) . "/{$sign->id}.png",
            );
    
            // Delete related files
            Storage::disk('public')->delete($delete);
    
            $sign->delete();
        }

		return response()->json( null, 204 );
    }
}
