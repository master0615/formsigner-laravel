<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
	//
	protected $fillable = [
		 'name', 
		 'description', 
		 'page_number', 
		 'form_id', 
		 'file_width', 
		 'file_height',
		 'ext'
	];

	protected $touches = ['form'];

	public function fields()
	{
		return $this->hasMany('App\Field');
	}

	public function form()
	{
		return $this->belongsTo('App\Form');
	}

	public function path()
    {
        return action('StorageController@getFile', ['form', $this->id, $this->ext]);
	}
}
