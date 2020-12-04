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

$cfg = json_decode(file_get_contents("config"));

$send_to = $cfg -> users;
$saved_state = $cfg -> saved_state;

$tg_data = json_decode(file_get_contents('php://input'), true);
$chat_id = $tg_data["message"]["chat"]["id"];
$text = $tg_data["message"]["text"];

if ($chat_id && $text=="/start") {
	if (!in_array("$chat_id", $send_to)) {
		$send_to[] = "$chat_id";
	}
}

if ($data -> state -> onEmpty) {
	if ($saved_state == 0) {
		foreach ($send_to as $cid) {
			$tg_bot -> sendMessage($cid, "Кормушка пустая!");
		}

		$saved_state = 1;
	}
	
} else {
	if ($saved_state == 1) {
		foreach ($send_to as $cid) {
			$tg_bot -> sendMessage($cid, "Кормушка загружена!");
		}

		$saved_state = 0;
	}
	echo "Все ОК";
}

$cfg -> users = $send_to;
$cfg -> saved_state = $saved_state;

file_put_contents("config", json_encode($cfg));
?>