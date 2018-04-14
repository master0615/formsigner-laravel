<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Initial;
use App\User;
use Illuminate\Http\Request;

class InitialController extends Controller
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
			'data' => 'required'
        ], [
            'data.required' => "Please draw initial!",
		]);

        
        $user = User::findOrFail($userId);

        $is_exist = isset($user['id']) && Initial::where("user_id", $user->id)->exists();

        if ( $is_exist ) {
            $initial = $user->initial;
        } else {
            $initial = new Initial();
        }

        $initial->user_id = $userId;
        $initial->data = $request->data;
        $initial->save();

        if ( !$request->hasFile('file') ) {
            $file = $request->file;
            $check = strpos($file, "base64");    

            $encoded_image = substr($file, strpos($file, ",") + 1);
            $decoded_image = base64_decode($encoded_image);

            //$targetFile = public_path() . "\storage\images\sign\\{$sign->id}.png";
            //$success = file_put_contents($targetFile,  $decoded_image);

            $targetFile = INITIAL_IMAGE_PATH . "/{$initial->id}.png";            
            $path = Storage::put( $targetFile,  $decoded_image);
        } else {
        // store on disk
            $targetFile = INITIAL_IMAGE_PATH . "/{$initial->id}.png";
            $path = Storage::putFileAs( SIGN_IMAGE_PATH,  $request->file('file'), $targetFile);
        }

        $initial->path = $initial->path();

        return response()->json( $initial );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Initial  $initial
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $initial = $user->initial;

        if ($initial) {
            $initial->path =$initial->path();            
        }
        
        return response()->json( $initial );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Initial  $initial
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        $initial = $user->initial;

        if ($initial) {
            $delete = array(
                str_replace('public/', '', INITIAL_IMAGE_PATH) . "/{$initial->id}.png",
            );
    
            // Delete related files
            Storage::disk('public')->delete($delete);
            
            $initial->delete();
        }

		return response()->json( null, 204 );
    }
}
