/*
// -------------------------------------------------->
// AQ 2.0 [21-01-2014 20:07]
// Generación de datos de Años Anteriores
// para el Plan Operativo
// --------------------------------------------------<
*/
/* C-12-49 */
/******************** Indicador ***************************/
/* Versión 3 */
INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_ind_anio, a.t09_ind_mes, a.t09_ind_mta, 'ieam', NOW(), NULL, NULL, 1 
FROM t09_act_ind_mtas a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_ind_anio, '-', a.t09_ind_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_ind_anio, '-', b.t09_ind_mes) FROM t09_act_ind_mtas b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_ind_anio, a.t09_ind_mes, a.t09_ind_mta, 'ieam', NOW(), NULL, NULL, 1 
FROM t09_act_ind_mtas a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_ind_anio, '-', a.t09_ind_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_ind_anio, '-', b.t09_ind_mes) FROM t09_act_ind_mtas b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);

/******************** Característica ***************************/
/* Versión 3 */
INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t09_car_anio, a.t09_car_mes, a.t09_car_ctrl, 'ieam', NOW(), NULL, NULL, 1 
FROM t09_act_ind_car_ctrl a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t09_car_anio, '-', a.t09_car_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t09_car_anio, '-', b.t09_car_mes) FROM t09_act_ind_car_ctrl b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t09_car_anio, a.t09_car_mes, a.t09_car_ctrl, 'ieam', NOW(), NULL, NULL, 1 
FROM t09_act_ind_car_ctrl a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t09_car_anio, '-', a.t09_car_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t09_car_anio, '-', b.t09_car_mes) FROM t09_act_ind_car_ctrl b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);

/******************** Entregables Indicador ***************************/
/* Versión 3 */
INSERT INTO t02_entregable_act_ind (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t02_anio, t02_mes, t09_cod_act_ind_val, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t02_anio, a.t02_mes, a.t09_cod_act_ind_val, 'ieam', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t02_entregable_act_ind (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t02_anio, t02_mes, t09_cod_act_ind_val, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t02_anio, a.t02_mes, a.t09_cod_act_ind_val, 'ieam', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);


/******************** Entregables Indicador Característica ***************************/
/* Versión 3 */
INSERT INTO t02_entregable_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t02_anio, a.t02_mes, 'ieam', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind_car a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind_car b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t02_entregable_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t02_anio, a.t02_mes, 'ieam', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind_car a
WHERE a.t02_cod_proy='C-12-49' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind_car b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);


/* C-12-47 */
/******************** Indicador ***************************/
/* Versión 3 */
INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_ind_anio, a.t09_ind_mes, a.t09_ind_mta, 'iekm', NOW(), NULL, NULL, 1 
FROM t09_act_ind_mtas a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_ind_anio, '-', a.t09_ind_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_ind_anio, '-', b.t09_ind_mes) FROM t09_act_ind_mtas b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_ind_anio, a.t09_ind_mes, a.t09_ind_mta, 'iekm', NOW(), NULL, NULL, 1 
FROM t09_act_ind_mtas a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_ind_anio, '-', a.t09_ind_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_ind_anio, '-', b.t09_ind_mes) FROM t09_act_ind_mtas b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);

/******************** Característica ***************************/
/* Versión 3 */
INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t09_car_anio, a.t09_car_mes, a.t09_car_ctrl, 'iekm', NOW(), NULL, NULL, 1 
FROM t09_act_ind_car_ctrl a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t09_car_anio, '-', a.t09_car_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t09_car_anio, '-', b.t09_car_mes) FROM t09_act_ind_car_ctrl b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t09_car_anio, a.t09_car_mes, a.t09_car_ctrl, 'iekm', NOW(), NULL, NULL, 1 
FROM t09_act_ind_car_ctrl a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t09_car_anio, '-', a.t09_car_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t09_car_anio, '-', b.t09_car_mes) FROM t09_act_ind_car_ctrl b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);

/******************** Entregables Indicador ***************************/
/* Versión 3 */
INSERT INTO t02_entregable_act_ind (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t02_anio, t02_mes, t09_cod_act_ind_val, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t02_anio, a.t02_mes, a.t09_cod_act_ind_val, 'iekm', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t02_entregable_act_ind (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t02_anio, t02_mes, t09_cod_act_ind_val, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t02_anio, a.t02_mes, a.t09_cod_act_ind_val, 'iekm', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);


/******************** Entregables Indicador Característica ***************************/
/* Versión 3 */
INSERT INTO t02_entregable_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 3, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t02_anio, a.t02_mes, 'iekm', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind_car a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=2
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind_car b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=3);

/* Versión 4 */
INSERT INTO t02_entregable_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
SELECT a.t02_cod_proy, 4, a.t08_cod_comp, a.t09_cod_act, a.t09_cod_act_ind, a.t09_cod_act_ind_car, a.t02_anio, a.t02_mes, 'iekm', NOW(), NULL, NULL, 1 
FROM t02_entregable_act_ind_car a
WHERE a.t02_cod_proy='C-12-47' 
AND a.t02_version=3
AND CONCAT(a.t02_cod_proy, '-', a.t08_cod_comp, '-', a.t09_cod_act, '-', a.t09_cod_act_ind, '-', a.t09_cod_act_ind_car, '-', a.t02_anio, '-', a.t02_mes) NOT IN (SELECT CONCAT(b.t02_cod_proy, '-', b.t08_cod_comp, '-', b.t09_cod_act, '-', b.t09_cod_act_ind, '-', b.t09_cod_act_ind_car, '-', b.t02_anio, '-', b.t02_mes) FROM t02_entregable_act_ind_car b WHERE b.t02_cod_proy=a.t02_cod_proy AND b.t02_version=4);

