<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Test;

class FineReaderController extends Controller
{
    function sendImage(){
        
        $applicationId = 'extrak receipt scanner';
        $password = '+0Mv+rU+EbB/8AmHYxGhgGkN';
        $fileName = 'homebuilders.jpg';

       // $local_directory=dirname(__FILE__).'/receiptsImg';
 
            $local_directory = public_path().'/img';
        
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
 
      //header('Content-type: application/rtf');
      //header('Content-Disposition: attachment; filename="file.rtf"');


 
        
        echo $response;
        
        echo "<br>";
        echo "<br>";
        

        $xml = simplexml_load_string($response);
        $lines = array();
        $temp = '';

        foreach($xml->children() as $page ) {
           foreach($page->children() as $block) {
              if($block->attributes()['blockType'] == "Text"){
                  foreach($block->children() as $text) {  
                      foreach($text->children() as $par) {
                          foreach($par->children() as $line) {
                              foreach($line->formatting as $formatting){
                                  $temp = '';
                                  foreach($formatting->children() as $charparams){
                                    $temp .= $charparams;
                                  }
                                  $lines [] = $temp;
                              }
                          }
                      } 
                  }  
              }
               elseif($block->attributes()['blockType'] == "Table"){
                   foreach($block->children() as $row){
                       $temp = '';
                       foreach($row->children() as $cell){
                           $temp .= ' ';
                           foreach($cell->children() as $text){
                               foreach($text->children() as $par){
                                   foreach($par->children() as $line) {
                                    foreach($line->formatting as $formatting){
                                      
                                      foreach($formatting->children() as $charparams){
                                        $temp .= $charparams;
                                      }
                                      
                                    }
                                }
                               }
                           }
                          
                       }
                        $lines [] = $temp;
                   }
               }
           }
        }

        $result = $this->parseValues($lines);
        echo "<pre>";
        print_r ($result);
        echo "</pre>";
    }
    
    function parseValues($lines){
        $values = array();
        foreach($lines as $line) {
            $values[] = $line;
        }
        return $values;
    }
        
    
}