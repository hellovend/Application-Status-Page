<?php

// 🔥 에러 표시 (디버깅용)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔥 DB 연결
include '../config/dbconfig.php'; // 너 DB 연결 파일
include '../config/discord_config.php'; // 디스코드 웹훅

// 🔥 요청 데이터 받기
$input = file_get_contents("php://input");

// 로그 파일 저장 (요청 들어오는지 확인용)
file_put_contents("log.txt", $input . "\n", FILE_APPEND);

// JSON 파싱
$data = json_decode($input, true);

if (!$data) {
    die("JSON 파싱 실패");
}

// 🔥 데이터 꺼내기
$uniqueNumber = $data['unique_number'] ?? null;
$nickname = $data['nickname'] ?? null;
$age = $data['age'] ?? null;
$timestamp = $data['timestamp'] ?? null;

// 값 체크
if (!$uniqueNumber || !$nickname) {
    die("값 부족");
}

// 🔥 중복 확인
$stmt = $conn->prepare("SELECT id FROM candidates WHERE unique_number = ?");
$stmt->bind_param("s", $uniqueNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    // 🔥 DB 저장 (pending)
    $insert = $conn->prepare("
        INSERT INTO candidates (unique_number, nickname, age, created_at, pass_status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $insert->bind_param("ssis", $uniqueNumber, $nickname, $age, $timestamp);

    if ($insert->execute()) {

        // 🔥 디스코드 알림
        $msg = [
            'content' => "```새 지원자 접수```",
            'embeds' => [
                [
                    'title' => "닉네임: $nickname",
                    'description' => "고유번호: $uniqueNumber\n나이: $age",
                    'color' => 5814783
                ]
            ]
        ];

        $ch = curl_init($discordWebhookResults);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        echo "OK";
    } else {
        echo "DB 저장 실패";
    }

} else {
    echo "이미 존재";
}

?>