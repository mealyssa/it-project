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

class Users_Registration extends Controller
{
   public function postRegister(){
   		
   		$user = new Users;
   		$user->first_name = Input::get('first_name');
   		$user->last_name = Input::get('last_name');
   		$user->username = Input::get('username');
   		$user->password = Input::get('password');
   		$user->contact_number = Input::get('contact_number');
   		$user->role = Input::get('role');
   		$user->email= Input::get('email');
   		$user->save();

   		$messages = [
   			'first_name.required' =>'Enter your firstname',
   			'first_name.alpha' =>'Letters only please',
   			'last_name.required'  =>'Enter your lastname',
   			'last_name.alpha'  =>'Letters only please',
   			'username.required'	  =>'Enter your username',
   			'password.required' =>'Enter your password',
   			'password.max:6' =>'Must be 6 characters or above',
   			'contact_number.required'  =>'Enter your contact_number',
   			'contact_number.numeric'  =>'Numbers only please',
   			'role.required'	  =>'Enter your role',
   			'email.required'=>'Enter your email',
   			'email.email'=>'Enter a valid email',
   			'email.unique:user'=>'Enter your email',
   		];
   		$rules =[
            'first_name'=>'required|alpha',
            'last_name' =>'required|alpha',
            'username'  =>'required',
            'password'  =>'required',
            'contact_number'=>'required|numeric',
            'role'          =>'required',
            'email'     =>'required|email|unique:user'
        ];
        
        $validator = Validator::make(Input::all(),$rules,$messages);
        if($validator->fails()){
        	return redirect()->back()->with('errors', $validator->messages());
        }
        else{


        	$user->save();
        	return Redirect::to('/home1');
        }
   	


   	}

}
