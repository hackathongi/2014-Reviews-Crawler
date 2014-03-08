<?php
/**
 * Created by PhpStorm.
 * User: alex@volcanicinternet.cat
 * Date: 3/8/14
 * Time: 9:53 AM
 */

// Includes
include_once 'constants.php';
include_once 'htmlDownloader.php';

class eKomiImporter
{
    public $encoding = 'Windows-1252';
    public $reviews = array();
    public $shop = array();
    public $competitorMaxRate = 10;
    public $clientLanguage = 'es';

    public function run($url_competitor, $shop_id = '')
	{
        echo "\nBOT START\n\n";

        //DOWNLOAD MAIN HTML
        $downloader = new htmlDownloader();
        $mainUrl = $url_competitor;
        $mainHtml = $downloader->get_html($mainUrl);

        $shopHtml = $this->encodeHTML($mainHtml);
        //SHOP
        if (preg_match('|<h2 [^>]*class="sectionTitle"[^>]*>\s*Valoraci[^<]*<span[^>]*>([^<]*)</span>|', $shopHtml, $m)){
            $this->shop['SHOP_NAME'] = $m[1];
        }
        if (preg_match('|<p class="shopAddressDetails">([^<]*)<|', $shopHtml, $m)){
            $this->shop['SHOP_ADDRESS'] = $m[1];
        }
        if (preg_match('|<div[^>]*>Website:</div>.*<a class="shoplink url" href=[\'"]([^\'"]*)[\'"]|isU', $shopHtml, $m)){
            $this->shop['SHOP_URL'] = $m[1];
        }
        $this->shop['SHOP_URL_COMPETITOR'] = $url_competitor;
        if ($shop_id) $this->shop['SHOP_ID'] = $shop_id;
        if (preg_match('|<strong>Tel:</strong>([^<]*)<|isU', $shopHtml, $m)){
            $this->shop['SHOP_PHONE'] = $m[1];
        }

        //$this->shop['SHOP_EMAIL'] = '';
        if (preg_match('|<img [^>]*src="(/images/shoplogos/[^"]*)"|isU', $shopHtml, $m)){
            $this->shop['SHOP_LOGO'] = 'https://www.ekomi.es/' . $m[1];
        }

        //CRAWL
        $currentPage = 1;
        $numberPages = 1;
        if (preg_match_all('|<a [^>]*class=.pagelink[^>]*s=(\d+)\D|is', $mainHtml, $m)){
            $numberPages = max($m[1]);
        }
        while ($currentPage <= $numberPages){
            echo "Página $currentPage: ".count($this->reviews)." en total\n";
            if (preg_match_all('|<li class="commentItemWrapper">(.*)</li>|isU', $mainHtml, $mReviews)){
                for ($i = 0; $i < count($mReviews[1]); $i++){
                    $reviewHtml = $mReviews[1][$i];
                    $this->mapReview($reviewHtml);
                }
            }

            //PAGINATION
            $currentPage++;
            $nextUrl = $url_competitor . '?s=' . $currentPage;
            $mainHtml = $downloader->get_html($nextUrl);
        }

        $results = array (
            "shop" => $this->cleanElem($this->shop),
            "reviews" => $this->reviews
        );
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($test_array, array ($xml, 'addChild'));
        print $xml->asXML();

        $resultsJson = json_encode($results);
        print_r($resultsJson);
    }

    function mapReview($reviewHtml) {
        $reviewHtml = $this->encodeHTML($reviewHtml);
        $review = array();
        if (preg_match('|<div [^>]*class="whiteBoxT2InnerRight"[^>]*>(.*)</div>|isU', $reviewHtml, $m)){
            $review['DESCRIPTION'] = $this->myStripTags($m[1]);
        }
        if (preg_match('|<div [^>]*class="smallGradeWrapper right"[^>]*>\s*<span>([^<]*)</span>|isU', $reviewHtml, $m)){
            $rating = $this->convertRating($m[1]);
            if ($rating){
                $review['RATING'] = $rating;
            }
        }
        if (preg_match('|<div [^>]*class="commentDetails"[^>]*>[^<]*\D(\d+\.\d+\.\d{4})[^<]*\D(\d{1,2}:\d{2})[^<]*</div>|is', $reviewHtml, $m)){
            $date = $m[1] . ' ' . $m[2];
            $review['DATE'] = date('Y-m-d H:i:s', strtotime($date));
        }
        $review['LANGUAGE'] = $this->clientLanguage;
        //$review['USER_NAME'] = '';
        //$review['USER_SURNAME'] = '';
        //$review['USER_EMAIL'] = '';

        $this->cleanElem($review);
        $this->reviews[] = $review;

    }

    function cleanElem(&$elem){
        foreach ($elem as $key => $value){
            $elem[$key] = trim($value);
        }
    }
    function myStripTags($html){
        $html = preg_replace('|</?\w[^>]*>|', ' ', $html);
        $html = preg_replace('|\s+|', ' ', $html);
        return trim($html);
    }

    function convertRating($rating){
        $rating = trim($rating);
        return $rating*MAX_RATING/$this->competitorMaxRate;
    }

    function encodeHTML($html){
        return iconv($this->encoding, "UTF-8", $html);
    }
}

?>
