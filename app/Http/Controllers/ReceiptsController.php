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
      $hashedFilename = str_replace("/", "-", $hashedFilename);
      Input::file('fileUpload')->move($folder,$hashedFilename.'.'.$extension);
      echo $hashedFilename;

      $receipts->name = Input::get('filenameTxtbox');
      $receipts->path = $hashedFilename;
      $receipts->event = $activities['activities'];
      $receipts->user_id = Auth::user()->id;

      $receipts->save(); 

      Session::flash('session_ImageName',$hashedFilename.'.'.$extension);
      return redirect('/expense/receipts/extractedData');
    }
  }

  public function expenses(){
    // $data = session::get('lines');
    // foreach($data as $line) {
    //   echo "<script>console.log('$line')</script>";
    // }
    
    $arrayData = session::get('arrayData');
     return view('pages.expenses',['extract'=>$arrayData]);
  }
}
