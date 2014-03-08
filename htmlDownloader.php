<?php
/**
 * Created by PhpStorm.
 * User: alex@volcanicinternet.cat
 * Date: 3/8/14
 * Time: 9:53 AM
 */

class htmlDownloader {

    public function get_html($url, $post = '', $curlParameters = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'eShoppinion Bot');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:9050');
        curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($curlParameters) {
            foreach ($curlParameters as $key => $value) {
                curl_setopt($ch, $key, $value);
            }
        }

        $html = curl_exec($ch);
        
        if (curl_errno($ch)) {
            print curl_error($ch);
        }

        curl_close($ch);

        return $html;
    }

}

?>
