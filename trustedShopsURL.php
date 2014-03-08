<?php

//includes
include_once 'htmlDownloader.php';
include_once 'constants.php';
//RUN
parseShop('http://www.trustedshops.es/b2c_int/getdata.php?module=search_shop2&format=json&q=.');

class TrustedShopsURL {
    function parseShop($url,$filepath){
       $downloader = new htmlDownloader();
       $html = $downloader->get_html($url);
       
       if (preg_match('|\[(.*)$|is', $html, $adInfo)){
           if(preg_match_all('|"(\d+)"|is'$adInfo[1], $id) {
                $shops = '';
                for ($i = 0; $i < count($id[0]); $i++) {
                    $link = 'http://www.trustedshops.es/b2c_int/getdata.php?module=shopData&format=json';
                    for ($j = 0; $j < 85; $j++) {
                        $link += '&shops%5B%5D='.$id[$i];
                    }
                    $shops = parseShopURL($link);
                }
                $handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
                fwrite($handle, $shops);
           }
       }
    }

    function parseShopURL($url) {
       $downloader = new htmlDownloader();
       $html = $downloader->get_html($url);
       
       if (preg_match_all('|"url":"([^"]+)"|is', $html, $adInfo)){
            $shops = '';
            for ($i = 0; $i < count($adInfo[0]); $i++) {
                $shopCode = substr($adInfo[$i],strrpos($adInfo[$i], '_'));
                $shops += 'https://www.trustedshops.es/evaluacion/info'.$adInfo[$i].'\n';
           }
           return shops;
       }    
    }
}

?>