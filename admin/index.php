<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include '../config/dbconfig.php';
    include '../config/discord_config.php';

    $uniqueNumber = $_POST['unique_number'];
    $passStatus = $_POST['pass_status'];

    if ($passStatus !== 'passed' && $passStatus !== 'failed' && $passStatus !== 'notpassed') {
        die("유효하지 않은 합격 여부 값입니다.");
    }

    // exam_results 테이블에서 고유번호가 이미 존재하는지 확인
    $checkExamResultsSql = "SELECT * FROM exam_results WHERE unique_number = '$uniqueNumber'";
    $resultExamResults = $conn->query($checkExamResultsSql);

    // notpassed_candidates 테이블에서 고유번호가 이미 존재하는지 확인
    $checkNotPassedSql = "SELECT * FROM notpassed_candidates WHERE unique_number = '$uniqueNumber'";
    $resultNotPassed = $conn->query($checkNotPassedSql);

    if ($resultExamResults->num_rows > 0) {
        echo "<aside id='popup'><p>해당 고유번호($uniqueNumber)는 이미 데이터베이스에 등록되어 있습니다. 관리자에게 문의해주세요.</p></aside>";
        // exam_results 테이블에서 해당 합격 여부 삭제
        $deleteExamResultsSql = "DELETE FROM exam_results WHERE unique_number = '$uniqueNumber'";
        $conn->query($deleteExamResultsSql);
    } elseif ($resultNotPassed->num_rows > 0) {
        echo "<aside id='popup'><p>해당 고유번호($uniqueNumber)는 지원불가자로 등록되어 있습니다. 관리자에게 문의해주세요.</p></aside>";
    } else {
        // notpassed_candidates 테이블에 지원불가자 추가
        if ($passStatus === 'notpassed') {
            $insertNotPassedSql = "INSERT INTO notpassed_candidates (unique_number) VALUES ('$uniqueNumber')";

            if ($conn->query($insertNotPassedSql) === TRUE) {
                // 디스코드 웹훅 메시지 준비
                $discordWebhook = $discordWebhookResults;
                $statusText = '지원불가자';

                $data = [
                    'content' => "```인사팀에게 알려드립니다!```",
                    'embeds' => [
                        [
                            'title' => "고유번호: $uniqueNumber",
                            'description' => "합격여부: $statusText",
                            'color' => 16776960 // Yellow for 'notpassed'
                        ]
                    ]
                ];

                // 디스코드 웹훅으로 POST 요청 보내기
                $ch = curl_init($discordWebhook);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);

                echo "<aside id='popup'><p>고유번호: $uniqueNumber, 지원불가자로 등록되었습니다.</p></aside>";
            } else {
                echo "<aside id='popup'><p>지원불가자 테이블에 데이터 추가에 실패했습니다. 다시 시도해주세요.</p></aside>";
            }
        } else {
            // exam_results 테이블에 데이터 추가 (합격 또는 불합격)
            $insertExamResultsSql = "INSERT INTO exam_results (unique_number, pass_status) VALUES ('$uniqueNumber', '$passStatus')";

            if ($conn->query($insertExamResultsSql) === TRUE) {
                // 디스코드 웹훅 메시지 준비
                $discordWebhook = $discordWebhookResults;
                $statusText = ($passStatus === 'passed') ? '합격' : '불합격';

                $data = [
                    'content' => "```인사팀에게 알려드립니다!```",
                    'embeds' => [
                        [
                            'title' => "고유번호: $uniqueNumber",
                            'description' => "합격여부: $statusText",
                            'color' => ($passStatus === 'passed') ? 32768 : 16711680 // Green for 'passed', Red for 'failed'
                        ]
                    ]
                ];

                // 디스코드 웹훅으로 POST 요청 보내기
                $ch = curl_init($discordWebhook);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);

                echo "<aside id='popup'><p>고유번호: $uniqueNumber, 합격 여부가 등록되었습니다.</p></aside>";
            } else {
                echo "<aside id='popup'><p>데이터 추가에 실패했습니다. 다시 시도해주세요.</p></aside>";
            }
        }
    }

    $conn->close(); // 데이터베이스 연결 닫기
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/registration.css">
    <title>SamSam  합격자 등록</title>
</head>

<body>
    <div id="wrap">
        <h1><a href="#"><img src="../assets/logo/logo.png" alt="SamSam 서버 로고"></a></h1>
        <h2>SamSam  등록</h2>
        <p>고유번호와 합격여부를 입력 후 등록하세요!</p>
        <form action="index.php" method="post">
            <input type="number" id="unique_number" name="unique_number" placeholder="고유번호" required><br>
            <select id="pass_status" name="pass_status" required>
                <option value="passed">합격</option>
                <option value="failed">불합격</option>
                <option value="notpassed">지원불가자</option>
            </select><br>
            <input type="submit" value="결과 등록">
        </form>
        <form action="" method="post">
            <input type="submit" name="logout" value="로그아웃">
        </form>
    </div>
</body>

</html>
