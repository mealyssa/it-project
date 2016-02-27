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

class Users_Login extends Controller{
    
    public function postLogin(){
        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(['username' => $username, 'password' => $password]))
        {
            return redirect('/home1');
            
            
        }
        else{
            return redirect('/index');
        }
    }
    
    
}