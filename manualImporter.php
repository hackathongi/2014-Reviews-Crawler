<?php
/**
 * Created by PhpStorm.
 * User: alex@volcanicinternet.cat
 * Date: 3/8/14
 * Time: 9:53 AM
 */
include_once 'template_ekomi.php';
include_once 'template_trustedShops.php';

//$bot = new eKomiImporter();
//$bot->run('https://www.ekomi.es/testimonios-rastreatorcom.html');

$bot = new trustedShopsImporter();
$bot->run('https://www.trustedshops.es/evaluacion/info_XE6858272B93E59E044B95103208AC479.html');

?>