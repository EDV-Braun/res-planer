<?php 
include "inc/error_handling.inc.php";
include "inc/sql.inc.php";
include "Savant3.php";

$conn = mysql_connect("localhost","root","xo7socx");

try {
	if(!$conn)
		throw new myException("Es konnte keine Verbindung hergestellt werden.");
	
	if(!mysql_select_db("blumberg_planer"))
		throw new myException("Die DB steht nicht zur Verfügung.");
		
} catch(Exception $e) {
	$e->showError();
}

$sql = new sql();
$tpl = new Savant3();

include "inc/func.inc.php";

?>