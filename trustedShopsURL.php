<?php

//includes
include_once 'htmlDownloader.php';
include_once 'constants.php';

//RUN
parseShop('http://www.trustedshops.es/b2c_int/getdata.php?module=search_shop2&format=json&q=.');

class TrustedShopsURL {
  function parseShop($url){
    $downloader = new htmlDownloader();
    $html = $downloader->get_html($url);
     
    if (preg_match('|\[(.*)$|is', $html, $adInfo)){
      if(preg_match_all('|"(\d+)"|is'$adInfo[1], $id) {
        $shops = '';
        for ($i = 0; $i < count($id); $i++) {
          $link = 'http://www.trustedshops.es/b2c_int/getdata.php?module=shopData&format=json';
          for ($j = 0; $j < 85; $j++) {
            $link += '&shops%5B%5D='.$id[$i];
          }
          $shops = parseShopURL($link);
        }
      }
    }
  }

  function parseShopURL($url) {
    $downloader = new htmlDownloader();
    $html = $downloader->get_html($url);
      
    if (preg_match_all('|"url":"([^"]+)"|is', $html, $adInfo)){
      for ($i = 0; $i < count($adInfo); $i++) {
        $shopCode = substr($adInfo[$i],strrpos($adInfo[$i], '_'));
        $shop = 'https://www.trustedshops.es/evaluacion/info'.$adInfo[$i]; 
        //insert api
      }
    }    
  }
}

?>