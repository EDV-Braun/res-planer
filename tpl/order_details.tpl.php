<table border="0" cellpadding="0" cellspacing="0" class="res_details">
    <tr><th colspan="4"><? echo $this->auftrag->betreff ?> von <? echo date("H:i", strtotime($this->auftrag->von)) ?>  - <? echo date("H:i", strtotime($this->auftrag->bis)) ?> Uhr</th>
        <td align="right" class="buttons"><a href='<? echo $this->edit ?>' title='Ressource Bearbeiten'><img src="img/add.png">&nbsp;<a href="<? echo $this->delete ?>"><img src="img/delete.png"></a></td></tr>
    <? foreach ($this->res as $key => $val) : ?>
        <tr>
            <td><? echo $val["fahrzeug"] ?></td>
            <td><? echo $val["gespann"] ?></td>        
            <td><? echo $val["name"] ?> </td>
            <td><? echo $val["vorname"] ?></td>
            <td align="right" class="buttons"><div><a href='<? echo $val["edit"] ?>' title='Ressource Bearbeiten'><img src="img/edit.png"></a>&nbsp;<a href="<? echo $val["delete"] ?>"><img src="img/delete.png"></a></div></td>
        </tr>
    <? endforeach; ?>
</table>



