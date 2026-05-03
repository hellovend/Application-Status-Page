<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rlagusdn143";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 체크
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

// 편의를 위해 mysqli 객체를 $mysqli로도 사용할 수 있습니다.
$mysqli = $conn;
?>

