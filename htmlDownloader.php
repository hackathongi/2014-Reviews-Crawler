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
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:27.0) Gecko/20100101 Firefox/27.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:9050');  //PARA UN FUTURO USAR TOR
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);   //PARA UN FUTURO USAR TOR
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
