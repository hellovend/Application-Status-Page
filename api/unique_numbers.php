<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/css/styles.css">
    <title>데이터베이스 고유번호 보기</title>
</head>
<body>
    <?php
    require_once __DIR__ . '/../config/dbconfig.php';

    if ($conn->connect_error) {
        die("MySQL 연결 실패: " . $conn->connect_error);
    }

$sql = "SELECT unique_number FROM exam_results";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>고유번호</th><th>고유번호</th></tr>";
    $counter = 0;
    while($row = $result->fetch_assoc()) {
        if ($counter % 2 == 0) {
            echo "<tr><td>" . $row["unique_number"] . "</td>";
        } else {
            echo "<td>" . $row["unique_number"] . "</td></tr>";
        }
        $counter++;
    }
    if ($counter % 2 != 0) {
        echo "<td></td></tr>";
    }
    echo "</table>";
} else {
    echo "결과가 없습니다.";
}
$conn->close();
?>
</body>
</html>
