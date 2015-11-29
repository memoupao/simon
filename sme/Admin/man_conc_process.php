<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLMantenimiento.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) 

{
    
    GuardarConcurso($Accion);
    
    exit();
}

if (md5("ajax_del") == $Accion) 

{
    
    EliminarConcurso();
    
    exit();
}

?>
<?php

// egion Mantenimiento de Perfiles
function GuardarConcurso($tipo)

{
    $objMante = new BLMantenimiento();
    
    $bret = false;
    
    if ($tipo == md5("ajax_new")) 

    {
        
        $bret = $objMante->ConcursoNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) 

    {
        
        $bret = $objMante->ConcursoActualizar();
    }
    
    ob_clean();
    
    ob_start();
    
    if ($bret) 

    {
        
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } 

    else 

    {
        
        echo ("ERROR : \n" . $objMante->GetError());
    }
    
    return $bret;
}

function EliminarConcurso()

{
    ob_clean();
    
    ob_start();
    
    $objMante = new BLMantenimiento();
    
    $bret = false;
    
    $idConcurso = $_POST['id'];
    
    $bret = $objMante->ConcursoEliminar($idConcurso);
    
    if ($bret) 

    {
        echo ("Exito Se Elimino correctamente el Concurso [" . $idConcurso . "]!");
    } 

    else 

    {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    
    return $bret;
}

// nd Region

?>

<?php ob_end_flush(); ?>