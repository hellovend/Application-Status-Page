<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>데이터베이스 고유번호 보기</title>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nanum Gothic', sans-serif;
            background: #f5f6f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .container { width: 100%; max-width: 420px; }

        .header { text-align: center; margin-bottom: 1.5rem; }
        .header h1 { font-size: 20px; font-weight: 800; color: #111; letter-spacing: -0.3px; }
        .header p  { font-size: 13px; color: #888; margin-top: 4px; }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead th {
            padding: 11px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        thead th:first-child { text-align: left; }
        thead th:last-child  { text-align: right; width: 48px; }
        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.1s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f9fafb; }
        .num {
            padding: 11px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #111;
        }
        .idx {
            padding: 11px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #aaa;
            text-align: right;
        }
        .empty { padding: 3rem; text-align: center; color: #aaa; font-size: 14px; }
        .footer { text-align: center; margin-top: 12px; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
<?php
require_once __DIR__ . '/../config/dbconfig.php';

if ($conn->connect_error) {
    die("<p style='color:red;text-align:center;padding:2rem'>연결 실패: " . $conn->connect_error . "</p>");
}

$result = $conn->query("SELECT unique_number FROM exam_results");
$total  = $result ? $result->num_rows : 0;
?>

<div class="container">
    <div class="header">
        <h1>고유번호 목록</h1>
        <p>현재 DB에 등록된 고유번호 목록</p>
    </div>

    <div class="card">
        <?php if ($total > 0): ?>
        <table>
            <thead>
                <tr><th>고유번호</th><th>#</th></tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="num"><?= htmlspecialchars($row['unique_number']) ?></td>
                    <td class="idx"><?= str_pad($i++, 3, '0', STR_PAD_LEFT) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty">결과가 없습니다.</div>
        <?php endif; ?>
    </div>

    <div class="footer">총 <?= $total ?>개 레코드</div>
</div>

<?php $conn->close(); ?>
</body>
</html>