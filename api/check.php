<?php
require_once __DIR__ . '/../config/dbconfig.php';

$uniqueNumber = "";
$passStatus = "미지원 & 등록의 오류";
$passClass = '';
$passText = '';

$service_status = 0;

if ($service_status === 1) {
    header("Location: pages/maintenance.html");
    exit();
} elseif ($service_status === 2) {
    header("Location: pages/service_ended.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $uniqueNumber = $_POST['unique_number'];

    // =========================
    // 🔥 Prepared Statement (보안 핵심)
    // =========================
    $stmt = $conn->prepare("SELECT pass_status FROM exam_results WHERE unique_number = ?");
    $stmt->bind_param("s", $uniqueNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $row = $result->fetch_assoc();
        $passStatus = strtolower(trim($row['pass_status']));

        // =========================
        // 상태 처리
        // =========================
        if ($passStatus === 'passed') {
            $passClass = 'passed';
            $passText = '합격';

        } elseif ($passStatus === 'failed') {
            $passClass = 'failed';
            $passText = '불합격';

        } elseif ($passStatus === 'pending') {
            $passClass = 'pending';
            $passText = '지원서 검토 중';

        } else {
            $passClass = 'error';
            $passText = '미지원 & 등록의 오류';
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script>
        (function(){
            var t = localStorage.getItem("dm-theme") ||
                    (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
            document.documentElement.setAttribute("data-theme", t);
        })();
    </script> -->
    <link rel="stylesheet" href="../assets/css/check.css">
    <link rel="stylesheet" href="../assets/css/darkmode.css">
    <title>SamSam 합격자 조회</title>

    <style>
        #wrap2 {
            margin-top: 20px;
        }

        #wrap2 .inner {
            position: relative;
            padding: 20px;
            border: 1px solid var(--border-color-soft);
            background: var(--bg-inner);
            max-width: 600px;
        }

        #close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: var(--text-primary);
        }

        #close-btn:hover {
            color: var(--danger);
        }

        .passed { color: #22c55e; font-weight: bold; }
        .failed { color: var(--danger); font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        .error { color: gray; font-weight: bold; }
    </style>
</head>

<body>

<div id="wrap1">
    <h1>
        <a href="#">
            <img src="../assets/logo/logo.png" alt="logo">
        </a>
    </h1>

    <h2>합격자 조회</h2>

    <form method="post">
        <input type="text" name="unique_number" placeholder="고유번호" required
            value="<?php echo htmlspecialchars($uniqueNumber); ?>">
        <input type="submit" value="조회">
    </form>
</div>

<?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
<div id="wrap2">
    <h1>SamSam 결과</h1>
    <button id="close-btn">&times;</button>

    <div class="inner">
        <table>
            <tr>
                <th>고유번호</th>
                <td><?php echo htmlspecialchars($uniqueNumber); ?></td>
            </tr>

            <tr>
                <th>결과</th>
                <td class="<?php echo $passClass; ?>">
                    <?php echo $passText; ?>
                </td>
            </tr>
        </table>

        <p>
            <?php
            if ($passStatus === 'passed') {
                echo '<span>축하합니다!</span> 면접 일정 DM 전달 바랍니다.';

            } elseif ($passStatus === 'failed') {
                echo '<span>불합격입니다</span> 3일 후 재지원 가능합니다.';

            } elseif ($passStatus === 'pending') {
                echo '<span>검토중</span> 현재 담당 인사팀이 검토 중입니다.';

            } else {
                echo '합격 여부를 확인할 수 없습니다.';
            }
            ?>
        </p>
    </div>
</div>
<?php endif; ?>

<script src="../assets/script/darkmode.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('close-btn');
    const box = document.getElementById('wrap2');

    if (btn && box) {
        btn.addEventListener('click', () => {
            box.style.display = 'none';
        });
    }
});
</script>


</body>
</html>