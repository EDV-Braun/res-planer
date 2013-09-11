<?php

try {
    if (isset($_POST["neuer_mitarbeiter"])) {
        if ($_POST["name"] != "")
            $sql->query("insert into mitarbeiter(name,vorname) values('{$_POST["name"]}','{$_POST["vorname"]}');");
    }
    if (isset($_POST["loesche_mitarbeiter"])) {
        foreach ($_POST["mitarbeiter"] as $m) {
            $sql->query("delete from mitarbeiter where id = $m");
        }
    }
    if (isset($_POST["speicher_tages_mitarbeiter"])) {
        foreach ($_POST["mitarbeiter"] as $m) {
            $sql->query("insert into tages_mitarbeiter(mitarbeiter_id,datum) values($m,{$_GET["date"]});");
            header("location: ?page=uebersicht&date={$_GET["date"]}");
        }
    }

    if (isset($_POST["loesche_tages_mitarbeiter"])) {
        foreach ($_POST["mitarbeiter"] as $m) {
            $sql->query("delete from tages_mitarbeiter where mitarbeiter_id = $m and datum = '{$_GET["date"]}'");
        }
    }
} catch (Exception $e) {
    $e->showError();
}

try {
    switch ($_GET["action"]) {
        // Tages-Mitarbeiter auswählen
        case "stm":
            // Liste wird erstellt
            $sql->query("select * from mitarbeiter");

            $liste = "<label>Mitarbeiter Auswahl</label><select name='mitarbeiter[]' multiple size='8'>";
            
            while ($row = $sql->fetchObject()) {
                $sql2 = new sql();
                $sql2->query("select * from tages_mitarbeiter where mitarbeiter_id = $row->id and datum = '{$_GET["date"]}'");
                
                if ($sql2->numRows() == 0) {
                    $liste .= "<option value='$row->id'>$row->name, $row->vorname</option>";
                }
                    
            }
            $liste .= "</select>";

            // Ausgabe
            $html = "<form method=\"post\" action=\"?page=mitarbeiter&action=ltm&date=$act_date_ymd\">";
            $html .= "$liste<br>";
            $html .= "<input type=\"submit\" name=\"speicher_tages_mitarbeiter\" value=\"speichern\"></form>";
            break;

        // Tages-Mitarbeiter Liste
        case "ltm":
            // Liste wird erstellt
            $sql->query("select mitarbeiter.id,mitarbeiter.name,mitarbeiter.vorname from tages_mitarbeiter,mitarbeiter where tages_mitarbeiter.datum = '$act_date_ymd' and mitarbeiter.id = tages_mitarbeiter.mitarbeiter_id;");

            $liste = "<label>Mitarbeiter für den $act_date_german</label><select name='mitarbeiter[]' multiple size='8'>";
            while ($row = $sql->fetchObject()) {
                $liste .= "<option value='$row->id'>$row->name, $row->vorname</option>";
            }
            $liste .= "</select>";

            // Ausgabe
            $html = "<form method=\"post\" action=\"?page=mitarbeiter&action=ltm&date={$_GET["date"]}\">";
            $html .= "$liste<br>";
            $html .= "<input type=\"submit\" name=\"loesche_tages_mitarbeiter\" value=\"Auswahl löschen\"></form>";
            $html .= "<a href=\"?page=mitarbeiter&action=stm&date={$_GET["date"]}\"><button>Mitarbeiter hinzufügen</button></a>";
            break;

        // Mitarbeiter Liste
        case "lm":
            // Liste wird erstellt
            $sql->query("select * from mitarbeiter");

            $tpl = new Savant3();
            

            $liste = "<label>Mitarbeiter</label>";
            $tpl->res = $sql->fetchObject();
            $tpl->fetch("tpl/mitarbeiter_liste.tpl.php");
            
           
            // Ausgabe
            $html = "<form method=\"post\" action=\"?page=mitarbeiter&action=lm&date={$_GET["date"]}\">";
            $html .= "<label>Name:</label><input type=\"text\" name=\"name\">";
            $html .= "<label>Vorname:</label><input type=\"text\" name=\"vorname\">";
            $html .= "$liste<br>";
            $html .= "<input type=\"submit\" name=\"neuer_mitarbeiter\" value=\"speichern\">";
            $html .= "<input type=\"submit\" name=\"loesche_mitarbeiter\" value=\"Auswahl löschen\"></form>";
            break;
        // default
        default:
            $html = "nix";
    }
} catch (Exception $e) {
    $e->showError();
}

echo $html;
?>