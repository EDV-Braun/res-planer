<?php
include "../config.php";

if ($_GET["id"] != "") {
    $sql->query("update auftraege set pos={$_GET["pos"]} where id={$_GET["id"]};");
} else {
    echo "Fehler: es ist keine ID angegeben";
}
?>
