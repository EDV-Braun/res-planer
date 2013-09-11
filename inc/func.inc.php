<?php 
	function getUsedRessources($datum,$zeit = "") {
		global $sql;
		
		if($zeit != "")
			$zeit = "and bis > '$zeit'";		
		
		$sql_query = "	select * from auftraege,geplante_ressourcen
		where datum = '$datum' $zeit and
		geplante_ressourcen.auftraege_id = auftraege.id
		;";

		$sql->query($sql_query);
		
		$arr = array();
		$arr["fahrzeug"];
		while($row = $sql->fetchObject()) {
			array_push($arr,$arr["fahrzeuge"][] = $row->fahrzeug_id);
			array_push($arr,$arr["gespann"][] = $row->gespann_id);
			array_push($arr,$arr["mitarbeiter"][] = $row->mitarbeiter_id);
		}
		
		/*
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		*/
		
		return $arr;
	}
?>