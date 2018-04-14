<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class User extends Authenticatable
{
	use HasApiTokens, Notifiable, AuthenticableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 'last_name', 'email', 'password', 'role','provider_id','active', 'provider_name', 'provider_company'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token'
	];

	public function fullname()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function hasRole($role)
	{
		$roles = explode('|', $role);
		return in_array($this->role, $roles);
	}


	public function oauthAccessToken()
	{
		return $this->hasMany('\App\OauthAccessToken');
	}

	public function canEditProfile($user_id)
	{
		if (Auth::id() == $user_id) {
			return 1;
		} elseif (Auth::user()->hasRole('owner|admin')) {
			// should admin be able to access other admins or owners?
			return 1;
		} else {}
		return 0;
	}

	public function canViewProfile($user_id)
	{
		if (Auth::id() == $user_id) {
			return 1;
		} elseif (Auth::user()->hasRole('owner|admin')) {
			// should admin be able to access other admins or owners?
			return 1;
		} else {}
		return 0;
	}

	public function forms()
	{
		return $this->hasMany('App\Form');
	}

	public function sign()
	{
		return $this->hasOne('App\Sign');
	}

	public function initial()
	{
		return $this->hasOne('App\Initial');
	}

	public function profile_photo()
	{
		return $this->hasOne('App\ProfilePhoto');
	}

	public function send_emails()
	{
		return $this->hasmany('App\SendEmail');
	}
}
