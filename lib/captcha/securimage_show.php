<?php
/**
 * CticServices
 *
 * Genera una imagen captcha para la validacion en el ingreso al sistema
 *
 * @package lib\Captcha
 * @author DA
 * @since Version 2.0
 *
 * @param string sid  Cadena aleatoria para evitar el imagenes en la cache
 *
 */
require_once 'securimage.php';

$img = new securimage(array(       
    'num_lines' => 3,
    'noise_level' => 0    
));


$img->perturbation = 0;
$img->code_length = 5;
$img->image_width = 150;
$img->text_color      = new Securimage_Color("#000000");
$img->image_height = (int)($img->image_width * 0.35);
$img->charset = 'abcdefghklmnprstuvwyz123456789';
$img->show();
