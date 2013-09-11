<?php
$date = $_GET["date"];

if (date("D",strtotime($date)) != "Mon") {
    $heute = date("Ymd", strtotime("last monday", strtotime($date)));
} else {
    $heute = date("Ymd", strtotime($date));
}

// Ermittlung des aktuellen Datum
if ($date == "")
    $act_date = $heute;
else
    $act_date = $date;

// Aktuelles Datum, ohne Umwandlung in Timestamp
$act_date_ymd = $act_date;

// String Umwandlung in Timestamp
$act_date = strtotime("$act_date -0 days");

$tag[0] = "So";
$tag[1] = "Mo";
$tag[2] = "Di";
$tag[3] = "Mi";
$tag[4] = "Do";
$tag[5] = "Fr";
$tag[6] = "Sa";

// Aktuelles Datum in Deutsch
$w = date("w", $act_date);
$act_date_german = date("d.m.Y", $act_date);
$act_date_german = "$act_date_german ({$tag[$w]})";

$calendar = "<table id='calendar' cellpadding=0 cellspacing=0>";
$calendar .= "<tr>";
// Wochentage werden erstellt
for ($x = 0; $x < 7; $x++) {
    $converted_date = strtotime("+$x days", strtotime($heute));

    // Ausgewählter Tag wird ermittelt
    $class = "";
    if ($converted_date == $act_date) {
        $class = "class='act' ";
    }

    $wochentag = date("w", $converted_date);
    $date = date("d.m.Y", $converted_date);

    $calendar .= "<th $class>$date<br>(" . $tag[$wochentag] . ")</th>";
}
$calendar .= "</tr><tr>";

// Einträge werden geladen
for ($x = 0; $x < 7; $x++) {
    $converted_date = strtotime("+$x days", strtotime($heute));

    // Ausgewählter Tag wird ermittelt
    $class = "";
    if ($converted_date == $act_date) {
        $class = "class='act' ";
    }

    $wochentag = date("w", $converted_date);
    $date = date("Ymd", $converted_date);

    // Hier kommen die Einträge hin
    try {
        $sql -> query("select * from auftraege where datum = '$date' order by pos;");
        $data = "<ul class=\"sortable\" id=\"$date\">";
        while ($row = $sql -> fetchObject()) {
            $von = date("H:i", strtotime($row -> von));
            $bis = date("H:i", strtotime($row -> bis));
            $data .= "<li class=\"ui-state-default\" id=\"$row->id\"><a href=\"?page=auftraege&action=overview&id=$row->id&date=$date\">$row->betreff ($von - $bis)</li>";
        }
        $data .= "</ul>";

        $calendar .= "<td $class onclick='document.location.href=\"?date=$date\"'>$data</td>";
    } catch (Exception $e) {
        $e -> showError();
    }

}
$calendar .= "</tr>";
$calendar .= "</table>";

$kw_backward = date("Ymd",strtotime("last week",strtotime($heute)));
$kw_forward = date("Ymd",strtotime("next week",strtotime($heute)));

$kw = date("W",strtotime($heute));
?>