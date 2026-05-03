<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: delete.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . '/../config/dbconfig.php';

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    if ($conn->connect_error) {
        die("MySQL 연결 실패: " . $conn->connect_error);
    }

    $sql = "SELECT id, username, password FROM users WHERE username = '$input_username'";
    $result = $conn->query($sql);


    include(__DIR__ . '/../config/discord_webhook.php');

    header("Location: delete.php");
    exit();
} else {
    echo "<aside id='popup'><p>아이디($input_username) 또는 비밀번호($input_password)가 잘못되었습니다.</p></aside>";
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/css/login.css">
    <title>SamSam 인사팀 로그인</title>
</head>

<body>
    <div id="wrap">
        <h1><a href="#"><img src="https://cdn.discordapp.com/attachments/927518910864052264/1146529153672294530/ANGEL_LOGO.gif" alt="엔젤 로고"></a></h1>
        <h2>SamSam 인사팀 로그인</h2>
        <p>로그인을 해서 합격자 시스템을 이용해보세요!</p>
        <form action="login.php" method="post">
            <input type="text" id="username" name="username" placeholder="아이디" required>
            <input type="password" id="password" name="password" placeholder="비밀번호" required>

            <input type="submit" value="로그인">
        </form>
    </div>
</body>

</html>