<?php
require_once __DIR__ . '/../config/dbconfig.php';

// POST 데이터 받기
$unique_number = $_POST['unique_number'];
$pass_status = $_POST['pass_status'];

// 디버그용: 받은 데이터 출력
echo "Received Data - Unique Number: $unique_number, Pass Status: $pass_status\n";

// 데이터베이스에 정보 추가
$query = "INSERT INTO `exam_results` (`id`, `unique_number`, `pass_status`) VALUES (NULL, '$unique_number', '$pass_status')";

// 디버그용: 수행할 쿼리 출력
echo "Query: $query\n";

$result = $mysqli->query($query);

// 디버그용: 쿼리 실행 결과 출력
echo "Query Result: " . ($result ? "Success" : "Error") . "\n";

if ($result) {
    echo json_encode(["message" => "Success"]);
} else {
    echo json_encode(["message" => "Error", "sql_error" => $mysqli->error]);
}

// MySQL 연결 종료
$mysqli->close();
?>
