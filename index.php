<?php

include "secret.php";
include "api_base.php";
include "min_tg_api.php";

$curl = curl_init($rt_url);
curl_setopt($curl, CURLOPT_URL, $rt_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Authorization: Bearer ".$RT_KEY,
);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$resp = curl_exec($curl);
curl_close($curl);

$data = json_decode($resp);


$tg_bot  = new TelegramBot($TG_KEY);
$send_to = ["223074836"];

if ($data -> state -> onEmpty) {
	echo "Кормушка пустая";
	foreach ($send_to as $chatId) {
		$tg_bot -> sendMessage($chatId, "Кормушка пустая!")
	}
} else {
	echo "Все ОК";
}

?>