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
        $date_purchased = $this->getDatePurchased($lineArray);
        $place_purchased = $this->getPlacePurchased($lineArray);
        $items = $this->getItems($lineArray,$total['index']);

        $arrayData =  array(
            'vendor'         => $merchant,
            'receipt_no'     => $or_number,
            'date_purchased' => $date_purchased,
            'place_purchased'=> $place_purchased,
            'recognizedText' => '',
            'total'          => $total['value'],
            'items'          => $items,
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
                similar_text($base, $newfilter,$percentage);
                if($percentage > $greater && $percentage > $threshold) {
                    $greater = $percentage;
                    $merchant = $filter;
                }
            }
           
        }
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
      
        if (is_numeric(str_replace('.', '', $string))) { 
            return true; 
        } 
        return false; 
    }

    function getTotal($lineArray){
        $indexOfTotal = 0;
        $total = 0.00;
        $totalIndeces = [];
        $filters = [
         'Total',
        'Total amount',
        'Amount due'
        ];

        $removeFilters = [
            'Total Tender',
            'Total Items',
            'Vat',
            'Sales',
            'Subtotal',
            'Change'

        ];


        foreach($lineArray as $key=>$line) {
            $base = strtolower($line);
            foreach ($removeFilters as  $filter) {
                $newfilter = strtolower($filter);
                $find = strpos($base, $newfilter);
                if ($find!==FALSE) {
                    unset($lineArray[$key]);
                }

            }

        }


        foreach ($lineArray as $key => $line) {
            
            $base = strtolower($line);
            foreach ($filters as  $filter) {
               $newfilter = strtolower($filter);

               $find = strpos($base, $newfilter);

               if ($find!==FALSE) {
                    $totalIndeces[] = $key;
               }
            }
        }


        foreach($totalIndeces as $index) {

            $line = $lineArray[$index];
            $newline = preg_replace("/[^0-9]/","",$line);
            $total = number_format((float)$newline/100, 2, '.', '');
            $indexOfTotal = $index;



        }
        return ['value'=>$total, 'index'=>$indexOfTotal];

    }
    function getDatePurchased($lineArray){
        $date_purchased = null;
        $month =null;
        $day = null;
        $year = null;
       $pattern1 = "/\d{2}\/\d{2}\/\d{4}/";
       $pattern2 = "/\d{1}\/\d{2}\/\d{2}/";
       $pattern3 = "/\d{2}\-\d{2}\-\d{4}/";
       $pattern4 = "/(\w+) (\d{1,2}), (\d{4})/";
       $removeFilters = [
            'issued on',
            'valid until'
       ];

       $filters = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        foreach ($lineArray as $key => $line) {
            $base = strtolower($line);
            foreach ($removeFilters as $removeFilter) {
                $newFilterRemove = strtolower($removeFilter);

                $foundFilter = strpos($line,$newFilterRemove);
                if ($foundFilter!==FALSE) {
                    unset($lineArray[$key]);

                }
                else{
                  
                }
            }
        }
        foreach ($lineArray as $key => $line) {
            if (preg_match($pattern1, $line, $matches)) {
               $date_purchased = $matches[0];
            }
            elseif (preg_match($pattern2,$line,$matches)) {
                $date_purchased = $matches[0];
            }
            elseif (preg_match($pattern3,$line,$matches)) {
                $date_purchased = $matches[0];
            }
            elseif(preg_match($pattern4, $line,$matches)){
                $date_purchased = $matches[0];
              
            }      
        }
        return $date_purchased;
    }
    function getPlacePurchased($lineArray){
        $place_purchased = [];
        $filters = [
            'St.',
            'Street',
            'North',
            'South',
            'Road',
            'City',
        ];

        $firstTenLines = array();
        for ($i=1; $i < 10; $i++) { 
            $firstTenLines[] = $lineArray[$i];
        }
        foreach ($firstTenLines as $key => $line) {
            $base = strtolower($line);
            foreach ($filters as $key => $filter) {
                $newfilter = strtolower($filter);
                $foundKeyAddress = strpos($base,$newfilter);
                if($foundKeyAddress!==FALSE){
                    $place_purchased = trim($line);
                    break;
                }
               
            }
        }

        return $place_purchased;
    }

    function getItems($lineArray,$index){


        $removeFilters = [
            'VAT',
        ];


        foreach($lineArray as $key=>$line) {
            $base = strtolower($line);
            foreach ($removeFilters as  $filter) {
                $newfilter = strtolower($filter);
                $find = strpos($base, $newfilter);
                if ($find!==FALSE) {
                    unset($lineArray[$key]);
                }

            }

        }

        //$newArray = array();
        $lines = array();
        $items = array();

        $pattern        =  "/(\d+)\s*[a-zA-Z]*+$/";
        $namePattern = '/(\D+)/';
    

        //dd($lineArray);
        for ($i=0; $i <= $index; $i++) { 
            if(array_key_exists($i, $lineArray)) {
                $subject        = str_replace('.', '', $lineArray[$i]);
                $subject        = str_replace(',', '', $subject);
                $subject        = trim($subject);
                
                if( preg_match($pattern, $subject, $matches) ) {
                    if( preg_match($namePattern, $subject, $nameMatches) ) {
                        $name = trim($nameMatches[0]);
                    
                        $price = number_format((float)$matches[1]/100, 2, '.', '');
                        if(!empty($name)) {
                            $items[] = ['price'=>$price, 'name'=>$nameMatches[0] ];
                        }
                        
                  
                    }
                }
            }

        }


        return($items);
    } 



}