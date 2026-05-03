<?php
require_once __DIR__ . '/../config/dbconfig.php';

if ($conn->connect_error) { die("연결 실패: " . $conn->connect_error); }

// 합격자
$sql_pass = "SELECT unique_number FROM exam_results WHERE pass_status='passed'";
$res_pass = $conn->query($sql_pass);
$pass = [];
if($res_pass->num_rows>0){
    while($row=$res_pass->fetch_assoc()) $pass[] = $row['unique_number'];
}

// 불합격자
$sql_failed = "SELECT unique_number FROM exam_results WHERE pass_status='failed'";
$res_failed = $conn->query($sql_failed);
$failed = [];
if($res_failed->num_rows>0){
    while($row=$res_failed->fetch_assoc()) $failed[] = $row['unique_number'];
}

// 지원불가자
$sql_denied = "SELECT unique_number FROM notpassed_candidates";
$res_denied = $conn->query($sql_denied);
$denied = [];
if($res_denied->num_rows>0){
    while($row=$res_denied->fetch_assoc()) $denied[] = $row['unique_number'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>실시간 고유번호 확인</title>
<style>
body { font-family:sans-serif; background:#f5f5f5; padding:20px;}
h2 { text-align:center; margin-bottom:30px; }
.table-container { display:flex; justify-content:space-around; flex-wrap:wrap; gap:20px; }

.card {
    background:#fff;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    border-radius:10px;
    padding:15px;
    flex:1 1 250px;
    min-width:200px;
}

.card h3 { text-align:center; margin-bottom:15px; color:#6145ff; }

table { border-collapse: collapse; width:100%; }
th, td { border:1px solid #ddd; padding:8px; text-align:center;}
th { background:#6145ff; color:#fff; }
td { background:#f9f9f9; }
</style>
</head>
<body>

<h2>실시간 고유번호 확인</h2>

<div class="table-container">
    <div class="card" id="pass-table">
        <h3>합격자</h3>
        <table>
            <tr><th>고유번호</th><th>고유번호</th></tr>
            <?php
            $counter = 0;
            foreach($pass as $num){
                if($counter % 2 == 0) echo "<tr><td>$num</td>";
                else echo "<td>$num</td></tr>";
                $counter++;
            }
            if($counter % 2 != 0) echo "<td></td></tr>";
            ?>
        </table>
    </div>

    <div class="card" id="failed-table">
        <h3>불합격자</h3>
        <table>
            <tr><th>고유번호</th><th>고유번호</th></tr>
            <?php
            $counter = 0;
            foreach($failed as $num){
                if($counter % 2 == 0) echo "<tr><td>$num</td>";
                else echo "<td>$num</td></tr>";
                $counter++;
            }
            if($counter % 2 != 0) echo "<td></td></tr>";
            ?>
        </table>
    </div>

    <div class="card" id="denied-table">
        <h3>지원불가자</h3>
        <table>
            <tr><th>고유번호</th><th>고유번호</th></tr>
            <?php
            $counter = 0;
            foreach($denied as $num){
                if($counter % 2 == 0) echo "<tr><td>$num</td>";
                else echo "<td>$num</td></tr>";
                $counter++;
            }
            if($counter % 2 != 0) echo "<td></td></tr>";
            ?>
        </table>
    </div>
</div>

<script>
// 2초마다 테이블 영역만 새로고침
setInterval(() => {
    fetch('index.php?refresh=1')
        .then(res => res.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');

            document.getElementById('pass-table').innerHTML = doc.getElementById('pass-table').innerHTML;
            document.getElementById('failed-table').innerHTML = doc.getElementById('failed-table').innerHTML;
            document.getElementById('denied-table').innerHTML = doc.getElementById('denied-table').innerHTML;
        });
}, 2000);
</script>

</body>
</html>
