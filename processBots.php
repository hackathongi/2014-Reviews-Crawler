<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/8/14
 * Time: 7:59 PM
 */

include_once 'template_ekomi.php';
include_once 'template_trustedShops.php';

$shopToParse = getNextShopCompetitor();
while ($shopToParse){
    if (stristr($shopToParse, 'ekomi')){
        $bot = new eKomiImporter();
        $bot->run($shopToParse);
    }elseif (stristr($shopToParse, 'trustedshops')){
        $bot = new trustedShopsImporter();
        $bot->run($shopToParse);
    }

    $shopToParse = getNextShopCompetitor();
}

?>