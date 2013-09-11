<?php
//header("location: ?page=mitarbeiter&action=ltm&date=$act_date_ymd");

$res = array();
$res = getUsedRessources($act_date_ymd);

$sql->getTagesMitarbeiter($act_date_ymd);


while ($row = $sql->fetchObject()) {
    if (!in_array($row->mitarbeiter_id, $res)) {
        $mitarbeiter_liste .= "$row->name, $row->vorname<br>";
    }
}

$sql->query("select * from auftraege where datum = '$act_date_ymd'");

while ($row = $sql->fetchObject()) {
    $tpl->auftrag = $row;

    $sql2 = new sql();
    $sql2->query("select * from geplante_ressourcen where auftraege_id = $row->id");

    $ressourcen = Array();
    while ($row2 = $sql2->fetchObject()) {
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
        $name = $row3->name;
        $vorname = $row3->vorname;

        // Array
        array_push($ressourcen, array(
            "fahrzeug" => $fahrzeug,
            "gespann" => $gespann,
            "name" => $name,
            "vorname" => $vorname,
            "edit" => "?page=auftraege&action=anlegen&id=$row->id&ressourcen_id=$row2->id&date=$act_date_ymd",
            "delete" => "javascript:deleteRes($row->id,$row2->id,$act_date_ymd);"
        ));
        
         
    }
    
    $tpl->res = $ressourcen;
    
    $tpl->edit = "?page=auftraege&action=anlegen&id=$row->id&date=$act_date_ymd";
    $tpl->delete = "";

    $auftraege_liste .= $tpl->fetch("tpl/order_details.tpl.php");
}
?>

<h3>Freie Mitarbeiter für den <?php echo $act_date_german ?></h3>
<?php echo $mitarbeiter_liste ?>
<br>
<a href='?page=mitarbeiter&action=stm&date=<? echo $act_date_ymd ?>'>hinzufügen</a>

<h3>Aufträge für den <?php echo $act_date_german ?></h3>
<?php echo $auftraege_liste ?>
<a href="?page=auftraege&action=anlegen&date=<?php echo $act_date_ymd ?>">Auftrag anlegen</a>