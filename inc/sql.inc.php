<?php 
class sql {
	private $result;
                  public $query;
	
	public function __construct() {
		
	}
	
	public function query($query) {
                                    $this->query = $query;
		$result = mysql_query($query);
		
		if(!$result)
			throw new myException("Query konnte nicht ausgeführt werden: ".mysql_error());
		
		$this->result = $result;
		return $result;
	}      

                  public function fetchObject() {
		return mysql_fetch_object($this->result);
	}
	
	public function numRows() {
		return mysql_num_rows($this->result);
	}
	
	// Projekt spezifische Querys
	public function getTagesMitarbeiter($date) {
		return $this->query("select * from mitarbeiter,tages_mitarbeiter where datum = '$date' and tages_mitarbeiter.mitarbeiter_id = mitarbeiter.id");
	}
}
?>