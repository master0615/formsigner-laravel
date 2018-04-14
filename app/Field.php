<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    //

	protected $fillable = [
		'name', 
		'description', 
		'value', 
		'type', 
		'file_id', 
		'x_rate', 
		'y_rate', 
		'width_rate', 
		'height_rate', 
		'length', 
		'group', 
		'is_only_check_group', 
		'is_mandatory', 
		'date_format',
		'select_options',
		'meta_info'
	];
	protected $touches = ['file'];

	public function file()
	{
		return $this->belongsTo('App\File');
	}
}
