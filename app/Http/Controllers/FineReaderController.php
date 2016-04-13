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

        $applicationId = 'extract receipt scanner6';
        $password = ' aIKV7vi6i66jEVG1hEX0Rzfl';
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
            sleep(2);
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
        $finalPlacePurchased = $this->getPlacePurchased($lineArray);
        $items = $this->getItems($lineArray,$total['index']);

        $arrayData =  array(
            'vendor'         => $merchant,
            'receipt_no'     => $or_number,
            'date_purchased' => $date_purchased,
            'place_purchased'=> $finalPlacePurchased,
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

        $threshold = 10.00;
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
            "Metro Fresh and Easy Punta",
            "Gaisano Capital South",
            "Enzo's Meat Market Foods Corp",
            "ThreeSixty Pharmacy",
            "Cebu Familia House Modiste Inc",
            "Gaisano Grand Mall Minglanilla",
            "Jey Gas Center",
            "La Nueva Drugstore Chains,Inc",
            "Longwin Tabunok",
            "New Banilad Shell Station",
            "The Pork Shop",
            "South Town Center",
            "Family Mart",
            "Jollibee Cebu Mango",
            "Jollibee Leon Kilat",
            "Jolibee Super Metro Mambaling",
            "Siknoy Noodle House",
            "Ice Giant Desserts & Snacks Inc"


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

        $foundIndex = null;

        $filters = [

                "OR No",
                "OR #",
                "0R#",
                "OR#", 
                "O.R.",
                "SI #",
                "SI#",
                "SI No",
                "S.I. NO",
                "RCPT #",
                "RCPT#",
                "Rcpt#",
                "Rcpt #",
                "SALES INVOICE NUMBER",
                "Sales Invoice Number",
                "Sales Invoice No",
                "Official Receipt #",
                "Sales Invoice#",
                "CASH SALES INVOICE",
            ];

        $words = null;
        foreach ($lineArray as $key => $line) {
            
            foreach ($filters as  $filter) {
                $line = str_replace($filter, $filter." ", $line);
                $newfilter = ($filter);
                $base = ($line);
                $find = strpos($base, $newfilter);
                if ($find !==FALSE) {
                    
                   $found = TRUE;
                   $foundBase = $base;
                   $foundFilter = $newfilter;
                   $foundIndex = $key;

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
        if($receiptNo == "" && $found){
            $line = ($lineArray[$foundIndex]);
            $next = ($lineArray[$foundIndex+1]);
            $filterCharAt = strpos($line,$foundFilter);

            $temp = substr($next, $filterCharAt-100);
            $temp = trim( str_replace(".", "", $temp) );

            $match = preg_match('[\d+]',$temp,$matches);
            if($match){
                $receiptNo = $matches[0];
            }
        }
        //dd($lineArray);
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
        $lineArray = array_filter($lineArray);
        $value = null;
        $indexOfTotal = 0;
        $total = 0.00;
        $totalIndeces = [];
        $hasSubtotal = false;
        $filters = [
         'Total',
         'Total Due',
        'Total amount',
        'Amount due',
        'Due amount',
        'Total Invoice',
        'Total amount paid',
        'Subtotal'
        ];

        $removeFilters = [
            'Total Tender',
            'Total Items',
            'Total Discount',
            'Vat',
            'Sales',
            'Change',
            'Net Total',
            'Vatable',
            'Cash'
        ];


        foreach($lineArray as $key=>$line) {
            $base = strtolower($line);
            foreach ($removeFilters as  $filter) {
                $newfilter = strtolower($filter);
                $find = strpos($base, $newfilter);
                if ($find!==FALSE) {
                    $lineArray[$key] = str_replace($newfilter, '', $base);

                }

            }

        }

        foreach ($lineArray as $key => $line) {
            
            $base = strtolower($line);
            foreach ($filters as  $filter) {
               $newfilter = strtolower($filter);
                $patternTotal = "[^\s*($newfilter)]";
               $match = preg_match($patternTotal, $base,$matches);

               if ($match) {
                    $totalIndeces[] = ['key'=>$key, 'filter'=>$newfilter];
               }
            }
        }
        if(sizeof($totalIndeces)>1){
             for ($i=0; $i < sizeof($totalIndeces); $i++) { 
                if(strtolower($totalIndeces[$i]['filter'])==strtolower('subtotal')){
                     unset($totalIndeces[$i]);
                }
            }
        }

        $pattern1 = "[\d+\s*\d{2}\s*]";

        foreach($totalIndeces as $index) {
            $key = $index['key'];

            $line = strtolower($lineArray[ $key ]);
            $line = str_replace('.', '', $line);
            $line = str_replace(',', '', $line);
            $line = str_replace('_', '', $line);


            $filter = strtolower($index['filter']);

            $rightof_keyword = substr( $line, strpos($line, $filter) + strlen($filter) );
            $rightof_keyword = str_replace(' ', '', $rightof_keyword);
           
            $match = preg_match($pattern1, $rightof_keyword, $matches);
            if($match) {
                $value = $matches[0];
                $total = number_format((float)$value/100, 2, '.', '');
                $indexOfTotal = $index;
            }
            if($total==0){
                $belowLine = $key+1;
                $lineforTotalVal = $lineArray[$belowLine];
                $words = explode(' ', $lineforTotalVal);
                foreach ($words as $word) {
                   $word = str_replace(',', '', $word);
                   $word = str_replace('.', '', $word);
                    $matchePossible = preg_match($pattern1, $word);
                    if($matchePossible){
                        $total = number_format((float)$word/100,2,'.','');
                    }

                }
            }
            
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
       $pattern5 = "(\w{3}\.\d{2}\.\d{4})";
       $pattern6 = "(\w{3}\s\d{1,2}\s\d{4})";

       $removeFilters = [
            'issued on',
            'valid until',
            'date issued'
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
               break;
            }
            elseif (preg_match($pattern2,$line,$matches)) {
                $date_purchased = $matches[0];
                break;
            }
            elseif (preg_match($pattern3,$line,$matches)) {
                $date_purchased = $matches[0];
                break;
            }
            elseif(preg_match($pattern4, $line,$matches)){
                $date_purchased = $matches[0];
                break;
              
            }   
            elseif(preg_match($pattern5, $line,$matches)){
                $date_purchased = $matches[0];
                break;
              
            }   
            elseif(preg_match($pattern6, $line,$matches)){
            $date_purchased = $matches[0];
            break;
          
            }   

        }
        return $date_purchased;
    }

    function getPlacePurchased($lineArray){
        $place_purchased = array();
        $finalPlacePurchased =null;
        $result = null;
        $filters = [
            'St.',
            'St .',
            'Street',
            'Road',
            'City',
            'Ave.'

        ];

        $firstTenLines = array();
        for ($i=0; $i < 10; $i++) { 
            $firstTenLines[] = $lineArray[$i];
        }
        foreach ($firstTenLines as $key => $line) {
            $base = strtolower($line);
            foreach ($filters as  $filter) {
                $newfilter = strtolower($filter);
                $foundKeyAddress = strpos($base,$newfilter);
                
                if($foundKeyAddress!==FALSE){
                    $place_purchased[] = [ 'line' => trim($line), 'index' =>$key ];
                    break;
                }     
            }
        }
      $lineCount = sizeof($place_purchased);
       if($lineCount>1){
        echo "sdfdf";
            $result = $place_purchased[1]['index'] - $place_purchased[0]['index'];
        }
     
         if($result==1){
            $finalPlacePurchased = $place_purchased[0]['line'].' '.$place_purchased[1]['line'];
         }
         else{
            $finalPlacePurchased = $place_purchased[0]['line'];
         }
        
        
        return $finalPlacePurchased;
    }

    function getItems($lineArray,$index){
        $removeFilters = [
            'VAT'
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
        
        $lines = array();
        $items = array();

        $pattern        =  "/(\d+)\s*[a-zA-Z]*+$/";
        $namePattern = '/(\D+)/';
    

        for ($i=0; $i <= $index['key']; $i++) { 
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
        return $items;
    } 



}