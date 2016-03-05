<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
class ReceiptsController extends Controller
{
   public function upload(){
       

        $folder = "assets/receiptsImg/";
        $location = $folder.basename($_FILES['fileUpload']['name']);
        $file_tmp = $_FILES['fileUpload']['tmp_name'];
        $filename = $_FILES['fileUpload']['name'];
        echo $filename;
        Input::file('fileUpload')->move($folder,$filename);

      
   }
}
