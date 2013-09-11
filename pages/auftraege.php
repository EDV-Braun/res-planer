<?php

try {
    if (isset($_POST["speichern"])) {
        if ($_POST["id"] == "") {
            $sql -> query("insert into auftraege(betreff,datum,von,bis,notiz) values('{$_POST["betreff"]}','$act_date_ymd','" . str_replace(":", "", $_POST["von"]) . "','" . str_replace(":", "", $_POST["bis"]) . "','{$_POST["notiz"]}');");
            $auftrag_id = mysql_insert_id();
        } else {
            $auftrag_id = $_POST["id"];
            $sql -> query("update auftraege set betreff='{$_POST["betreff"]}',notiz='{$_POST["notiz"]}',datum='$act_date_ymd',von='" . str_replace(":", "", $_POST["von"]) . "',bis='" . str_replace(":", "", $_POST["bis"]) . "' where id=$auftrag_id");
        }
    }
    if (isset($_POST["ressource_speichern"])) {
        $auftrag_id = $_POST["id"];

        if ($_POST["update"] == "true") {
            $sql -> query("update geplante_ressourcen set fahrzeug_id = '{$_POST["fahrzeug"]}', gespann_id = '{$_POST["gespann"]}', mitarbeiter_id = '{$_POST["mitarbeiter"]}' where id = '{$_POST["ressourcen_id"]}'");
        } else {
            $sql -> query("insert into geplante_ressourcen(auftraege_id,fahrzeug_id,gespann_id,mitarbeiter_id) values('$auftrag_id','{$_POST["fahrzeug"]}','{$_POST["gespann"]}','{$_POST["mitarbeiter"]}');");
        }
    }
} catch (Exception $e) {
    $e -> showError();
}

if ($_GET["id"])
    $auftrag_id = $_GET["id"];

try {
    $von = "";
    $bis = "";
    if (isset($auftrag_id)) {
        $sql -> query("select * from auftraege where id = $auftrag_id");
        $row = $sql -> fetchObject();

        foreach ($row as $key => $val) {
            ${$key} = $val;
        }

        // Verbrauchte Ressourcen laden
        $usedRes = getUsedRessources($act_date_ymd, $von);

        $von = date("H:i", strtotime($row -> von));
        $bis = date("H:i", strtotime($row -> bis));
    }

    // Ressourcen werden geladen
    $sql_r = new sql();
    $sql_r -> query("select * from geplante_ressourcen where auftraege_id = '{$_GET["id"]}' and id = '{$_GET["ressourcen_id"]}' ");
    $row_r = $sql_r -> fetchObject();

    switch ($_GET["action"]) {
        // Fuhrpark Liste
        case "anlegen" :
            // Fahrzeug Liste wird erstellt
            $sql -> query("select * from fuhrpark where typ = 0");

            $fahrzeug_liste = "<label>Fahrzeuge</label><select name='fahrzeug' size='4'>";
            while ($row = $sql -> fetchObject()) {
                $class = "";
                $disabled = "";
                $selected = "";
                if ($row -> id == $row_r -> fahrzeug_id) {
                    $selected = "selected";
                } else {
                    if (in_array($row -> id, $usedRes["fahrzeuge"])) {
                        $class = "inUse";
                        $disabled = "disabled";
                    }
                }

                $fahrzeug_liste .= "<option value='$row->id' class='$class' $disabled $selected>$row->fahrzeug</option>";
            }
            $fahrzeug_liste .= "</select>";

            // Gespann Liste wird erstellt
            $sql -> query("select * from fuhrpark where typ = 1");

            $gespann_liste = "<label>Gespanne</label><select name='gespann' size='4'>";
            while ($row = $sql -> fetchObject()) {
                $class = "";
                $disabled = "";
                $selected = "";
                if ($row -> id == $row_r -> gespann_id) {
                    $selected = "selected";
                } else {
                    if (in_array($row -> id, $usedRes["gespann"])) {
                        $class = "inUse";
                        $disabled = "disabled";
                    }
                }

                $gespann_liste .= "<option value='$row->id' class='$class' $disabled $selected>$row->fahrzeug</option>";
            }
            $gespann_liste .= "</select>";

            // Mitarbeiter Liste wird erstellt
            $sql -> getTagesMitarbeiter($act_date_ymd);

            $mitarbeiter_liste = "<label>Mitarbeiter</label><select name='mitarbeiter' size='4'>";
            while ($row = $sql -> fetchObject()) {
                $class = "";
                $disabled = "";
                $selected = "";
                if ($row -> mitarbeiter_id == $row_r -> mitarbeiter_id) {
                    $selected = "selected";
                } else {
                    if (in_array($row -> mitarbeiter_id, $usedRes["mitarbeiter"])) {
                        $class = "inUse";
                        $disabled = "disabled";
                    }
                }

                $mitarbeiter_liste .= "<option value='$row->mitarbeiter_id' class='$class' $disabled $selected>$row->name, $row->vorname</option>";
            }
            $mitarbeiter_liste .= "</select>";

            // Update
            if ($_GET["ressourcen_id"] != "")
                $update = "true";
            else
                $update = "false";

            // Ausgabe
            $html = "<form method=\"post\" action=\"?page=auftraege&action=anlegen&id={$_GET["id"]}&ressourcen_id={$_GET["ressourcen_id"]}&date=$act_date_ymd\">";
            $html .= "<input type=\"hidden\" name=\"id\" value=\"$auftrag_id\">";
            $html .= "<label>Auftrag</label><input type=\"text\" name=\"betreff\" value=\"$betreff\">";
            $html .= "<label>Notiz</label><textarea name=\"notiz\" rows=\"1\">$notiz</textarea>";
            $html .= "<label>Von (hh:mm/hhmm)</label><input type=\"text\" name=\"von\" value=\"$von\">";
            $html .= "<label>Bis (hh:mm/hhmm)</label><input type=\"text\" name=\"bis\" value=\"$bis\">";
            $html .= "<input type=\"submit\" name=\"speichern\" value=\"speichern\"></form>";

            if (isset($auftrag_id)) {
                $html .= "<form method=\"post\" action=\"?page=auftraege&action=anlegen&date=$act_date_ymd\">";
                $html .= "<input type=\"hidden\" name=\"id\" value=\"$auftrag_id\">";
                $html .= "<input type=\"hidden\" name=\"update\" value=\"$update\">";
                $html .= "<input type=\"hidden\" name=\"ressourcen_id\" value=\"{$_GET["ressourcen_id"]}\">";
                $html .= "<br><fieldset><legend>Ressource Planen</legend>";
                $html .= "$fahrzeug_liste<br>";
                $html .= "$gespann_liste<br>";
                $html .= "$mitarbeiter_liste<br>";
                $html .= "<input type=\"submit\" name=\"ressource_speichern\" value=\"speichern\">";
                $html .= "</fieldset></form>";
            }

            break;

        case "overview" :
            $sql -> query("select * from auftraege where id = {$_GET["id"]}");

            while ($row = $sql -> fetchObject()) {
                $tpl -> auftrag = $row;

                $sql2 = new sql();
                $sql2 -> query("select * from geplante_ressourcen where auftraege_id = $row->id");

                //echo "select * from geplante_ressourcen where auftraege_id = $row->id";

                $ressourcen = Array();
                while ($row2 = $sql2 -> fetchObject()) {
                    $sql3 = new sql();

                    // Fahrzeug
                    $sql3 -> query("select * from fuhrpark where id = $row2->fahrzeug_id");
                    $row3 = $sql3 -> fetchObject();
                    $fahrzeug = $row3 -> fahrzeug;

                    // Gespann
                    $sql3 -> query("select * from fuhrpark where id = $row2->gespann_id");
                    $row3 = $sql3 -> fetchObject();
                    $gespann = $row3 -> fahrzeug;

                    // Mitarbeiter
                    $sql3 -> query("select * from mitarbeiter where id = '$row2->mitarbeiter_id'");
                    $row3 = $sql3 -> fetchObject();
                    $name = $row3 -> name;
                    $vorname = $row3 -> vorname;

                    // Array
                    array_push($ressourcen, array("fahrzeug" => $fahrzeug, "gespann" => $gespann, "name" => $name, "vorname" => $vorname, "edit" => "?page=auftraege&action=anlegen&id=$row->id&ressourcen_id=$row2->id&date=$act_date_ymd", "delete" => "javascript:deleteRes($row->id,$row2->id,$act_date_ymd);"));

                }

                $tpl -> res = $ressourcen;

                $tpl -> edit = "?page=auftraege&action=anlegen&id=$row->id&date=$act_date_ymd";
                $tpl -> delete = "";

                $html = $tpl -> fetch("tpl/order_details.tpl.php");
            }
            break;
        // default
        default :
            $html = "Action nicht vorhanden!";
    }
} catch (Exception $e) {
    $e -> showError();
}

echo $html;
?>