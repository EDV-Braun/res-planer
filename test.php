<?php 
	$heute = date("Ymd");
	$heute = strtotime("$heute -0 days");
	
	$tag[0] = "So";
	$tag[1] = "Mo";
	$tag[2] = "Di";
	$tag[3] = "Mi";
	$tag[4] = "Do";
	$tag[5] = "Fr";
	$tag[6] = "Sa";
	
	$html = "<table>";
	
	for($x=0; $x<=6; $x++) {		
		$converted_date = strtotime("+$x days",$heute);
		
		$wochentag = date("w",$converted_date);
		$date = date("d.m.Y",$converted_date);
		
		$html .= "<th>$date (".$tag[$wochentag].")</th>";
	}
	$html .= "</table>";
	
	echo "datum: $heute<br>";
	echo "morgen: $morgen";
	
	echo $html;
?>