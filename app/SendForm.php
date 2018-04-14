<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendForm extends Model
{
	protected $fillable = [
        'sender', 
        'receivers', 
        'subject', 
        'body', 
        'file_name',
        'status',
   ];

   public function path()
   {
       return action('StorageController@getFile', ['send_form', $this->id, 'pdf']);
   }
}
