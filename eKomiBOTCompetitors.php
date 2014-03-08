<?php

//includes
include_once 'htmlDownloader.php';
include_once 'constants.php';
include_once 'comunicadorBDD.php';

//RUN
$bot = new eKomiURL();
$bot->nextGoogle('https://www.google.com/search?q=site:www.ekomi.es/testimonios&hl=en&start=', '', array(
    CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:27.0) Gecko/20100101 Firefox/27.0',
));

class eKomiURL {

  function nextGoogle($url){
     $com = new comunicadorBDD();
    $finished = 0;
    $review = array();
    for ($page = 0; $finished = 1; $page++) {
      $url = 'https://www.google.com/search?q=site:www.ekomi.es/testimonios&hl=en&start=' . $page*10;
      sleep(10);
      $downloader = new htmlDownloader();
      $html = $downloader->get_html($url);

      if (preg_match_all('@<h3[^>]*>\s*<a [^>]*(https://www\.ekomi\.es/testimonios-[^"\']*\.html)@isU', $html, $adInfo)){
        for ($i = 0; $i < count($adInfo[1]); $i++) {
          $review[] = $adInfo[1][$i];
        }
          print_r(array("urls" => $review));
          $json = json_encode(array("urls" => $review));
          $com->insertNewCompetitors($json);
          $review = array();
      } else {
        $finished = 1;
      }
    }
  }
}

?>