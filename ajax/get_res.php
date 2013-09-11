<?php
include "../config.php";

$sql->query("select * from auftraege where id = {$_GET["id"]}");

while($row = $sql->fetchObject()) {
	$sql2 = new sql();
	$sql2->query("select * from geplante_ressourcen where auftraege_id = $row->id");
	
	//echo "select * from geplante_ressourcen where auftraege_id = $row->id";
	$ressourcen = "";
	while($row2 = $sql2->fetchObject()) {
		$sql3 = new sql();
		
		// Fahrzeug
		$sql3->query("select * from fuhrpark where id = $row2->fahrzeug_id");
		$row3 = $sql3->fetchObject();
		$fahrzeug = $row3->fahrzeug;
		
		// Gespann
		$sql3->query("select * from fuhrpark where id = $row2->gespann_id");
		$row3 = $sql3->fetchObject();
		$gespann = $row3->fahrzeug;
		
		// Mitarbeiter
		$sql3->query("select * from mitarbeiter where id = '$row2->mitarbeiter_id'");
		$row3 = $sql3->fetchObject();
		$mitarbeiter = " (<i>$row3->name, $row3->vorname</i>)";
		
		$ressourcen .= "$fahrzeug, $gespann $mitarbeiter <a href='?page=auftraege&action=anlegen&id=$row->id&ressourcen_id=$row2->id&date=$act_date_ymd' title='Ressource Bearbeiten'>Edit</a><br>";
	}
	
	$auftraege_liste .= "<b>$row->betreff</b> von ".date("H:i",strtotime($row->von))." - ".date("H:i",strtotime($row->bis))." Uhr<br>$ressourcen<br>";
        
        echo $ressourcen;
}
?>
