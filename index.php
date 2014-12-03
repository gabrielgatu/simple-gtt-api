<?php 
	header('Access-Control-Allow-Origin: *');
	include_once('simple_html_dom.php');
	
	printJsonHoursForBusStop();
	
	function printJsonHoursForBusStop() {
		echo getJsonForBusStop("2052");
	}

	# Orari
	function getJsonForBusStop($busName) {
		$data = getBusHours($busName);
		return json_encode($data);
	}

	function getBusHours($busName) {
		$data = array();
		$html = file_get_html(
			'http://m.gtt.to.it/m/it/arrivi.jsp?n=' . $busName);

		foreach($html->find('li') as $element) {
		   	$busName = str_replace("&nbsp;", "", $element->find('h3', 0)->plaintext);
		    $busLine = getBusLine($element);
			
		    $data += array($busName => $busLine);
	  	}

	  	return $data;
	}

	function getBusLine($html) {
		$data = array();

		foreach($html->find('span.n') as $element) {
		   $single_hour = explode("*", $element);
		   
		   $result1 = str_replace("\t", "", $single_hour[0]);
		   $result1 = str_replace(" ", "", $result1);
		   $result1 = str_replace("</span>", "", $result1);
		   
		   $result2 = false;

		   if (isset($single_hour[1])) {
				$result2 = true;
		   }
	       $data += array($result1 => $result2);
	  	}
	  	return $data;
	}
 ?>