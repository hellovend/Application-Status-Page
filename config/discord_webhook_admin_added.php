<?php
include 'discord_config.php';

$webhookurl = $discordWebhookAdminAdded;

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([
    "embeds" => [
        [
            "title" => "새로운 어드민 추가",
            "description" => "ID: ".$admin_username."\nIP: ".$admin_ip,
            "timestamp" => $timestamp
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
