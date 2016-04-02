<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Test;
use Session;
use DateTime;
use App\vendorContainer;

class FineReaderController extends Controller
{

    //
    function extract( $image_name ){

        $applicationId = 'extrack receipts';
        $password = 'SxwbCyz0Qa6APg3ZH/P9LOSR';
        $fileName = $image_name;

        // $local_directory=dirname(__FILE__).'/receiptsImg';
 
        $local_directory = public_path().'/assets/receiptsImg';
        
        $filePath = $local_directory.'/'.$fileName;
        
        if(!file_exists($filePath))
        {
            die('File '.$filePath.' not found.');
        }
        if(!is_readable($filePath) )
        {
            die('Access to file '.$filePath.' denied.');
        }

        $url = 'http://cloud.ocrsdk.com/processReceipt?imageSource=scanner';
      
        // Send HTTP POST request and ret xml response
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
        $post_array = array();
        
        if((version_compare(PHP_VERSION, '5.5') >= 0)) {
            $post_array["my_file"] =  new \CurlFile($filePath,'file/exgpd',$fileName);
           
        } else {
            $post_array["my_file"] = "@".$filePath;
        }
        
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $post_array); 
        $response = curl_exec($curlHandle);
        
        if($response == FALSE) {
            $errorText = curl_error($curlHandle);
            curl_close($curlHandle);
            die($errorText);
        }
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);


        $xml = simplexml_load_string($response);
        
        if($httpCode != 200) {
            
            if(property_exists($xml, "message")) {
                die($xml->message);
            }
            die("unexpected response ".$response);
        }

        $arr = $xml->task[0]->attributes();
        $taskStatus = $arr["status"];
        if($taskStatus != "Queued") {
            die("Unexpected task status ".$taskStatus);
        }
  

        $taskid = $arr["id"];  

        $url = 'http://cloud.ocrsdk.com/getTaskStatus';
        $qry_str = "?taskid=$taskid";

        while(true)
        {
            sleep(5);
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url.$qry_str);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
            curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
            curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
            $response = curl_exec($curlHandle);
            $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);
            $xml = simplexml_load_string($response);
            
            if($httpCode != 200) {
              if(property_exists($xml, "message")) {
                die($xml->message);
              }
              die("Unexpected response ".$response);
            }
            
            $arr = $xml->task[0]->attributes();
            $taskStatus = $arr["status"];
            if($taskStatus == "Queued" || $taskStatus == "InProgress") {
              continue;
            }
            if($taskStatus == "Completed") {
              break;
            }
            if($taskStatus == "ProcessingFailed") {
              die("Task processing failed: ".$arr["error"]);
            }
            die("Unexpected task status ".$taskStatus);
        }
        


        $url = $arr["resultUrl"];   
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curlHandle);
        curl_close($curlHandle);
        
        return $response;

        //return view('pages.expenses',['extract'=>$arrayData]); 
        /*header('Content-type: application/xml');
        header('Content-Disposition: attachment; filename="file.xml"');*/
    }


    function sendToOCR () {
        $image_name = session::get('session_ImageName');
        $response = $this->extract($image_name);

        $xml = simplexml_load_string($response);
        $recognizedText = $xml->receipt->recognizedText;
        $lineArray = explode("\n",$recognizedText);
        $merchant = $this->getMerchant($lineArray);
        $or_number = $this->getOR($lineArray);
        $total = $this->getTotal($lineArray);

        $arrayData =  array(
            'vendor'         => $merchant,
            'receipt_no'     => $or_number,
            'recognizedText' => '',
            'total'          => '',
            'items'          => [],
            'date'           => ''
         );

        Session::flash('session_ImageName',$image_name);
        Session::flash('arrayData', $arrayData);
        return redirect('expenses');
       // return view('pages.expenses',['extract'=>$arrayData, 'fromUpload'=>true]); 

    }

    function getMerchant($lineArray){
        $threshold = 80.00;
        $merchant = null;

        $filters = [
            "Shopwise Basak Cebu",
            "Super Metro Basak",
            "Gaisano Tabunok",
            "ACE HARDWARE PHILIPPINES, INC",
            "Cebu Home & Builders Centre",
            "McDonald's South Road",
            "Robinsons Place Cebu",
            "7-Eleven",
            "Fooda Saversmart",
            "Gaisano Tabunok",
            "KFC Cebu IT Park",
            "Your Life Pharmacy",
            "Cebu's Original Lechon Belly",
            "Metro Fresh and Easy Punta"


            ];

        $greater = 0;


        foreach ($lineArray as $key=>$line) {

            $base = trim(strtolower($line));

            foreach($filters as $filter) {

                $newfilter = strtolower($filter);
                //$find = strpos($base, $newfilter );
                similar_text($base, $newfilter,$percentage);

                //echo "$base ------ $newfilter <b>$percentage</b> <br>";

                if($percentage > $greater && $percentage > $threshold) {
                    $greater = $percentage;
                    $merchant = $filter;
                }
            }
           
        }
        echo $merchant;
        return $merchant;
    }

    function getOR($lineArray){
        $receiptNo = null;
        $found = false;
        $foundBase =null;
        $foundFilter = null;



        $filters = [
            "OR No",
            "OR #",
            "SI #",
            "SI No",
            "SALES INVOICE NUMBER",
            "Official Receipt #",
            "OR#",
            "Sales Invoice#",
            "O.R.",
          

            ];
        $words = null;
        foreach ($lineArray as $key => $line) {
            
            foreach ($filters as $key => $filter) {
                $line = str_replace($filter, $filter." ", $line);
                $newfilter = ($filter);
                $base = ($line);
                $find = strpos($base, $newfilter);
               // echo "$base -- $newfilter <br>";
               
                if ($find !==FALSE) {
                    
                   $found = TRUE;
                   $foundBase = $base;
                   $foundFilter = $newfilter;
                 
                }
                
            }

        }

        
        if($found) {
          
            $rightof_keyword = substr( $foundBase, strpos($foundBase, $foundFilter) + strlen($foundFilter) );
            $texts =  array_filter(explode(' ', $rightof_keyword));

            foreach ($texts as $key => $text) {
                $text = trim( str_replace(".", "", $text) );
                $resultWithDash = $this->isNumericWithDash($text);
                $resultNumeric = is_numeric($text);
                if ($resultWithDash || $resultNumeric) {
                    $receiptNo = $text;
                    break;
                }
            }
        }

        echo $receiptNo;
       return $receiptNo;
    }

    function isNumericWithDash($string){ 
        
            if (is_numeric(str_replace('-', '', $string))) { 
                return true; 
            } 
        
        return false; 
    }

    function isNumericWithComma($string){ 
     
            if (is_numeric(str_replace(',', '', $string))) { 
                return true; 
            } 
        
        return false; 
    }

    function isNumericWithDot($string){
        if (('.' == substr($string, 2, 1)) || ('.' == substr($string, 3, 1))) { 
            if (is_numeric(str_replace('.', '', $string))) { 
                return true; 
            } 
        } 
        return false; 
    }

    function getTotal($lineArray){
    }


}