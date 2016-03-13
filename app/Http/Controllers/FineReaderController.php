<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Test;
use Session;
use DateTime;
use App\vendors;

class FineReaderController extends Controller
{

    function extract(){

        $image_name = session::get('session_ImageName');
        
        $applicationId = 'extrak receipt scanner';
        $password = '+0Mv+rU+EbB/8AmHYxGhgGkN';
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
        
        return $this->getValues($response);
 
        //header('Content-type: application/rtf');
        //header('Content-Disposition: attachment; filename="file.rtf"');
        
           
        //
    }
    
    function getValues($response){

        $vendors = new Vendors;
    
        $lineItems      = array();
        $date           = array();
        $time_purchased = array();
        $address        = array();
        $total          = array();
        $xml            = simplexml_load_string($response);
        $recognizedText = $xml->receipt->recognizedText;
        $lines          = explode("\n", $recognizedText);
        $receiptNumber  = $this->getReceiptNumber($lines);
        
        $vendor         = '';

        foreach($lines as $line) {
            $result = $vendors->find($line);
            if($result!=null) {
                $vendor = $result;
                break;
            }
        }



        
 
        foreach($xml->receipt->children() as $key => $child) {
            if($child->getName() == "field"){
                if($child->attributes() == "Date"){
                    $date = new DateTime($child->value[2].'-'.$child->value[1].'-'.$child->value[0]);   
                }
                elseif($child->attributes() == "Address"){
                    $address = ['Address' => $child->value];
                }
                elseif ($child->attributes() == "Total") {
                    $total = ['Total' => $child->value/100];
                }
                elseif ($child->attributes() == "Time") {
                    $time_purchased[] = [$child->value]; 
                }
                elseif ($child->attributes() == "Vendor") {
                    //$vendor = $child->value;
                }
                
            }
            elseif($child->getName() == "lineItem"){
                $lineItems[] = ['name'=> $child->name,'price' => $child->total/100];
            }
        }

        $hasDuplicate = $this->hasDuplicateItemNames($lineItems);
        if($hasDuplicate){
            $lineItems = $this->getCorrectedNames($lines,$lineItems);
        }
        else{
            //nothing to do
        }
        
       //$vendor = "mariem tabay jr";
         $arrayData =  array(
            'vendor'     => $vendor,
            'date'       => $date->format('Y-m-d'),  
            'items'      => $lineItems,
            'receipt_no' => $receiptNumber,
            'recognized' => $recognizedText
         );
      //echo $response;
        return view('pages.expenses',['extract'=>$arrayData]); 
    }
    
    function getReceiptNumber($lines){
        
        
       // dd($lines);
        $receipt_number = null;

        $filters = ['OR No','OR #','SI #'];
        
        foreach($lines as $index=>$line) {
            foreach($filters as $filter) {
                if (strpos($line, $filter) !== false) {

                    $rightof_keyword = substr( $line, strpos( $line, $filter) + strlen($filter) );
                    $texts = array_filter(explode(' ',$rightof_keyword));
                    foreach($texts as $text) {
                        if(is_numeric($text)){
                            $receipt_number = $text;
                            break 3;
                        }
                        elseif($this->isNumericWithDash($text)) {
                            $receipt_number = $text;
                            break 3;
                        }
                    } 
                }
            }
        }
        
        return ($receipt_number);
    }

    function isNumericWithDash($string){ 
        if (('-' == substr($string, 2, 1)) || ('-' == substr($string, 3, 1))) { 
            if (is_numeric(str_replace('-', '', $string))) { 
                return true; 
            } 
        } 
        return false; 
    }  

    function hasDuplicateItemNames($lineItems){
        //dd($lineItems);
        $error = false;
        $names = array();
        foreach($lineItems as $lineItem){
            $names[] = (string) $lineItem['name'];
        }
        
        $duplicates = (array_count_values($names));
        
        foreach ($duplicates as $key => $duplicate) {
            if($duplicate > 1) {
                $error = true;
            }
        }

        return $error;
    }

    function getCorrectedNames($lines,$lineItems){
        $prices = array();
        foreach ($lineItems as $key => $lineItem) {
            $prices[] = $lineItem['price'];
        }

        $pricesSize = sizeof($prices);
        $linesSize = sizeof($lines);

        $firstOccurenceAtIndex = null;

        for($i=0; $i<$linesSize; $i++) {
            $str = (string)$prices[0];
            if (strpos($lines[$i], $str) !== FALSE){
                $isOK = true;
                $j = $i;
                foreach($prices as $price) {
                    if (strpos($lines[$j], (string)$price) !== FALSE){
                        //nothing to do for now
                    }
                    else{
                        $isOK = false;
                    }
                    $j=($j+2);
                }

                if($isOK){
                    $firstOccurenceAtIndex = $i;
                    break 1;
                    
                }
            }
     

        }
        $itemCount = sizeof($lineItems);
        $itemNames = $this->getItemNameFromUpperLine($firstOccurenceAtIndex,$lines,$itemCount);

        $correctedLineItems = array();
        for($i=0; $i<$itemCount; $i++) {
            $correctedLineItems[] = ['name'=>$itemNames[$i], 'price'=>$prices[$i]];
        }
        return ($correctedLineItems);
  
    }

    function getItemNameFromUpperLine($firstOccurenceAtIndex,$lines,$itemCount){
        $index = $firstOccurenceAtIndex - 1;
        $itemNames = array();
        for($i=0; $i<$itemCount; $i++) {
            $itemNames[] = trim($lines[$index]);
            $index = ($index+2);
        }

        return ($itemNames);

    }

 
}