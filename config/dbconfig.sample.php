<?php
// 데이터베이스 설정 샘플 파일
// 실제 사용 시 dbconfig.php로 복사하고 아래 값을 수정하세요.

// 데이터베이스 서버 호스트 (예: localhost)
$servername = "localhost";

// 데이터베이스 사용자명
$username = "your_username";

// 데이터베이스 비밀번호
$password = "your_password";

// 데이터베이스 이름
$dbname = "your_database_name";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 오류 체크
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}
?>