<?php

header("Content-Type: text/html;charset=utf-8");

include_once 'constants.php';
include_once 'toJSON.php';

$shop[SHOP_NAME] = 'shop_name';
$shop[SHOP_ADDRESS] = 'shop_address';
$shop[SHOP_URL] = 'shop_url';
$shop[SHOP_URL_COMPETITOR] = 'shop_url_competitor';
$shop[SHOP_ID] = 'shop_id';
$shop[SHOP_PHONE] = 'shop_phone';
$shop[SHOP_EMAIL] = 'shop_email';
$shop[SHOP_LOGO] = 'shop_logo';

$reviews => array($one => array($u[DESCRIPTION] = 'description',
$u[RATING] = 'rating';
$u[DATE] = 'date';
$ad[LANGUAGE] = 'language';
$ad[USER_NAME] = 'name';
$ad[USER_SURNAME] = 'surname';
$ad[USER_EMAIL] = 'user_email'),
$two => array($u[DESCRIPTION] = 'description2',
$u[RATING] = 'rating2';
$u[DATE] = 'date2';
$ad[LANGUAGE] = 'language2';
$ad[USER_NAME] = 'name2';
$ad[USER_SURNAME] = 'surname2';
$ad[USER_EMAIL] = 'user_email2'));

$json = new ReviewsToJson($shop,$reviews);