<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/8/14
 * Time: 5:47 PM
 */

include_once 'constants.php';

    function encodeHTML($html, $encoding){
        return iconv($encoding, "UTF-8", $html);
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

    function convertRating($rating, $competitorMaxRate){
        $rating = trim($rating);
        return $rating*MAX_RATING/$competitorMaxRate;
    }
?>