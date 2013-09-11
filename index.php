<?php
include "config.php";
include "inc/calendar.inc.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>Demo JSON - jQuery Week Calendar</title>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="/libs/jquery-1.4.4.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

        <link rel="stylesheet" href="css/jquery.alerts.css" />
        <script src="libs/jquery.alerts.js"></script>

        <link rel='stylesheet' type='text/css' href='http://planer.lu-blumberg.de/css/default.css' />
        <style type='text/css'>
            body {
                font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
                margin: 0;
            }

            h1 {
                margin: 0;
                padding: 0.5em;
            }

            p.description {
                font-size: 0.8em;
                padding: 0 1em 1em;
                margin: 0;
            }

            #message {
                font-size: 0.7em;
                position: absolute;
                top: 1em;
                right: 1em;
                width: 350px;
                display: none;
                padding: 1em;
                background: #ffc;
                border: 1px solid #dda;
            }
        </style>

        <script>
            $(document).ready(function() {
                $(".sortable").sortable({
                    stop: handleStop
                });
                $(".sortable").disableSelection();
            });

            function deleteRes(auftrag_id, res_id,date) {
                jConfirm('Wollen Sie diesen Datensatz wirklich löschen?', 'Ja', 'Nein', function(confirmed) {
                    if (confirmed) {
                        document.location.href = "?page=auftraege&action=anlegen&id="+auftrag_id+"&ressourcen_id="+res_id+"&date="+date;
                    } else {

                    }
                });
            }

            function handleStop(event, ui) {
                var ul = ui.item.parent().attr("id");

                $("#" + ul + " li").each(function(index) {
                    $.get("ajax/set_pos.php", {id: $(this).attr("id"), pos: index}, function(data) {
                        if (data !== "")
                            alert(data);

                    });
                });
            }


        </script>
    </head>
    <body>
        <div id="wrap">
            <div id="top">
                <div id="cal_buttons">
                    <a href="?page=uebersicht&action=ltm&date=<? echo $kw_backward ?>"><img src="img/backward.png"></a>&nbsp;
                    <a href="?page=uebersicht&action=ltm&date=<? echo $kw_forward ?>"><img src="img/forward.png"></a>
                </div>
            </div>
            <div id="left">         
                <a href="?page=uebersicht&date=<?php echo $act_date_ymd ?>">Übersicht</a><hr>
                <a href="?page=mitarbeiter&action=lm&date=<?php echo $act_date_ymd ?>">Mitarbeiter</a><hr>
                <a href="?page=fuhrpark&action=lf&date=<?php echo $act_date_ymd ?>">Fuhrpark</a><hr>
            </div>
            <div id="col1">         
                <?php
                try {
                    $page = $_GET["page"];
                    if (isset($_GET["page"]) && $page != "") {
                        $inc_file = "pages/$page.php";
                    } else {
                        $inc_file = "pages/index.php";
                    }

                    if (file_exists($inc_file)) {
                        include $inc_file;
                    } else {
                        throw new myException("Die seite '$inc_file' ist nicht vorhanden", __LINE__);
                    }
                } catch (Exception $e) {
                    $e->showError();
                }
                ?>
                <?php echo $_SESSION["error"] ?>
            </div>
            <div id="col2">
                <?php echo $calendar ?>
            </div>
        </div>
        <div id="bottom"></div>

    </body>
</html>
