<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Session;
use Hash;
use Auth;
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
          $events = new Events;
          $receipts = new Receipts;
 
          $events-> = Input::get('filenameTxtbox');
          $folder = "assets/receiptsImg/";
          $location = $folder.basename($_FILES['fileUpload']['name']);
          $file_tmp = $_FILES['fileUpload']['tmp_name'];
          $filename = $_FILES['fileUpload']['name'];

          $userid = Auth::user()->id;
          $hashedFilename = Hash::make($filename);
          Input::file('fileUpload')->move($folder,$hashedFilename);



        }

      
   }
}
