<?php

// 🔥 에러 표시 (개발용)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔥 DB / Discord 설정
include '../config/dbconfig.php';
include '../config/discord_config.php';

// 🔥 요청 데이터 받기
$input = file_get_contents("php://input");
file_put_contents("log.txt", $input . "\n", FILE_APPEND);

// JSON 파싱
$data = json_decode($input, true);

if (!$data) {
    die("JSON 파싱 실패");
}

// 🔥 데이터 추출
$uniqueNumber = $data['unique_number'] ?? null;
$nickname = $data['nickname'] ?? null;
$age = $data['age'] ?? null;

// 🔥 서버 기준 시간 (조작 방지)
$registeredAt = date("Y-m-d H:i:s");

// 값 검증
if (!$uniqueNumber || !$nickname) {
    die("값 부족");
}

// =========================
// 🔥 중복 확인 (exam_results)
// =========================
$stmt = $conn->prepare("SELECT id FROM exam_results WHERE unique_number = ?");
$stmt->bind_param("s", $uniqueNumber);
$stmt->execute();
$result = $stmt->get_result();

// =========================
// 🔥 INSERT (없을 때만)
// =========================
if ($result->num_rows === 0) {

    $passStatus = "pending"; // ENUM 값

    $insert = $conn->prepare("
        INSERT INTO exam_results 
        (unique_number, pass_status, registered_at)
        VALUES (?, ?, ?)
    ");

    $insert->bind_param("sss", $uniqueNumber, $passStatus, $registeredAt);

    if ($insert->execute()) {

        // =========================
        // 🔥 Discord 알림
        // =========================
        $msg = [
            'content' => "```새 지원자 접수```",
            'embeds' => [[
                'title' => "닉네임: $nickname",
                'description' => "고유번호: $uniqueNumber\n상태: $passStatus",
                'color' => 5814783
            ]]
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

$conn->close();

?>