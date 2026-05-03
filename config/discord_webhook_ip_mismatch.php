<?php
include 'discord_config.php';

$webhookurl = $discordWebhookIPMismatch;

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([
    "embeds" => [
        [
            "title" => "불일치 IP 접속 안내",
            "description" => "접속 IP: ".$_SERVER['REMOTE_ADDR'],
            "timestamp" => $timestamp,
            "color" => hexdec("FF0000")
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init($webhookurl);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

curl_close($ch);
?>
