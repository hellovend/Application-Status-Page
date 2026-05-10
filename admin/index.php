<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/dbconfig.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $uniqueNumber = $_POST['unique_number'];
    $passStatus = $_POST['pass_status'];

    if (!in_array($passStatus, ['passed', 'failed', 'notpassed'])) {
        die("유효하지 않은 값입니다.");
    }

    // =========================
    // notpassed 처리
    // =========================
    if ($passStatus === 'notpassed') {

        $check = $conn->prepare("SELECT unique_number FROM notpassed_candidates WHERE unique_number = ?");
        $check->bind_param("s", $uniqueNumber);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            echo "<script>alert('이미 지원불가자입니다.');</script>";

        } else {

            $insert = $conn->prepare("INSERT INTO notpassed_candidates (unique_number) VALUES (?)");
            $insert->bind_param("s", $uniqueNumber);

            if ($insert->execute()) {

                // 디스코드
                $webhook = "YOUR_WEBHOOK_URL";

                $data = [
                    'content' => "```인사팀에게 알려드립니다!```",
                    'embeds' => [[
                        'title' => "고유번호: $uniqueNumber",
                        'description' => "합격여부: 지원불가자",
                        'color' => 16776960
                    ]]
                ];

                sendDiscord($webhook, $data);

                echo "<script>alert('지원불가자 등록 완료');</script>";
            }
        }
    }

    // =========================
    // exam_results
    // =========================

    $check = $conn->prepare("SELECT unique_number FROM exam_results WHERE unique_number = ?");
    $check->bind_param("s", $uniqueNumber);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        // UPDATE
        $update = $conn->prepare("UPDATE exam_results SET pass_status = ? WHERE unique_number = ?");
        $update->bind_param("ss", $passStatus, $uniqueNumber);
        $update->execute();

        echo "<script>alert('기존 데이터 업데이트 완료');</script>";

    } else {

        // INSERT
        $insert = $conn->prepare("INSERT INTO exam_results (unique_number, pass_status) VALUES (?, ?)");
        $insert->bind_param("ss", $uniqueNumber, $passStatus);
        $insert->execute();

        echo "<script>alert('새 데이터 등록 완료');</script>";
    }

    // =========================
    // 디스코드 알림
    // =========================

    $webhook = "YOUR_WEBHOOK_URL";

    $statusText = match($passStatus) {
        'passed' => '합격',
        'failed' => '불합격',
        default => '지원불가자'
    };

    $color = match($passStatus) {
        'passed' => 32768,
        'failed' => 16711680,
        default => 16776960
    };

    $data = [
        'content' => "```인사팀에게 알려드립니다!```",
        'embeds' => [[
            'title' => "고유번호: $uniqueNumber",
            'description' => "합격여부: $statusText",
            'color' => $color
        ]]
    ];

    sendDiscord($webhook, $data);

    $conn->close();
}

// =========================
// Discord 함수
// =========================

function sendDiscord($url, $data) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
    curl_close($ch);
}
?>
