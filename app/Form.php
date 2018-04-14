<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
	//
	protected $fillable = [
		'name', 
		'pages', 
		'icon', 
		'share_all', 
		'user_id', 
		'description',
	];
	//protected $touches = ['user'];

	public function files()
	{
		return $this->hasMany('App\File');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function thumb()
	{
        return action('StorageController@getFile', ['form', $this->id, 'jpg', 1]);
	}

}
