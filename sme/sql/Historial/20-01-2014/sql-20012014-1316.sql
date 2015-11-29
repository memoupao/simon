/*
// -------------------------------------------------->
// DA 2.0 [20-01-2014 13:16]
// MB indico que el objetivo es simplificar el proceso por lo que las opciones siguientes
// ya no serán gestionadas en el sistema y sean desactivadas: Autorización para Giro
// de Cheques, Control de Pago a SE, Autorización de Giro de Cheques a SE
// 
// --------------------------------------------------<
*/

DELETE FROM adm_menus_perfil where mnu_cod IN( 'MNU4700','MNU4900','MNU4910');


