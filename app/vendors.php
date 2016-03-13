<?php
namespace App;
class Vendors {

	private $vendorNames = array(
		'Super Metro',
		'Cebu Home Builders'
		);

	function find($string) {
		$foundVendor = null;

		foreach($this->vendorNames as $vendorName) {

			$words = explode(' ',$vendorName);
			//$error = false;
			$truthValues = array();
			foreach($words as $word) {
				if( strpos( strtolower($string), strtolower($word)) !==FALSE ) {
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


	


}
?>



