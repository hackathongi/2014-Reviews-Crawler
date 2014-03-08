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
$bot->run('https://www.trustedshops.es/evaluacion/info_X217160A51D572EC38A451806E48ADDDD.html');

?>