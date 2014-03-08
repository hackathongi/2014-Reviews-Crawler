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
    function insertShopAndOpinions($json, $url_competitor){
        print_r($json);
        $downloader = new htmlDownloader();
        $url = 'http://api.eshopinion.cat/POST/Crawler';
        $sortida = $downloader->get_html($url, $json); //PETICION API 1
        $this->setAsParsered($url_competitor);
    }

    function setAsParsered($url_competitor){
        $downloader = new htmlDownloader();
        $url = 'http://api.eshopinion.cat/POST/Crawler/IsParsered'; //SETEAR TIENDA COMO PARSEADA
        $sortida = $downloader->get_html($url, $url_competitor); //PETICION API 2
    }

    function insertNewCompetitorUrl($url_new_competitor){
        $downloader = new htmlDownloader();
        $url = 'http://api.eshopinion.cat/POST/Crawler/NewCompetitor'; //SETEAR TIENDA COMO PARSEADA
        $sortida = $downloader->get_html($url, $url_new_competitor); //PETICION API 2
    }
}

?>