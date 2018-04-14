<?php

namespace App;

use Illuminate\Database\Eloquent;

class StaffUser_User extends Eloquent
{
    /**
     * primaryKey 
     * 
     * @var integer
     * @access protected
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    protected $table = 'staffuser_user';
    protected $fillable = ['staff_user_id', 'user_id'];
}
