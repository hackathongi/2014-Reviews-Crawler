<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/8/14
 * Time: 5:58 PM
 */
include_once 'htmlDownloader.php';

class comunicadorBDD
{

    /*
     * JSON EXAMPLE
     *
     * ATENCION: En el elemento shop puede aparecer "shop_id", esto hace referencia a una tienda que ya existe en nuestra BDD
     *
    {"shop": {
        "shop_name": "123regenwasser.de",
        "shop_url": "http://www.123regenwasser.de",
        "shop_url_competitor": "https://www.trustedshops.es/evaluacion/info_XE6858272B93E59E044B95103208AC479.html",
        "shop_email": "info@dieregensammler.de",
        "shop_phone": "06184 / 929473",
        "shop_logo": "https://www.trustedshops.es/shoplogo/123regenwasser-de_14150.gif"
    },
    "reviews": [
        {
            "description": "Fachkundige und freundliche Beratung und prompte Zustellung der Lieferung. Kann ich nur weiter empfehlen. Auch Material und Aussehen der Regentonne entspricht voll dem Bild und somit auch den Erwartungen.",
            "rating": "5",
            "date": "2013-11-04 00:00",
            "language": "de"
        },
        {
            "description": "Lieferung kam wie angegeben nach 10 Tagen per Spedition. Alles in Ordnung, sehr zu empfehlen",
            "rating": "5",
            "date": "2013-05-13 00:00",
            "language": "de"
        }
    ]
}
     */

    function insertShopAndOpinions($json, $url_competitor){
        print_r($json);
        $downloader = new htmlDownloader();
        $url = 'http://api-test.eshopinion.com/POST/Crawler';
        $sortida = $downloader->get_html($url, $json);
        $this->setAsParsered($url_competitor);
    }

    //Esta funcion debe insertar una nueva url de competitor en BDD... esta url tendra el booleano de is_parsered a false en la BDD
    /*
     * {
    "urls": [
        "https://www.trustedshops.es/evaluacion/info_XABB0FD76AF092B84E9C134FB0B941686.html",
        "https://www.trustedshops.es/evaluacion/info_XCAFC31FCB4521B2D75EBD8DBD6DBE36F.html",
        "https://www.trustedshops.es/evaluacion/info_X5D4D9AE75D508EF1527CEC45E8422981.html",
        "https://www.trustedshops.es/evaluacion/info_X64E4DFE174E2650C57A1F4CF3DD29618.html",
        "https://www.trustedshops.es/evaluacion/info_X070CD9A2293767D7381136BB336B419F.html",
        "https://www.trustedshops.es/evaluacion/info_X4F549D6CD439588534A9152C6D1436EE.html",
        "https://www.trustedshops.es/evaluacion/info_XC42E0681E9404EF6C009D80E50342CCD.html",
        "https://www.trustedshops.es/evaluacion/info_XB23DCE0BEC7230018D8298D6F16F4BD5.html",
        "https://www.trustedshops.es/evaluacion/info_XA7775533611E130D4E6C65E5A10993C3.html"
    ]
}
     *
     */
    function insertNewCompetitors($json){
        $downloader = new htmlDownloader();
        $url = 'http://api-test.eshopinion.com/POST/NewCompetitor';
        $sortida = $downloader->get_html($url, $json);
    }

    //Esta funcion setea como parseada una url competitor de una tienda (is_parsered a true)

    function setAsParsered($url_competitor){
        $downloader = new htmlDownloader();
        $url = 'http://api-test.eshopinion.com/POST/IsParsered';
        $sortida = $downloader->get_html($url, $url_competitor);
    }

    //Esta funcion coje el siguiente url competitor que no esta ya parseado
    function getNextShopCompetitor(){
        $downloader = new htmlDownloader();
        $url = 'http://api-test.eshopinion.com/POST/GetNextShopCompetitor';
        return $url_competitor = $downloader->get_html($url);
    }

}

?>