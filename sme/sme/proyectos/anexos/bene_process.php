<?php
ini_set('max_execution_time',0); 
include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLBene.class.php");


$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    Guardar($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    Eliminar();
    exit();
}

if (md5("import_data_benef") == $Accion) {
	Import();
	exit;
}

if (md5("generate_template_import_beneficiarios") == $Accion) {
	$idProy = $objFunc->__GET('p');
	generateTemplateForImport($idProy);
	exit;
}




function Guardar($tipo)
{
    if (($_POST['t11_especialidad']) != '255') {
        $otros = '';
    } else {
        $otros = $_POST['t11_especialidad_otros'];
    }
    $t11_cod_bene = $_POST['t11_cod_bene'];
    $t02_cod_proy = $_POST['t02_cod_proy'];
    $t11_dni = $_POST['t11_dni'];
    $t11_ape_pat = $_POST['t11_ape_pat'];
    $t11_ape_mat = $_POST['t11_ape_mat'];
    $t11_nom = $_POST['t11_nom'];
    $t11_sexo = $_POST['t11_sexo'];
    $t11_edad = $_POST['t11_edad'];
    $t11_nivel_educ = $_POST['t11_nivel_educ'];
    $t11_especialidad = $_POST['t11_especialidad'];
    $t11_fec_ini = $_POST['t11_fec_ini'];
    $t11_fec_ter = $_POST['t11_fec_ter'];
    
    $t11_sec_prod_main = $_POST['t11_sec_prod_main'];
    $t11_sec_prod = $_POST['t11_sec_prod'];
    $t11_subsector = $_POST['t11_subsec_prod'];
    $t11_unid_prod_1 = $_POST['t11_unid_prod_1'];
    $t11_tot_unid_prod = $_POST['t11_tot_unid_prod'];    
    $t11_nro_up_b = $_POST['t11_nro_up_b'];
    
    $t11_sec_prod_main_2 = $_POST['t11_sec_prod_main_2'];
    $t11_sec_prod_2 = $_POST['t11_sec_prod_2'];
    $t11_subsec_prod_2 = $_POST['t11_subsec_prod_2'];
    $t11_unid_prod_2 = $_POST['t11_unid_prod_2'];
    $t11_tot_unid_prod_2 = $_POST['t11_tot_unid_prod_2'];    
    $t11_nro_up_b_2 = $_POST['t11_nro_up_b_2'];
    
    $t11_sec_prod_main_3 = $_POST['t11_sec_prod_main_3'];
    $t11_sec_prod_3 = $_POST['t11_sec_prod_3'];
    $t11_subsec_prod_3 = $_POST['t11_subsec_prod_3'];
    $t11_unid_prod_3 = $_POST['t11_unid_prod_3'];
    $t11_tot_unid_prod_3 = $_POST['t11_tot_unid_prod_3'];
    $t11_nro_up_b_3 = $_POST['t11_nro_up_b_3'];
    
    $t11_nom_prod = $_POST['t11_nom_prod'];
    $t11_direccion = $_POST['t11_direccion'];
    $t11_dpto = $_POST['cbodpto'];
    $t11_prov = $_POST['cboprov'];
    $t11_dist = $_POST['cbodist'];
    $t11_ciudad = $_POST['t11_ciudad'];
    $t11_case = $_POST['cbocase'];
    $t11_act_princ = $_POST['t11_act_princ'];
    $t11_telefono = $_POST['t11_telefono'];
    $t11_celular = $_POST['t11_celular'];
    $t11_mail = $_POST['t11_mail'];
    $t11_estado = $_POST['t11_estado'];
    $t11_obs = $_POST['t11_obs'];
    $t11_esp_otros = $otros;
    
    $objBene = new BLBene();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objBene->BeneNuevo($t11_cod_bene, $t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_sec_prod_main, $t11_sec_prod, $t11_subsector, $t11_unid_prod_1, $t11_nro_up_b, $t11_sec_prod_main_2, $t11_sec_prod_2, $t11_subsec_prod_2, $t11_tot_unid_prod, $t11_tot_unid_prod_2, $t11_unid_prod_2, $t11_nro_up_b_2, $t11_sec_prod_main_3 , $t11_sec_prod_3, $t11_subsec_prod_3, $t11_unid_prod_3, $t11_tot_unid_prod_3, $t11_nro_up_b_3, $t11_nom_prod, $t11_direccion, $t11_dpto, $t11_prov, $t11_dist, $t11_ciudad, $t11_case, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objBene->BeneActualizar($t11_cod_bene, $t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_sec_prod_main, $t11_sec_prod, $t11_subsector, $t11_unid_prod_1, $t11_nro_up_b, $t11_sec_prod_main_2, $t11_sec_prod_2, $t11_subsec_prod_2, $t11_tot_unid_prod, $t11_tot_unid_prod_2, $t11_unid_prod_2, $t11_nro_up_b_2, $t11_sec_prod_3, $t11_subsec_prod_3, $t11_sec_prod_main_3, $t11_unid_prod_3, $t11_tot_unid_prod_3, $t11_nro_up_b_3, $t11_nom_prod, $t11_direccion, $t11_dpto, $t11_prov, $t11_dist, $t11_ciudad, $t11_case, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objBene->GetError());
    }
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t02_cod_proy = $_POST['idProy'];
    $t11_cod_bene = $_POST['id'];
    $nombre_bene = $_POST['nom'];
    $objBene = new BLBene();
    $bret = $objBene->BeneEliminar($t02_cod_proy, $t11_cod_bene);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . ($nombre_bene ? $nombre_bene : $t11_cod_bene) . "] !!!");
    } else {
        echo ("ERROR : \n" . $objBene->GetError());
    }
    return $bret;
}


// -------------------------------------------------->
// DA 2.0 [26-01-2014 17:22]
// Nueva funcion de importacion de beneficiarios
function Import()
{
	if (isset($_POST['t02_cod_proy']) && !empty($_POST['t02_cod_proy']) && isset($_FILES['txtNomFile']['name'])) ;
	else {
		echo '<span style="font-size: 11px; font-family: arial; display: block; color: red; font-weight: bold; border:1px solid; padding: 2px; ">Error: Faltan datos. <br/></span>';
		return false;
	}
	
	
	
	date_default_timezone_set('America/Lima');
	
	require_once '../../../lib/phpexcel/PHPExcel/IOFactory.php';
	
	$t02_cod_proy = $_POST['t02_cod_proy'];

	$filename  = basename($_FILES['txtNomFile']['name']);
	$extension = pathinfo($filename, PATHINFO_EXTENSION);
	$new       = md5(uniqid()).'.'.$extension;
	
	$inputFileName = dirname(__FILE__).'/tmp_import/'.$new;
	
	$subido = false; 
	if (move_uploaded_file($_FILES['txtNomFile']['tmp_name'],$inputFileName )) {
		$subido = true;
	}
	
	if ($subido) {
		
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error al leer archivo "' . pathinfo($inputFileName, PATHINFO_BASENAME)
					. '": ' . $e->getMessage());
		}
		
		$objBene = new BLBene();
		
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = 'V'; //$sheet->getHighestColumn();
		
		
		echo '<span style="font-size: 11px; font-family: arial; display: block; color: black; padding: 2px; ">IMPORTANDO BENEFICIARIOS DEL PROYECTO '.$t02_cod_proy.' :. <br/></span>';
		
		
		for ($row = 4; $row <= $highestRow; $row++) {
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

			
			
			$t11_dni = $rowData[0][0];
			$t11_ape_pat = $rowData[0][1];
			$t11_ape_mat = $rowData[0][2];
			$t11_nom = $rowData[0][3];
			$t11_sexo = ($rowData[0][4] == 'Femenino' ? 107 : 106 );
			$t11_edad = $rowData[0][5];
			$t11_telefono = $rowData[0][6];
			$t11_celular = $rowData[0][7];
			$t11_mail = $rowData[0][8];
			
			$art11_nivel_educ = explode('-',$rowData[0][9]);
			$t11_nivel_educ = (int)$art11_nivel_educ[0];
			
			$art11_especialidad = explode('-',$rowData[0][10]);
			$t11_especialidad = (int)$art11_especialidad[0];
			
			$t11_esp_otros = ($t11_especialidad == 255 ? $rowData[0][11] : '');
			
			$t11_fec_ini = '';
			$t11_fec_ter = '';
			if (!empty($rowData[0][12])) {
				$date = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][12]);
				$t11_fec_ini = date('Y-m-d',$date);
			}
			
			if (!empty($rowData[0][13])) {
				$date = PHPExcel_Shared_Date::ExcelToPHP($rowData[0][13]);
				$t11_fec_ter = date('Y-m-d',$date);
			}

			
			
			$text_t11_dpto = str_replace('_',' ',$rowData[0][14]);
			$text_t11_dpto = trim($text_t11_dpto);
			$text_t11_dpto = strtoupper($text_t11_dpto);
			
			$text_t11_prov = str_replace('_',' ',$rowData[0][15]);
			$text_t11_prov = trim($text_t11_prov);
			
			$text_t11_dist = str_replace('_',' ',$rowData[0][16]);
			$text_t11_dist = trim($text_t11_dist);
			
			
			$centro_poblado = trim($rowData[0][17]);
			$centro_poblado = strtoupper($centro_poblado);
			
			$t11_direccion = trim($rowData[0][18]);
			$t11_ciudad = trim($rowData[0][19]);
			$t11_act_princ = trim($rowData[0][20]);
						
			$t11_estado = 113;
			$t11_obs = trim($rowData[0][21]);
			$t11_obs = strip_tags($t11_obs);
			
			
			if (!empty($t11_dni) && !empty($t11_ape_pat) && !empty($t11_ape_mat) && !empty($t11_nom)
				 && !empty($t11_sexo)  && !empty($t11_edad) 				 
				 && !empty($text_t11_dpto)  && !empty($text_t11_prov) && !empty($text_t11_dist) 
				 && !empty($centro_poblado) 
				) 
			{
				if (strlen($t11_dni) == 8) {
					$ret = $objBene->importBeneNuevos($t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_direccion, $t11_ciudad, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros,$text_t11_dpto, $text_t11_prov, $text_t11_dist, $centro_poblado);
					
					if ($ret) {
						echo '<span style="font-size: 11px; font-family: arial; display: block; color: green; font-weight: bold; border:1px solid; padding: 2px;">OK: '.implode(', ',$rowData[0]).'<br/></span>';
					} else {
						echo '<span style="font-size: 11px; font-family: arial; display: block; color: red; font-weight: bold; border:1px solid; padding: 2px; ">Error: Fila '.$row.'. '.($objBene->Error).'.<br/></span>';
					}
					
				} else {
					echo '<span style="font-size: 11px; font-family: arial; display: block; color: red; font-weight: bold; border:1px solid; padding: 2px; ">Error: Fila '.$row.'. El DNI no es valido.<br/></span>';
				}	
				
			} else {

				echo '<span style="font-size: 11px; font-family: arial; display: block; color: red; font-weight: bold; border:1px solid; padding: 2px; ">Error: En la Fila '.$row.' no contiene los datos importantes u obligatorios completos. <br/></span>';
			}
								
		}
		
		@unlink($inputFileName);
		echo '<script>with(parent){ dsLista.loadData(); }; alert("La Importación ha terminado satisfactoriamente.");</script>';
		
	}
	
}

// -------------------------------------------------->
// DA 2.0 [05-02-2014 11:27]
// Nueva funcion de generacion de plantilla para importar beneficiarios
function generateTemplateForImport($idProy)
{
	
	date_default_timezone_set('America/Lima');
	
	
	require_once '../../../lib/phpexcel/PHPExcel.php';
	require_once '../../../lib/phpexcel/PHPExcel/Writer/Excel2007.php';
	
	
	try {
		$inputFileName = dirname(__FILE__).'/../../../tpl/import-benef.xlsx';
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch (Exception $e) {
		die('Error al leer archivo "' . pathinfo($inputFileName, PATHINFO_BASENAME)
				. '": ' . $e->getMessage());
	}
	
	$objPHPExcel->getProperties()->setCreator("Fondoempleo")
	->setLastModifiedBy("Fondoempleo")
	->setTitle("Beneficiarios del Proyecto")
	->setSubject("Plantilla de importacion de datos")
	->setDescription("Beneficiarios del Proyecto - Office 2007 XLSX")
	->setKeywords("fondoemplo beneficiarios proyectos")
	->setCategory("Beneficiarios del Proyecto");
	
	
	if($idProy) {
		
		require (constant('PATH_CLASS') . "BLTablas.class.php");
		require (constant('PATH_CLASS') . "BLTablasAux.class.php");
	
		$OjbTab = new BLTablasAux();
	
		$nroFilas = 503;			
	
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', $idProy);
		
		/// Lista para el valor del Sexo:
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$fila, '');
			$objValidation = $objPHPExcel->getActiveSheet()->getCell('E'.$fila)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('El valor no es correcto.');
			$objValidation->setPromptTitle('Seleccione de la lista');
			$objValidation->setPrompt('Seleccione un item de la lista.');
			$objValidation->setFormula1('"Masculino,Femenino"');
		}
		
		
		// Lista para el valor de Grando de Instruccion:		
		$rsInstruccion = $OjbTab->NivelEdu();
		$arInstruccion = array();
		$pos = $posini = 4;
		while ($rwInstucc = mysql_fetch_array($rsInstruccion)) {
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$pos, $rwInstucc['codigo'].' - '.$rwInstucc['descripcion']);			
			$pos++;
		}
		
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$fila, '');
			$objValidation2 = $objPHPExcel->getActiveSheet()->getCell('J'.$fila)->getDataValidation();
			$objValidation2->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation2->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowErrorMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('El valor no es correcto.');
			$objValidation2->setPromptTitle('Seleccione de la lista');
			$objValidation2->setPrompt('Seleccione un item de la lista.');
			$objValidation2->setFormula1('LISTAS!$B$'.$posini.':$B$'.($pos-1));
		}
		
		
				
		// Lista para el valor de Especialidad:		
		$rsEspecialidad = $OjbTab->EspecialidadPer();
		$pos = $posini = 4;
		while ($rwEspecia = mysql_fetch_array($rsEspecialidad)) {
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$pos, $rwEspecia['codigo'].' - '.$rwEspecia['descripcion']);
			$pos++;
		}
		
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$fila, '');
			$objValidation3 = $objPHPExcel->getActiveSheet()->getCell('K'.$fila)->getDataValidation();
			$objValidation3->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation3->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation3->setAllowBlank(false);
			$objValidation3->setShowInputMessage(true);
			$objValidation3->setShowErrorMessage(true);
			$objValidation3->setShowDropDown(true);
			$objValidation3->setErrorTitle('Input error');
			$objValidation3->setError('El valor no es correcto.');
			$objValidation3->setPromptTitle('Seleccione de la lista');
			$objValidation3->setPrompt('Seleccione un item de la lista.');
			$objValidation3->setFormula1('LISTAS!$C$'.$posini.':$C$'.($pos-1));
		}		
		
		
				
		
		/// Listado de Departamentos:
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$fila, '');
			$objValidation4 = $objPHPExcel->getActiveSheet()->getCell('O'.$fila)->getDataValidation();
			$objValidation4->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation4->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation4->setAllowBlank(false);
			$objValidation4->setShowInputMessage(true);
			$objValidation4->setShowErrorMessage(true);
			$objValidation4->setShowDropDown(true);
			$objValidation4->setErrorTitle('Input error');
			$objValidation4->setError('El valor no es correcto.');
			$objValidation4->setPromptTitle('Seleccione de la lista');
			$objValidation4->setPrompt('Seleccione un item de la lista.');
			$objValidation4->setFormula1('departamentos');
		}		
		
		
		
		/// Listado de Provincias:
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$fila, '');
			$objValidation5 = $objPHPExcel->getActiveSheet()->getCell('P'.$fila)->getDataValidation();
			$objValidation5->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation5->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation5->setAllowBlank(false);
			$objValidation5->setShowInputMessage(true);
			$objValidation5->setShowErrorMessage(true);
			$objValidation5->setShowDropDown(true);
			$objValidation5->setErrorTitle('Input error');
			$objValidation5->setError('El valor no es correcto.');
			$objValidation5->setPromptTitle('Seleccione de la lista');
			$objValidation5->setPrompt('Seleccione un item de la lista.');
			$objValidation5->setFormula1('INDIRECT($O$'.$fila.')');
		}
		
		
		
		/// Listado de Distritos:	
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$fila, '');
			$objValidation6 = $objPHPExcel->getActiveSheet()->getCell('Q'.$fila)->getDataValidation();
			$objValidation6->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation6->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation6->setAllowBlank(false);
			$objValidation6->setShowInputMessage(true);
			$objValidation6->setShowErrorMessage(true);
			$objValidation6->setShowDropDown(true);
			$objValidation6->setErrorTitle('Input error');
			$objValidation6->setError('El valor no es correcto.');
			$objValidation6->setPromptTitle('Seleccione de la lista');
			$objValidation6->setPrompt('Seleccione un item de la lista.');
			$objValidation6->setFormula1('INDIRECT($P$'.$fila.')');
		}
		
		
		
		
		//Listas de Centros Poblados:
		
		/*$rsCentrosPoblados = $OjbTab->getListCentrosPoblados();
		$pos = $posini = 1;
		while ($rwCentrosPoblados = mysql_fetch_array($rsCentrosPoblados)) {
			$objPHPExcel->setActiveSheetIndex(3)->setCellValue('A'.$pos, $rwCentrosPoblados['codigo'].' - '.$rwCentrosPoblados['centro']);
			$pos++;
		}
		*/
		
		for ($fila=4; $fila<=$nroFilas; $fila++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$fila, '');
			$objValidation3 = $objPHPExcel->getActiveSheet()->getCell('R'.$fila)->getDataValidation();
			$objValidation3->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation3->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation3->setAllowBlank(false);
			$objValidation3->setShowInputMessage(true);
			$objValidation3->setShowErrorMessage(true);
			$objValidation3->setShowDropDown(true);
			$objValidation3->setErrorTitle('Input error');
			$objValidation3->setError('El valor no es correcto.');
			$objValidation3->setPromptTitle('Seleccione de la lista');
			$objValidation3->setPrompt('Seleccione un item de la lista.');
			$objValidation3->setFormula1('CENTROSPOBLADOS!$B$3:$B$31406');
		}
		
		
		
		
		
		
		
		

		$objPHPExcel->setActiveSheetIndex(0);
	
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="plantilla_beneficiarios.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
					
	
	}
	
	
	
	

}





function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

ob_end_flush();
