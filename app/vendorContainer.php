<?php
namespace App;
class VendorContainer {

	private $vendorNames = array(
		"McDonald's South Road",
		'Super Metro Basak',
		'Cebu Home Builders',
		'7-ELEVEN',
		'LONGWIN TABUNOK',
		"Metro Fresh n Easy",
		"SHOPWISE BASAK CEBU ",
		'ACE HARDWARE PHILIPPINES'
		);

	function findLine($string) {
		//$string = trim($string);
		$foundVendor = null;
		$percentageArray = array();
		foreach($this->vendorNames as $vendorName) {
			similar_text(strtolower($string), strtolower($vendorName),$percentage);
			$percentageArray[] = ['vendor'=>$vendorName,'base'=>$string, 'value'=>$percentage];
		}
		$possibleVendor = '';
		$possibleValue = 0;

		$percentageArray = array_filter($percentageArray);

		foreach($percentageArray as $result) {
				$value = $result['value'];


				
				if($value > $possibleValue){
					$possibleValue = $value;
					$possibleVendor = $result['vendor'];
				}

		}
		


		//echo "highest is $possibleVendor with $possibleValue<br>";
		return $foundVendor = ['vendor'=> $possibleVendor, 'value'=> $possibleValue ] ;
	}

	function find($lineArray) {

		$greater = 0;
		$vendor = '';
		$results = $this->findLine($lineArray);
		return $vendor = $results['vendor'];

	}





	function getAll(){

		return $this->$vendorNames;
	}




	


}
?>



