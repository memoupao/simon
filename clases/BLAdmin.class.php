<?php
require_once ("BLBase.class.php");

class BLAdmin extends BLBase
{

    var $fecha;

    var $Session;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }
    
    // Esta funcion es muy importante, ya que las funciones dependen de la conexion
    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }
    // -----------------------------------------------------------------------------
    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    function ListaAnios()
    {
        return $this->ExecuteQuery("SELECT * FROM adm_anios 
							WHERE flg_act='1' 
							ORDER BY num_anio ASC ;");
    }

    function TipoUsuarios()
    {
        return $this->ListaTipoUsuario(0);
    }

    function ListaTipoUsuario($id)
    {
        $sql = "SELECT codi, descrip 
		FROM adm_tablas_aux 
		WHERE idTabla='$id' ORDER BY descrip ASC";
        return $this->ExecuteQuery($sql);
    }
    
    // egion Usuarios
    function ListaUsuarios($id)
    {
        $sql = "SELECT coduser, tipo_user, nom_user, clave_user, mail, estado 
		FROM adm_usuarios 
		WHERE tipo_user='$id' ORDER BY nom_user ASC";
        return $this->ExecuteQuery($sql);
    }

    function NuevoUsuario()
    {
        // $codigo = $this->Autogenerate("adm_usuarios","coduser", "");
        $arrayfields = array(
            "coduser",
            "tipo_user",
            "nom_user",
            "clave_user",
            "mail",
            "t01_id_uni",
            "estado",
            "usr_crea",
            "fch_crea",
            "usr_actu",
            "fch_actu"
        );
        $arrayvalues = array(
            $_POST['txtcodigo'],
            $_POST['cboTipo'],
            $_POST['txtnombre'],
            md5($_POST['txtclave']),
            $_POST['txtemail'],
            $_POST['idunidad'],
            $_POST['cboEstado'],
            $this->Session->UserID,
            $this->fecha,
            "",
            ""
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("adm_usuarios", $arrayfields, $arrayvalues);
        
        $ret = $this->ExecuteCreate($sql1);
        
        return $ret;
    }

    function GetUsuario($codigo)
    {
        $sql = "SELECT coduser, tipo_user, nom_user, clave_user, mail, estado, t01_id_uni
		FROM  adm_usuarios
		WHERE coduser='$codigo' ";
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        
        return $row;
    }

    function ActualizarUsuario()
    {
        if ($_POST['txtclave'] != "") {
            $arrayfields = array(
                "coduser",
                "tipo_user",
                "nom_user",
                "clave_user",
                "mail",
                "t01_id_uni",
                "estado",
                "usr_actu",
                "fch_actu"
            );
            $arrayvalues = array(
                $_POST['txtcodigo'],
                $_POST['cboTipo'],
                $_POST['txtnombre'],
                md5($_POST['txtclave']),
                $_POST['txtemail'],
                $_POST['idunidad'],
                $_POST['cboEstado'],
                $this->Session->UserID,
                $this->fecha
            );
        } else {
            $arrayfields = array(
                "coduser",
                "tipo_user",
                "nom_user",
                "mail",
                "t01_id_uni",
                "estado",
                "usr_actu",
                "fch_actu"
            );
            $arrayvalues = array(
                $_POST['txtcodigo'],
                $_POST['cboTipo'],
                $_POST['txtnombre'],
                $_POST['txtemail'],
                $_POST['idunidad'],
                $_POST['cboEstado'],
                $this->Session->UserID,
                $this->fecha
            );
        }
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("adm_usuarios", $arrayfields, $arrayvalues, "coduser='" . $_POST['id'] . "'");
        
        $ret = $this->ExecuteUpdate($sql1);
        
        return $ret;
    }

    function EliminarUsuario($idUnidad)
    {
        $sql = "DELETE FROM adm_usuarios WHERE  coduser='" . $idUnidad . "'";
        $ret = $this->ExecuteDelete($sql);
        
        return $ret;
    }
    // nd Region
    
    // egion Menus x Perfil
    function ListaMenusPerfilAct($idPerfil)
    {
        $sql = "SELECT mp.mnu_cod, 
       mp.per_cod,
       m.mnu_nomb,
       m.mnu_link,
       m.mnu_activo,
       t.descrip as Perfil
  FROM adm_menus_perfil mp 
  INNER JOIN adm_menus m ON (m.mnu_cod=mp.mnu_cod)
  INNER JOIN adm_tablas_aux t ON (mp.per_cod=t.codi)
  WHERE
      mp.per_cod = $idPerfil  ;";
        return $this->ExecuteQuery($sql);
    }

    function ListaMenusPerfilInact($idPerfil)
    {
        $sql = "SELECT m.mnu_cod, 
       m.mnu_nomb,
       m.mnu_link,
       m.mnu_activo
  FROM adm_menus m 
  WHERE
        m.mnu_cod NOT IN ( SELECT mp.mnu_cod  FROM adm_menus_perfil mp
                            WHERE mp.per_cod = $idPerfil  ) 
    AND IFNULL(m.mnu_parent,'')<>'' AND m.mnu_link <> '#' 
  ORDER BY m.mnu_cod; ";
        return $this->ExecuteQuery($sql);
    }

    function NuevoMenuPerfil($idPerfil, $idMenu)
    {
        $arrayfields = array(
            "mnu_cod",
            "per_cod"
        );
        $arrayvalues = array(
            $idMenu,
            $idPerfil
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("adm_menus_perfil", $arrayfields, $arrayvalues);
        
        $ret = $this->ExecuteCreate($sql1);
        
        return $ret;
    }

    function EliminarMenuPerfil($idPerfil)
    {
        $sql1 = "delete from adm_menus_perfil where per_cod=$idPerfil";
        
        $ret = $this->ExecuteDelete($sql1);
        
        return $ret;
    }
    
    // nd Region
    
    // fin de la Clase BLAdmin
}

?>
