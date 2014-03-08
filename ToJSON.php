<?php

header("Content-Type: text/html;charset=utf-8";)

include_once 'constants.php';

public function ReviewsToJSON($shop, $review, $filepath){
    $shopObject = array(
    	if (isset($shop[SHOP_NAME])) SHOP_NAME => trim($shop[SHOP_NAME]);
    	if (isset($shop[ADDRESS])) ADDRESS => trim($shop[ADDRESS]);
		if (isset($shop[SHOP_URL])) SHOP_URL => trim($shop[SHOP_URL]);
		if (isset($shop[SHOP_URL_COMPETITOR])) SHOP_URL_COMPETITOR => trim($shop[SHOP_URL_COMPETITOR]);
		if (isset($shop[SHOP_ID])) SHOP_ID => trim($shop[SHOP_ID]);
		if (isset($shop[SHOP_PHONE])) SHOP_PHONE => trim($shop[SHOP_PHONE]);
		if (isset($shop[SHOP_EMAIL])) SHOP_EMAIL => trim($shop[SHOP_EMAIL]);
		if (isset($shop[SHOP_LOGO])) SHOP_LOGO => trim($shop[SHOP_LOGO]);
		'reviews' => $reviewObject = array(
			foreach ($review as $key => $value) {
				if (isset($key[DESCRIPTION], => trim($key[DESCRIPTION]);
				if (isset($key[RATING], => trim($key[RATING]);
				if (isset($key[DATE], => trim($key[DATE]);
				if (isset($key[LANGUAGE], => trim($key[LANGUAGE]);
				if (isset($key[USER_NAME], => trim($key[USER_NAME]);
				if (isset($key[USER_SURNAME], => trim($key[USER_SURNAME]);
				if (isset($key[USER_EMAIL], => trim($key[USER_EMAIL]);
			}
		);

    	$jsonContent = json_encode($shopObject)

    	$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, $jsonContent);
	}
}