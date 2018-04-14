<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Initial extends Model
{
    protected $fillable = [
        'user_id', 
        'data'
   ];

   public function user()
   {
    $this->belongsTo('App\User');
   } 

   public function path()
   {
       return action('StorageController@getFile', ['initial', $this->id, 'png']);
   }   
}
