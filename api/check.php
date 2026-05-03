<?php
require_once __DIR__ . '/../config/dbconfig.php';

$uniqueNumber = "";
$passStatus = "미지원 & 등록의 오류"; // 초기값
$passClass = '';
$passText = '';

// 서비스 상태: 0=정상, 1=점검, 2=종료
$service_status = 0; // 1=점검, 2=종료 테스트 가능

// 서비스 상태 체크
if ($service_status === 1) {
    header("Location: pages/maintenance.html");
    exit();
} elseif ($service_status === 2) {
    header("Location: pages/service_ended.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uniqueNumber = $_POST['unique_number'];

    $sql = "SELECT * FROM exam_results WHERE unique_number = '$uniqueNumber'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $passStatus = $row['pass_status'];

        // 닉네임 처리
        if (isset($row['nickname'])) {
            $nickname = $row['nickname'];
        } else {
            $nickname = "Nickname not found";
        }

        // 상태에 따른 클래스 및 텍스트 설정
        $status = strtolower(trim($passStatus));
        if ($status === 'passed') {
            $passClass = 'passed';
            $passText = '합격';
        } elseif ($status === 'failed') {
            $passClass = 'failed';
            $passText = '불합격';
        } else {
            $passClass = 'error';
            $passText = '미지원 & 등록의 오류';
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/css/check.css">
    <title>SamSam 합격자 등록</title>
    <style>
        /* 결과 박스 스타일 */
        #wrap2 {
            margin-top: 20px;
        }
        #wrap2 .inner {
            position: relative;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            max-width: 600px;
        }

        /* 닫기 버튼 */
        #close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: transparent;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        #close-btn:hover {
            color: red;
        }

        /* 합격 여부 색상 */
        .passed {
            color: green;
            font-weight: bold;
        }

        .failed {
            color: red;
            font-weight: bold;
        }

        .error {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="wrap1">
        <h1><a href="#"><img
                    src="https://cdn.discordapp.com/attachments/845544237821853756/1416733325321699348/chzzk-on.png?ex=68c7eb3a&is=68c699ba&hm=3f2a4b981850d004943bb032ead72b7ad58291b5488610fde7fd81c243073249&"
                    alt="SamSam 서버 로고"></a></h1>
        <h2>합격자 조회 결과</h2>
        <p>고유번호를 입력 후 조회를 눌러주세요!</p>
        <form action="check.php" method="post">
            <input type="text" id="unique_number" name="unique_number" placeholder="고유번호" required
                value="<?php echo htmlspecialchars($uniqueNumber); ?>"><br>
            <input type="submit" value="조회">
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <div id="wrap2">
        <h1>SamSam 합격자 발표</h1>
        <button id="close-btn">&times;</button>
        <div class="inner">
            <table>
                <tr>
                    <th>고유번호</th>
                    <td><?php echo htmlspecialchars($uniqueNumber); ?></td>
                </tr>
                <tr>
                    <th>합격 여부</th>
                    <td class="<?php echo $passClass; ?>"><?php echo $passText; ?></td>
                </tr>
            </table>
            <p>
                <?php
                if ($passStatus === 'passed') {
                    echo '<span>축하합니다!</span> 담당 인사팀에게 면접 가능한 시간을 지원서 작성 후 1시간 전까지 DM으로 알려주세요 (신분증, 학생증까지). 2차 면접은 인성 면접으로 진행하고 있습니다!';
                } elseif ($passStatus === 'failed') {
                    echo '<span>불합격입니다</span> 재지원은 작성 후 3일 뒤 가능하며, 귀하께서 과 함께 하실 수 있도록 재지원 해주신다면 다시 한번 면밀히 검토하겠습니다. 감사합니다.';
                } else {
                    echo '합격 여부를 확인할 수 없습니다. 관리자에게 문의해주세요.';
                }
                ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // 닫기 버튼 기능
        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.getElementById('close-btn');
            const wrap2 = document.getElementById('wrap2');

            if (closeBtn && wrap2) {
                closeBtn.addEventListener('click', () => {
                    wrap2.style.display = 'none';
                });
            }
        });
    </script>
</body>

</html>
