<?php
$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";

mail("gorkundp@yandex.ru", "Мегафон баланс", "На счету у Тамары Ивановны: $balance", $headers);