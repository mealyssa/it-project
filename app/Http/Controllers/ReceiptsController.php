<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Session;
use Hash;
use Auth;
use App\Receipts;

class ReceiptsController extends Controller
{
   public function upload(){
        $isEmpty;
        
        if(empty(Input::get('activities'))){
          $isEmpty = true; 
        }
        else{
          $isEmpty = false;
        }

        if($isEmpty) {

          Session::flash('empty_act','Uploaded receipts does not belong any activities');
          return redirect('/home');
        }
        else{

          $receipts = new Receipts;
          $activities = Input::all();

          $folder = "assets/receiptsImg/";
          $filename = Input::file('fileUpload')->getClientOriginalName();
          $extension = Input::file('fileUpload')->getClientOriginalExtension();
          $hashedFilename = Hash::make($filename);
          Input::file('fileUpload')->move($folder,$hashedFilename.'.'.$extension);
      
          $receipts->name = Input::get('filenameTxtbox');
          $receipts->path = Hash::make($hashedFilename);
          $receipts->event = $activities['activities'];
          $receipts->user_id = Auth::user()->id;

          $receipts->save();
          

        
         



        }

      
   }
}
