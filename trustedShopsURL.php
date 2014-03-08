<?php

//includes
include_once 'htmlDownloader.php';
include_once 'constants.php';
include_once 'comunicadorBDD.php';

//RUN
$bot = new TrustedShopsURL();
$bot->parseShop('http://www.trustedshops.es/b2c_int/getdata.php?module=search_shop2&format=json&q=.');

//CLASS

class TrustedShopsURL {
  function parseShop($url){
    $downloader = new htmlDownloader();
    $html = $downloader->get_html($url);
     
    if (preg_match('|\[(.*)$|is', $html, $adInfo)){
      if(preg_match_all('|"(\d+)"|is', $adInfo[1], $id)) {
          $index = 0;
          $shops = array();
          while ($index < count($id[1])){
              $link = 'http://www.trustedshops.es/b2c_int/getdata.php?module=shopData&format=json';
              $end = $index + 85;
              for ($i = $index; $i < $end; $i++) {
                  $index++;
                  if (isset ($id[1][$index])) $link .= '&shops[]='.$id[1][$index];
              }
              $this->parseShopURL($link, $shops);
              $json = json_encode(array("urls" => $shops));
              $com = new comunicadorBDD();
              $com->insertNewCompetitors($json);

          }
      }
    }
  }

  function parseShopURL($url, &$shops) {
    $downloader = new htmlDownloader();

    $html = $downloader->get_html($url);
      
    if (preg_match_all('|"tsId":"([^"]+)"|is', $html, $adInfo)){
      for ($i = 0; $i < count($adInfo[1]); $i++) {
        $shopUrl = 'https://www.trustedshops.es/evaluacion/info_'.$adInfo[1][$i].'.html';
        $shops[] = $shopUrl;
      }
    }    
  }
}

?>