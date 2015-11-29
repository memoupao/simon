/*
// -------------------------------------------------->
// AQ 2.0 [15-12-2013 12:30]
// Cambio de Estados de Informe
// --------------------------------------------------<
// Elaboracion - Elaboración
// Revision - Revisión
// Correccion - Corrección
// V.B. Monitor - V°B° Gestor de Proyecto
// V.B. MT - V°B° GP
// Aprobado CMT - Área Aprobado Responsable de Área
// Aprobado CMT - Aprobado RA
*/
UPDATE adm_tablas_aux SET descrip = 'Elaboración', abrev = 'Elaboración' WHERE idTabla = 15 AND codi = 45;
UPDATE adm_tablas_aux SET descrip = 'Revisión', abrev = 'Revisión' WHERE idTabla = 15 AND codi = 46;
UPDATE adm_tablas_aux SET descrip = 'Corrección', abrev = 'Corrección' WHERE idTabla = 15 AND codi = 47;
UPDATE adm_tablas_aux SET descrip = 'V°B°', abrev = 'V°B°' WHERE idTabla = 15 AND codi = 135;
UPDATE adm_tablas_aux SET descrip = 'Aprobado', abrev = 'Aprobado' WHERE idTabla = 15 AND codi = 257;

UPDATE adm_tablas_aux SET descrip = 'Elaboración', abrev = 'Elaboración' WHERE idTabla = 32 AND codi = 258;
UPDATE adm_tablas_aux SET descrip = 'Revisión', abrev = 'Revisión' WHERE idTabla = 32 AND codi = 259;
UPDATE adm_tablas_aux SET descrip = 'Corrección', abrev = 'Corrección' WHERE idTabla = 32 AND codi = 260;
UPDATE adm_tablas_aux SET descrip = 'V°B°', abrev = 'V°B°' WHERE idTabla = 32 AND codi = 261;
UPDATE adm_tablas_aux SET descrip = 'Aprobado', abrev = 'Aprobado' WHERE idTabla = 32 AND codi = 262;

/*
// -------------------------------------------------->
// AQ 2.0 [17-12-2013 11:48]
// Se reutiliza el perfil ADM para convertirlo en FE
// que será el superusuario en la parte funcional
// --------------------------------------------------<
*/
UPDATE pg.adm_perfiles SET per_des = 'FondoEmpleo', per_abrev = 'FE' WHERE adm_perfiles.per_cod = 10;
