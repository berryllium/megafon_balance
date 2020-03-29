<?php

require_once 'config.php';

$message = '';

foreach ($accounts as $user => $data) {

$post = [
  'j_username' => $data['login'],
  'j_password' => $data['pass']
];

$url = 'https://lk.megafon.ru/login/';
$agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0';
    $header = array(
        'Location: http://www.example.com/',
        'User-Agent: ' . $agent,
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-us,en;q=0.5',
        'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'Keep-Alive: 115',
        'Connection: keep-alive',
        'Referrer Policy: unsafe-url'
    );

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл
curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');

$result = curl_exec($ch);


preg_match_all('/<input type="hidden"\s*name="([^"]*)"\s*value="([^"]*)"/is', $result, $hiddenFields);

foreach ($hiddenFields[1] as $i => $name) {
  $post[$name] = $hiddenFields[2][$i];
}

curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, 'https://lk.megafon.ru/dologin/');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$result = curl_exec($ch);

preg_match('/<i class="lk_svg lk_svg_user_balans"><\/i>\s*<p>([^"]*)<\/p>/is', $result, $balance);

$balance = $balance[0];

$message .= "$user: $balance<br>";
sleep(1);
}

echo $message;
$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
mail($mailto, "Мегафон баланс", $message, $headers);