
<?php

// 차단할 IP
$blocked_ips = [
    '123.123.123.123'
];

// 토큰
$valid_token = 'abc123';

// Google Forms iframe 링크
$google_form_embed = 'https://docs.google.com/forms/d/e/1FAIpQLSdE8qLFs_VnMr9l3njeIjCdFK7G1P9umoaT4ufDz_UTuhLzpA/viewform?embedded=true';

// 현재 사용자 IP
$user_ip = $_SERVER['REMOTE_ADDR'] ?? '';

// IP 차단 검사
if (in_array($user_ip, $blocked_ips)) {
    http_response_code(403);
    die('접근이 차단되었습니다.');
}

// 토큰 검사
$token = $_GET['token'] ?? '';

if ($token !== $valid_token) {
    http_response_code(403);
    die('잘못된 접근입니다.');
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Google Form</title>

    <style>

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #fff;
        }

        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }

    </style>

</head>
<body>

<iframe
    src="<?= htmlspecialchars($google_form_embed) ?>"
    loading="lazy">
</iframe>

<script>

// 우클릭 막기
document.addEventListener('contextmenu', event => {
    event.preventDefault();
});

// F12 / 개발자도구 단축키 막기
document.addEventListener('keydown', event => {

    // F12
    if (event.key === 'F12') {
        event.preventDefault();
    }

    // Ctrl+Shift+I
    if (event.ctrlKey && event.shiftKey && event.key === 'I') {
        event.preventDefault();
    }

    // Ctrl+Shift+J
    if (event.ctrlKey && event.shiftKey && event.key === 'J') {
        event.preventDefault();
    }

    // Ctrl+U
    if (event.ctrlKey && event.key === 'u') {
        event.preventDefault();
    }

});

// 개발자도구 감지 시 페이지 숨기기
setInterval(() => {

    if (
        window.outerWidth - window.innerWidth > 160 ||
        window.outerHeight - window.innerHeight > 160
    ) {
        document.body.innerHTML = '';
    }

}, 1000);

</script>


</body>
</html>