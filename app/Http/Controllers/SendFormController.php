<?php

namespace App\Http\Controllers;

use Mail;
use Log;
use App\SendForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendFormMail;

class SendFormController extends Controller 
{
    public function send(Request $request) {

        $request->validate([
			'sender' => 'required|email|min:5',
			'receiver.*' => 'required|email|min:5',
			//'pdf' => 'required',
        ], [
            'sender.required' => "please enter sender's email address",
        ]);

        //$file = $request->file('pdf');
        // get extension
        //    $ext = $file->getClientOriginalExtension();
        //     return response()->json($request->pdf);

        //     if (!in_array($ext, ['pdf'])) {
        //         throw new \App\Exceptions\InvalidMimeException();
        //     }    


        
        $title = "Hi";
        switch ($request->status) {
            case 'sign':
                $message = "<p>I signed the form.</p><p>please review my form.</p>";
                break;
            case 'create':
                $message = "<p>I created the form.</p><p>please review my form.</p>"; 
                break;
            default:
                $message = "<p>I made the form.</p><p>please review my form.</p>"; 
                break;
        }

        // store in db
        $new_send_form = new SendForm();
        $new_send_form->sender = $request->sender;
        $new_send_form->receivers = implode(",", $request->receiver);
        $new_send_form->subject = $title;
        $new_send_form->body = $message;
        $new_send_form->file_name = $request->file_name;
        $new_send_form->status = $request->status;                
        $new_send_form->save();


        $targetFile = "{$new_send_form->id}.pdf";

        $path = Storage::putFileAs( SEND_FORM_PATH,  $request->file('pdf'), $targetFile);


        foreach ($request->receiver as $i => $receiver) {
            $to = $receiver;
            //$sent = Mail::queue(new SendFormMail(), ['title' => $title, 'content' => $message], function ($message) use ($request, $to, $path)
            $sent = Mail::send('emails.send', ['title' => $title, 'content' => $message], function ($message) use ($request, $to, $path)
            {
                $message->from($request->sender);
                $message->attach($request->file('pdf'));
                //$message->attachData($request->pdf, "{$request->file_name}.pdf", ['mime' => 'application/pdf']);
                $message->to($to);
            });
        }
        if (!$sent) {
            return response()->json(['message' => 'do something wrong' ], 401);
        }
        return response()->json(['Request completed'], 200);
    }
}
