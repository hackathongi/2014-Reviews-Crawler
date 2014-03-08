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

class trustedShopsImporter
{
    public $encoding = 'UTF-8';
    public $reviews = array();
    public $shop = array();
    public $competitorMaxRate = 5;
    public $languange = '';

    public function run($url_competitor, $shop_id = '')
	{
        echo "\nBOT START\n\n";

        //DOWNLOAD MAIN HTML
        $downloader = new htmlDownloader();
        $mainUrl = $url_competitor;
        $mainHtml = $downloader->get_html($mainUrl);
        if (preg_match('|<meta name="language" content="([^"]*)"|', $mainHtml, $m)){
            $this->languange = $m[1];
        }

        $shopUrl = str_replace('evaluacion', 'perfil', $mainUrl);
        $shopHtml2 = $downloader->get_html($shopUrl);
        $shopHtml = $this->encodeHTML($mainHtml);
        //SHOP
        if (preg_match('|<h1><span itemprop="name">(.*)</span>|isU', $shopHtml, $m)){
            $this->shop[SHOP_NAME] = $m[1];
        }
        if (preg_match('|<a href="[^"]*/certificate\.php[^>]*>[^<]*</a>\s*</td>\s*</tr>\s*<tr>\s*<td><strong>[^<]*</strong></td>\s*<td>(.*)</td>|', $shopHtml, $m)){
            $this->shop[SHOP_ADDRESS] = $m[1];
        }
        if (preg_match('|<a target="_blank" class="url" href="([^"]*)"|', $shopHtml, $m)){
            $this->shop[SHOP_URL] = $m[1];
        }
        $this->shop[SHOP_URL_COMPETITOR] = $url_competitor;
        if ($shop_id) $this->shop[SHOP_ID] = $shop_id;

        if (preg_match('|<div class="contact">\s*<p>[^<]*</p>\s*<ul>\s*<li>\s*<a[^>]*>([^<]*)</a>\s*</li>\s*<li>([^<]*)</li>|isU', $shopHtml2, $m)){
            $this->shop[SHOP_EMAIL] = $m[1];
            $this->shop[SHOP_PHONE] = $m[2];
        }
        elseif (preg_match('|<div class="contact">\s*<p>[^<]*</p>\s*<ul>\s*<li>\s*<a[^>]*>([^<]*)</a>|isU', $shopHtml2, $m)){
            $this->shop[SHOP_EMAIL] = $m[1];
        }

        if (preg_match('|<img class="shoplogo" [^>]*src="([^"]+)"|isU', $shopHtml, $m)){
            $this->shop[SHOP_LOGO] = $m[1];
        }

        $shopID = preg_replace('|.*info_|i', '', $url_competitor);
        $shopID = str_replace('.html', '', $shopID);

        $mainUrl = 'https://www.trustedshops.es/bewertung/new_profile_template/comments_page.php?method=all&page=1&externalShopId=' . $shopID;
        $mainHtml = $downloader->get_html($mainUrl);

        //CRAWL
        $currentPage = 1;
        $adsFound = true;
        while ($adsFound){
            echo "Pagina $currentPage: ".count($this->reviews)." en total\n";
            if (preg_match_all('|<div class="comment">(.*)</div>|isU', $mainHtml, $mReviews)){
                for ($i = 0; $i < count($mReviews[1]); $i++){
                    $reviewHtml = $mReviews[1][$i];
                    $this->mapReview($reviewHtml);
                }
                $adsFound = true;
            }
            else $adsFound = false;

            //PAGINATION
            $currentPage++;
            $nextUrl = str_replace('page=1', 'page=' . $currentPage, $mainUrl);
            $mainHtml = $downloader->get_html($nextUrl);
        }

        $this->cleanElem($this->shop);

        $results = array (
            "shop" => $this->shop,
            "reviews" => $this->reviews
        );

        $resultsJson = json_encode($results);
        $this->insertShopAndOpinions($resultsJson);

    }

    function mapReview($reviewHtml) {
        $review = array();
        if (preg_match('|<p class="the_statement">(.*)</p>|isU', $reviewHtml, $m)){
            $review[DESCRIPTION] = $this->myStripTags($m[1]);
        }
        if (preg_match('|mini_(\d+)\.gif"|isU', $reviewHtml, $m)){
            $rating = $this->convertRating($m[1]);
            if ($rating){
                $review[RATING] = $rating;
            }
        }
        if (preg_match('|<p class="meta">[^<]*\D(\d+\.\d+\.\d{4})|is', $reviewHtml, $m)){
            $date = $m[1];
            $review[DATE] = date('Y-m-d H:i', strtotime($date));
        }
        if ($this->languange) $review[LANGUAGE] = $this->languange;
        //$review[USER_NAME] = '';
        //$review[USER_SURNAME] = '';
        //$review[USER_EMAIL] = '';

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

    function insertShopAndOpinions($json){
        $downloader = new htmlDownloader();
        $url = 'http://api.eshopinion.cat/POST/Crawler';
        $sortida = $downloader->get_html($url, $json);
        print_r($sortida);
    }
}

?>
