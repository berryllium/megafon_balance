<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 29.03.2020
 * Time: 19:18
 */

class Remote
{

    /**
     * Отправка http запросов через curl
     * @param string $url
     * @param array $post
     * @param string $file
     * @param bool $getHeader
     * @param string $referer
     * @return bool|string
     */
    static public function rConnect($url = '', $post = array(), $file = '', $getHeader = false, $referer = '')
    {
        $fp = false;
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36';
        $header = array(
            'User-Agent: '.$agent,
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.5',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
            'Connection: keep-alive',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true );
        curl_setopt($ch,CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch,CURLOPT_COOKIEJAR,  'cookies.txt');
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($getHeader)
        {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        else
        {
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($referer)
        {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        if ($file)
        {
            $fp = fopen($file, 'w');
            curl_setopt($ch, CURLOPT_FILE, $fp);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if ($fp)
        {
            fclose($fp);
        }
        return $result;
    }
}