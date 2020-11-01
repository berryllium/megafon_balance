<?php
// подключаем конфиг и функции
require_once('config.php');
require_once('functions.php');
// получаем и обрабатываем данные
$data = json_decode(file_get_contents('php://input'), true);
$button = @$data['callback_query']['data'];
$data = @$data['callback_query']['message'] ?: $data['message'];
// пишем лог последнего сообщения боту
file_put_contents('bot.txt', print_r($data, true));
// получаем значение кнопки, нажатой пользователем
// получаем текст сообщения
$text = strtolower($data['text']);
// получаем id чата
$chat_id = $data['chat']['id'];

// получаем информацию об отправителе
// $from_id = $data['from']['id'];
// $from_name = $data['from']['first_name'];

// формируем кнопки с колбеками
$keyboard = [
  "resize_keyboard" => true,
  "inline_keyboard" => [
    [
      [
        'text' => 'Анюта',
        'callback_data' => 'Анюта'
      ],
      [
        'text' => 'Дима',
        'callback_data' => 'Дима'
      ]
    ],
    [
      [
        'text' => 'Тамара Ивановна',
        'callback_data' => 'Тамара Ивановна'
      ]
    ]
  ]
];

// обрабатываем сообщение
$method = false;
if($text == '/start') {
  $method = 'sendMessage';
  $sendData = [
    'text' => 'Чей баланс Вас интересует? Я сбегаю узнаю, но это займет время, так что терпите.',
    'reply_markup' => $keyboard
  ];
} elseif($text == '/stop'){
  $method = 'sendMessage';
  $sendData = [
    'text' => "И что теперь?",
  ];
}
elseif($button) {
  sendMessage($chat_id, 'Ща-ща, уже узнаю!');
  $response = getBalance($button);
  if($response['status'] == 'success') {
    $balance = $response['message'];
    $balance = str_replace('&#8381', 'руб.', $balance);
    $message = "На счету абонента $button $balance";
  } else {
    $message = 'С сервисом баланса какая-то беда, попробуйте завтра, а лучше вообще не пробуйте теперь';
  }
  $message .= ' Чей баланс узнать?';
  $method = 'sendMessage';
  $sendData = [
    'text' => $message,
    'reply_markup' => $keyboard
  ];
} else {
  $method = 'sendMessage';
  $sendData = [
    'text' => "Пользуйся кнопками!",
    'reply_markup' => $keyboard
  ];

}
file_put_contents('bal.log', print_r($response, true));  
$sendData['chat_id'] = $chat_id;
if($method) sendTelegram($method, $sendData);

// если пользователя запустил бота, а чата с ним не в базе - добавляем в базу
if($text == '/start') saveUser($chat_id);
// если пользователь остановил бота - удаляем чат с ним из базы
if($text == '/stop') removeUser($chat_id);
