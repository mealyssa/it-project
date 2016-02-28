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
use App\User;
use Auth;
use Session;
use Hash;
use App\Http\Requests\Registration_Request;

class Account_Controller extends Controller{
    
    public function postSignIn(){
        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(['username' => $username, 'password' => $password]))
        {
            return redirect('/home');
            
            
        }
        else{
            Session::flash('error', "Username or password incorrect.");
            return redirect('/');
        }
    }
     
    public function logout(){
        Auth::logout();
        Session::flash('from_logout', "Thank you for using Extrack");
        return redirect('/');
    }
    public function postRegister(Registration_Request $request){
       
        $applicant_data = new User();
        $applicant_data['first_name'] = $request->input('first_name');
        $applicant_data['last_name']  = $request->input('last_name');
        $applicant_data['username']   = $request->input('username');
        $applicant_data['password']   = Hash::make($request->input('password'));
        $applicant_data['contact_number']  = $request->input('contact_number');
        $applicant_data['email']   = $request->input('email');
        $savedata = $applicant_data->save();
        Session::flash('registered', "Account Sucessfully Registered ");
       return redirect('/');
        
       
    }
}