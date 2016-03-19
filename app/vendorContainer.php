<?php
namespace App;
class VendorContainer {

	private $vendorNames = array(
		'Super Metro',
		'Cebu Home Builders',
		'7-ElEVEN',
		'LONGWIN TABUNOK',
		"Metro Fresh n Easy",
		"Shopwise",
		'Ace Hardware'
		);

	function find($string) {
		$foundVendor = null;

		foreach($this->vendorNames as $vendorName) {

			$words = explode(' ',$vendorName);
			$truthValues = array();
			foreach($words as $word) {
				similar_text(strtolower($string), strtolower($word),$percentage);
				if( ( strpos( strtolower($string), strtolower($word)) !==FALSE ) || $percentage >= 15 ) {
					$truthValues[] = "yes";
				}
				else{
					$truthValues[] = "no";
				}
			}
			$truthValues = (array_count_values($truthValues));
			
			if(array_key_exists('yes',$truthValues) ) {
				if(sizeof($words) == $truthValues['yes']){
					$foundVendor = $vendorName;
				}	
			}
			
		}

		return $foundVendor;
	}

	function getAll(){
		return $this->$vendorNames;
	}




	


}
?>



