<?php

//includes
include_once 'htmlDownloader.php';
include_once 'constants.php';
include_once 'comunicadorBDD.php';

//RUN
$bot = new eKomiURL();
$bot -> nextGoogle('https://www.google.es/search?q=site%3Ahttps%3A%2F%2Fwww.ekomi.es%2Ftestimonios&oq=site%3Ahttps%3A%2F%2Fwww.ekomi.es%2Ftestimonios&aqs=chrome.0.69i59j69i58.9087j0j4&sourceid=chrome&espv=210&es_sm=122&ie=UTF-8#q=site:www.ekomi.es/testimonios&start=');

class eKomiURL {

  function nextGoogle($url){
    $finished = 0;
    $review = array();
    for ($page = 0; $finished = 0; $page = $page + 10) {
      $url += $page;
      $downloader = new htmlDownloader();
      $html = $downloader->get_html($url);

      if (preg_match_all('|<cite class="_jd">(https://www\.ekomi\.es/testimonios-[^<]+)</cite>|is', $html, $adInfo)){
        for ($i = 0; $i < count($adInfo); $i++) {
          $review[] = $adInfo[1][$i];
        }
      } else {
        $finished = 1;
      }
    }
    $json = json_encode(array("urls" => $review));
    $com = new comunicadorBDD();
    $com->insertNewCompetitors($json);
  }
}

?>