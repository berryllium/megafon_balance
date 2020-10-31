<?php
function getCodeCaptcha()  {
  $key = API_KEY;
  $path = 'captcha.jpg';
  // находим на странице ссылку на капчу и сохраняем по ней картинку в файл
  // preg_match_all('/<img alt="Visual CAPTCHA image, continue down for an audio option."\s*src="([^"]*)"\s*/is', $html, $link);
  // $url = htmlspecialchars_decode($link[1][0]);
  // file_put_contents($path, file_get_contents($url));

  // отправляем картинку POST на сервис rucaptcha, получем id ждем установленное время
  $post =  [
      'file' => new CurlFile('captcha.jpg'),
      'method' => 'post',
      'key' => $key,
      'json' => 1,
  ];
  var_dump($post);
  $result = Remote::rConnect("https://rucaptcha.com/in.php", $post);
  var_dump($result);
  file_put_contents('ru_captcha_post.txt', $result);
  
  $data = json_decode($result);
  $status = $data->status;
  if ($status) {
      $id = $data->request;
  } else {
      echo 'Ошибка сервиса распознавания captcha' . $data->request . PHP_EOL;
      return false;
  }

  sleep(10);

  // делаем GET-запрос на сервис rucaptcha с id и получаем код, возвращаем его в вызвавшую функцию
  $result = file_get_contents("https://rucaptcha.com/res.php?key=$key&action=get&id=$id&json=1");
  $data = json_decode($result);
  $status = $data->status;
  if ($status) {
      $captcha = $data->request;
      file_put_contents('ru_captcha_code.txt', $result);
      return $captcha;
  } else {
      echo 'Ошибка сервиса распознавания captcha' . $data->request . PHP_EOL;
      return false;
  }
}