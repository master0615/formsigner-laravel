<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
    //
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
       return action('StorageController@getFile', ['sign', $this->id, 'png']);
   }
}
