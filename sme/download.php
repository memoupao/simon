<?php

include 'includes/constantes.inc.php';
include 'includes/validauser.inc.php';

if ($ObjSession->Authorized()) {
	
	if (isset($_GET['fileurl']) && isset($_GET['filename']) && isset($_GET['path']) ) {
		$fileurl = $_GET['fileurl'];
		$filename = $_GET['filename'];
		$path = $_GET['path'];
		
		$fileurl = $path . '/' . $fileurl;
		
		// required for IE, otherwise Content-disposition is ignored
		if (ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');
		
		// addition by Jorg Weske
		$file_extension = strtolower(substr(strrchr($filename, "."), 1));
		
		if ($filename == "") {
			echo "<html><title>Title Here</title><body>ERROR: Descaga de archivo No Especificado. </body></html>";
			exit();
		} elseif (! file_exists($fileurl)) {
			echo "<html><title>Title Here</title><body>ERROR: Archivo no encontrado.</body></html>";
			exit();
		}
		;
		switch ($file_extension) {
			case "pdf":
				$ctype = "application/pdf";
				break;
			/*case "exe":
				$ctype = "application/octet-stream";
				break;
			case "zip":
				$ctype = "application/zip";
				break;*/
			case "doc":
				$ctype = "application/msword";
				break;
			case "xls":
				$ctype = "application/vnd.ms-excel";
				break;
			case "ppt":
				$ctype = "application/vnd.ms-powerpoint";
				break;
			case "gif":
				$ctype = "image/gif";
				break;
			case "png":
				$ctype = "image/png";
				break;
			case "jpeg":
			case "jpg":
				$ctype = "image/jpg";
				break;
			/*default:
				$ctype = "application/force-download";*/
		}
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false); // required for certain browsers
		header("Content-Type: $ctype");
		// change, added quotes to allow spaces in filenames, by Rajkumar Singh
		header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($fileurl));
		readfile("$fileurl");
		
	}
	
	exit;
	
	
} 

$objFunc->Redirect('login.php');
