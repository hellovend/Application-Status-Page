<?php
include 'discord_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Discord webhook URL
    $discordWebhook = $discordWebhookMain;

    // Get the user's IP address
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Mask the first part of the IP address
    $maskedIP = maskIPAddress($userIP);

    // URL for the image you want to include
    $imageUrl = "https://cdn.discordapp.com/attachments/845544237821853756/1416733325321699348/chzzk-on.png?ex=68c7eb3a&is=68c699ba&hm=3f2a4b981850d004943bb032ead72b7ad58291b5488610fde7fd81c243073249&"; // Replace with your image URL

    // Prepare data to send to Discord webhook with an image
    $discordData = [
        "content" => "```현재 인사팀원이 합격 여부 사이트에 접속했습니다!```",
        "embeds" => [
            [
                "title" => "인사팀원중 접속안내 \n         사용자 정보",
                "color" => hexdec("#FF0000"), // Red color
                "fields" => [
                    [
                        "name" => "IP 주소",
                        "value" => $maskedIP,
                    ],
                    [
                        "name" => "접속 날짜",
                        "value" => date("Y-m-d H:i:s"),
                    ],
                ],
                "image" => [
                    "url" => $imageUrl,
                ],
            ],
        ],
    ];

    $discordHeaders = [
        'Content-Type: application/json',
    ];

    // Send the data to Discord webhook
    $ch = curl_init($discordWebhook);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($discordData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $discordHeaders);
    curl_exec($ch);
    curl_close($ch);
}

// Function to mask the first part of an IP address
function maskIPAddress($ip) {
    $parts = explode(".", $ip); // IP를 . 기준으로 분리
    
    if (count($parts) === 4) { 
        $parts[1] = "*"; // 두 번째 옥텟 마스킹
        $parts[3] = "*"; // 네 번째 옥텟 마스킹
    }
    
    return implode(".", $parts);
}

