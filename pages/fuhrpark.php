<?php 
try {
	if(isset($_POST["neues_fahrzeug"])) {
			$sql->query("insert into fuhrpark(fahrzeug,typ) values('{$_POST["name"]}',{$_POST["typ"]});");		
	}
	if(isset($_POST["loesche_fahrzeug"])) {
		foreach($_POST["fahrzeuge"] as $f) {
			$sql->query("delete from fuhrpark where id = $f");
		}
	}
} catch(Exception $e) {
	$e->showError();
}

try {
	switch ($_GET["action"]) {						
		// Fuhrpark Liste
		case "lf":
			// Fahrzeug Liste wird erstellt
			$sql->query("select * from fuhrpark where typ = 0");

			$liste = "<label>Fahrzeuge</label><select name='fahrzeuge[]' multiple size='6'>";
			while($row = $sql->fetchObject()) {
				$liste .= "<option value='$row->id'>$row->fahrzeug</option>";
			}
			$liste .= "</select>";
				
			// Ausgabe
			$html = "<form method=\"post\" action=\"?page=fuhrpark&action=lf&date=$act_date_ymd\">";
			$html .= "<label>Neues Fahrzeug anlegen</label><input type=\"text\" name=\"name\">";
			$html .= "$liste<br>";
			$html .= "<input type=\"hidden\" name=\"typ\" value=\"0\">";
			$html .= "<input type=\"submit\" name=\"neues_fahrzeug\" value=\"speichern\">";
			$html .= "<input type=\"submit\" name=\"loesche_fahrzeug\" value=\"Auswahl löschen\"></form><br>";
			
			// Gespann Liste wird erstellt
			$sql->query("select * from fuhrpark where typ = 1");
			
			$liste = "<label>Gespanne</label><select name='fahrzeuge[]' multiple size='6'>";
			while($row = $sql->fetchObject()) {
				$liste .= "<option value='$row->id'>$row->fahrzeug</option>";
			}
			$liste .= "</select>";
			
			// Ausgabe
			$html .= "<form method=\"post\" action=\"?page=fuhrpark&action=lf&date=$act_date_ymd\">";
			$html .= "<label>Neues Gespann anlegen</label><input type=\"text\" name=\"name\">";
			$html .= "$liste<br>";
			$html .= "<input type=\"hidden\" name=\"typ\" value=\"1\">";
			$html .= "<input type=\"submit\" name=\"neues_fahrzeug\" value=\"speichern\">";
			$html .= "<input type=\"submit\" name=\"loesche_fahrzeug\" value=\"Auswahl löschen\"></form>";
			break;
		
			// default
		default:
			$html = "nix";
	}

} catch(Exception $e) {
	$e->showError();
}

echo $html;
?>