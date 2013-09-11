<table border="0" cellpadding="0" cellspacing="0">
        <th>Auswahl</th>
        <th>Stamm</th>
        <th>Name, Vorname</th>
    <? foreach($this->res as $key => $val) : ?>
        <td><input type="checkbox" name="mitarbeiter[]" value="<? $val["mitarbeiter_id"] ?>"></td>
        <td><input type="checkbox" name="stamm[]" value="<? $val["mitarbeiter_id"] ?>"></td>
        <td><? echo $val["name"] ?>, <? echo $val["nachname"] ?></td>
    <? endforeach; ?>
</table>