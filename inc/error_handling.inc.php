<?php 
class MyException extends Exception
{
	// Die Exceptionmitteilung neu definieren, damit diese nicht optional ist
	public function __construct($message, $code = 0) {
		// etwas Code

		// sicherstellen, dass alles korrekt zugewiesen wird
		parent::__construct($message, $code);
	}

	// maßgeschneiderte Stringdarstellung des Objektes
	public function __toString() {
		return "[{$this->code}]: {$this->message}\n";
	}
	
	public function showError() {
		$_SESSION["error"] = $this->__toString();
	}
}

?>