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

    function extract(){

        $image_name = session::get('session_ImageName');
        
        $applicationId = 'extrak receipt scanner2';
        $password = 'nocLEqMkht7O/LDcou0mA62T';
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
    
        
        $arrayData = $this->getValues($response);
        dd($arrayData);

        //return view('pages.expenses',['extract'=>$arrayData]); 
        /*header('Content-type: application/xml');
        header('Content-Disposition: attachment; filename="file.xml"');*/
    }

    
    
    function getValues($response){
        $vendors        = new VendorContainer;
        $xml            = simplexml_load_string($response);
        $vendor         = null;
        $recognizedText = $xml->receipt->recognizedText;
        $lineArray      = explode("\n", $recognizedText);
        $receiptNumber  = $this->getReceiptNumber($lineArray);
        $lineItemsArray = $this->getLineItems($lineArray);
        
        $total          = $lineItemsArray['total'];
       // $items          = $this->getItemNames($lineArray,$lineItemsArray) ;

        foreach($lineArray as $line) {
            $result = $vendors->find($line);
            if($result!=null) {
                $vendor = $result;
                break;
            }
        }
         $arrayData =  array(
            'vendor'         => $vendor,
            'receipt_no'     => $receiptNumber,
            'recognizedText' => $lineArray,
            'total'          => $total,
          // 'items'          => $items
         );


         return $arrayData; 
    }
    
    function getReceiptNumber($lines){
        $receipt_number = null;

        $filters = [
            'OR No',
            'OR #',
            'SI #',
            'SALES INVOICE NUMBER'
        ];
        
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

    function isNumericWithComma($string){ 
        if ((',' == substr($string, 2, 1)) || (',' == substr($string, 3, 1))) { 
            if (is_numeric(str_replace('-', '', $string))) { 
                return true; 
            } 
        } 
        return false; 
    }

 function getLineItems($lineArray){

        $possibleTotalValues = array();
        $total = '';
        $filters = array(
            'Total',
            'Subtotal',
            'Sub-total',
            'total amount'
        );

        /*find all lines containing the total field*/
        foreach($lineArray as $key=>$line) {
            foreach($filters as $filter) {
                if( strpos( strtolower($line), strtolower($filter)) !==FALSE ) {

                    /*extract the float value. example: Total:3.30 will return 3.30*/
                    $value = (  filter_var( $line, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) ); 
                    $possibleTotalValues[] = ['index'=>$key, 'value'=>$value];
                    echo $value."<br>";
                }
            }
        }

        //mangita item
        foreach( $possibleTotalValues as $possibleTotalValue) {
            $index = $possibleTotalValue['index'];
            $possibleItems = array();
           
            for($i=0; $i<$index; $i++) {
                $ignored = '';
                 $words = explode(' ',$lineArray[$i]);
                 $value = '';
                 $split = '';

              

                 if($this->containsField($lineArray[$i]) ) {
                    $ignored = true;
                 }
                 else{
                    $ignored = false;
                 }

                
                 if(!$ignored) {
                     foreach($words as $word){

                        $found = ( $this->containsField($word) ) ;
                         
                            
                            if( strpos($word,'.' ) !==FALSE ){
                                $split = explode('.',$word);
                                if(sizeof($split) == 2 && ( strlen($split[1])==2 || strlen($split[1])==3 ) ){ //mu explode then dapat 2 ra ang decimal place
                                     $value = $word; 
                                }
                               
                            }
                            elseif( strpos($word,',' ) !==FALSE){
                                $split = explode(',',$word);
                                if(sizeof($split) == 2 && ( strlen($split[1])==2 || strlen($split[1])==3 ) ){
                                     $value = $word;
                                }
                            }
                     }
                 }

                 if( $value > 0 ) {
                    $possibleItems[] = ['index'=>$i, 'value'=>$value];
                   echo "index $i value $value <br>";
                }

            }

            $result = $this->isLineItemsEqualTotal($lineArray,$possibleTotalValue,$possibleItems);
            if($result) {
                $total = $possibleTotalValue['value'];
            }
        }

        return ['total'=>$total];

        
    }

    function containsField($line){
        $found = false;
        $filters = array(
            'change',
            'sales',
            
            'vat',
            'cash',
            'thank',
            'OR No',
            'OR #',
            'SI #',
            'SALES INVOICE NUMBER',
            'official',
            'receipt',
            'item(s)',
            'items',
            'total',
            'amount'

            );

        foreach($filters as $filter) {
            if( strpos(strtolower($line),strtolower($filter) ) !==FALSE  ) {
                $found = true;
            }
        }

        return $found;

        
    }

  


    function getItemNames($lineArray,$lineItemsArray){
       
        $lines = array();
        foreach($lineItemsArray['lineItems'] as $item){

            $lineIndex = $item['index'];
            $lines[] = $lineArray[$lineIndex];

        }
        return $lines;
    }

    function isLineItemsEqualTotal($lineArray,$total,$possibleItems) {
        
        $sum = 0;
        $found = false;

        foreach($possibleItems as $item){
            $sum+=($item['value']);
        }
        
        if($sum == $total['value']){ //true if items are one-liner
            $found = true;
        }
        else{
            $sum = 0;

            for($i=0; $i<sizeof($possibleItems); $i++) {
                $item = $possibleItems[$i];
                $sum+=($item['value']);
                $i++;
            }
           
            if($sum == $total['value']){ //true if items are two-liner
                $found = true;
            }
        }

        return $found;
    }


 
}