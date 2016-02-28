<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use Auth;
use Session;

class Users_Logout extends Controller{
    
    public function index(){
        Auth::logout();
        Session::flash('from_logout', "Thank you for using Extrack");
        return redirect('/index');
    }
    
}