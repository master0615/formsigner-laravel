<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

define('PUBLIC_DIR', \Illuminate\Support\Facades\Storage::disk('public')->getDriver()
        ->getAdapter()
        ->getPathPrefix());
        
define('PROFILE_IMAGE_PATH', 'public/images/profile');
define('PROFILE_IMAGE_SIZE', '200');
define('PROFILE_IMAGE_THUMB_SIZE', '50');

define('FORM_IMAGE_PATH', 'public/images/form');
define('FOMR_IMAGE_THUMB_SIZE', '200');

define('SIGN_IMAGE_PATH', 'public/images/sign');
define('SIGN_IMAGE_WIDTH', '400'); 
define('SIGN_IMAGE_HEIGHT', '200');

define('INITIAL_IMAGE_PATH', 'public/images/initial');
define('INITIAL_IMAGE_WIDTH', '400'); 
define('INITIAL_IMAGE_HEIGHT', '200');

define('SEND_FORM_PATH', 'public/pdfs/send');

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
