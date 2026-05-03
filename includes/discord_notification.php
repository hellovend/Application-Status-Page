<?php
// Get the data from the POST request
$uniqueNumber = $_POST['unique_number'];
$passStatus = $_POST['pass_status'];

if ($passStatus !== 'passed' && $passStatus !== 'failed') {
    die("유효하지 않은 합격 여부 값입니다.");
}

// Define Korean text for pass status
$passStatusText = ($passStatus === 'passed') ? '합격' : '불합격';

// Prepare the Discord webhook message
$discordWebhook = "YOUR_DISCORD_WEBHOOK_URL";
$data = [
    'content' => "임베드 추가O 고유번호 추가",
    'embeds' => [
        [
            'title' => "고유번호: $uniqueNumber",
            'description' => "합격여부: $passStatusText", // Use $passStatusText for Korean text
            'color' => ($passStatus === 'passed') ? 32768 : 16711680 // Green for 'passed', Red for 'failed'
        ]
    ]
];

// Send the POST request to the Discord webhook
$ch = curl_init($discordWebhook);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);
