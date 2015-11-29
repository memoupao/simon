<?php
WriteMenuMain($ObjSession);

function WriteMenuMain($Sesion)
{
    $MenuOptions = $Sesion->ListaMenus(TRUE, $Sesion->PerfilID);
    while ($row = mysqli_fetch_assoc($MenuOptions)) { // Inicio while
        echo ("<li ><a class='" . $row['mnu_class'] . "' href='" . $row['mnu_link'] . "' target='" . $row['mnu_target'] . "'>" . $row['mnu_nomb'] . " &nbsp;</a>   \n");
        WriteMenuItems($Sesion, $row['mnu_cod']);
        echo ("</li>\n");
    } // fin While
    $MenuOptions->free();
}

function WriteMenuItems($Sesion, $strMenuParent)
{
    $SubItems = $Sesion->ListaMenusItems(TRUE, $strMenuParent, $Sesion->PerfilID);
    if (mysqli_num_rows($SubItems) > 0) {
        echo ("<ul> \n");
        while ($r = mysqli_fetch_assoc($SubItems)) { // Inicio while
            $TieneHijos = $r['submenus']; // $Sesion->NumeroSubItems($r['mnu_cod']);
            echo ("<li ><a class='" . $r['mnu_class'] . "' href='" . $r['mnu_link'] . "' target='" . $r['mnu_target'] . "'>" . $r['mnu_nomb'] . "</a>   \n");
            if ($TieneHijos > 0) {
                WriteMenuItems($Sesion, $r['mnu_cod']);
            }
            echo ("</li>\n");
        } // fin While
        $SubItems->free();
        echo ("</ul> \n");
    }
}

?>