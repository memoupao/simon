<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");

$Folder = basename(dirname(dirname(__FILE__)));
$Host = $_SERVER['HTTP_HOST'];

define('FOLDER', $Folder, false );
define('APP_PATH', $_SERVER['DOCUMENT_ROOT'].'/'.$Folder.'/', false );
define('PATH_IMG', 'http://'.$Host.'/'.$Folder.'/img/', false );
define('PATH_JS', 'http://'.$Host.'/'.$Folder.'/js/', false );
define('PATH_CSS', 'http://'.$Host.'/'.$Folder.'/css/', false );
define('PATH_SME', 'http://'.$Host.'/'.$Folder.'/sme/', false );
define('PATH_RPT', 'http://'.$Host.'/'.$Folder.'/sme/reportes/', false );

define('PATH_CLASS', $_SERVER['DOCUMENT_ROOT'].'/'.$Folder.'/'."clases/", false );
define('PATH_LIBRERIAS', $_SERVER['DOCUMENT_ROOT'].'/'.$Folder.'/'."lib/", false );
define('PATH_TEMP_UPLOAD', $_SERVER['DOCUMENT_ROOT'].'/'.$Folder.'/'."tempupload/", false );

define('VIRTUAL_PATH', 'http://'.$Host, false );
define('DOCS_PATH', 'http://'.$Host.'/'.$Folder.'/', false );

$Host = NULL;

define('DB_HOST', 'localhost', false );

if (strstr($Folder,'ctic')) {
	define('DB_NAME', 'ctic', false );
} else if (strstr($Folder,'pg')) {
	define('DB_NAME', 'pg', false );
} else {
	define('DB_NAME', 'pg_test', false );
}

if($_SERVER["SERVER_NAME"]=='181.65.172.204' || $_SERVER["SERVER_NAME"]=='192.168.10.5' ) {
	// Servidor de Producción
    define('DB_USER', 'userdbfe01', false );
    define('DB_PWD', '6(Pdsw(A#^vE', false );

} else if($_SERVER["SERVER_NAME"]=='50.62.212.208') {

    define('DB_USER', 'desarrollo', false );
    define('DB_PWD', 'Sapolio2013$+', false );

} else if( $_SERVER["SERVER_NAME"] == 'sgp.cticservices') {

	define('DB_USER', 'cticservices', false );
	define('DB_PWD', '123456', false );

} else {

	define('DB_USER', 'root', false );
	define('DB_PWD', '123456', false );
}

define('MESES', 12);
session_save_path(APP_PATH.'sessions');